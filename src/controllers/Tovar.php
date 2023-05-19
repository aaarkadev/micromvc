<?php

require_once(APP_BASE.'src/Response.php');

class Tovar {

    const TABLE_NAME = 'tovars';
    private $db;
    private $baseUrl = '';

    public function __construct(Request $request=null, View $view=null, Db $db=null) {
        $this->db = $db;
        if(!is_null($request)) {
            $this->baseUrl = $request->data['baseUrl'];
        }
    }

    public function __invoke(Request $request, View $view, Db $db): Response {
        $this->db = $db;
        $view->SetTemplate('tovar');

        if(empty($this->baseUrl)) {
            $this->baseUrl = $request->data['baseUrl'];
        }

        $uri = $request->data['server']['REQUEST_URI'];
        $uri = explode('/',trim($uri,' /'));
        $uri = end($uri);
        list($tovar_id, ) = explode('-',$uri,2);
        $tovar_id = abs(intval($tovar_id));

        if(empty($tovar_id)) {
            header($request->data['server']['SERVER_PROTOCOL'] . ' 404 not found', true, 404);
            throw new \Error('404 tovar not found');
        }
        $pageData = array();
        $pageData['tovar'] = $this->getTovar($tovar_id);

        return new Response($pageData);
    }

    public function getTovar(int $tovar_id): array {
        $tovar = array();
 
        $sql = "SELECT *
                        FROM ".self::TABLE_NAME." 
                        WHERE id = ".intval($tovar_id)."
                        LIMIT 1";

        $rows = $this->db->query($sql); 
        if(!empty($rows)) {
            foreach($rows as $row) {
                $row['url'] = $this->genUrl($row);
                $row['breadcrumbs'] = $this->genBreadcrumbs($row);
                $tovar = $row;
            }
        }

        return $tovar;
    }

    public function genBreadcrumbs(array $tovar): array {
        $breadcrumbs = array();

        $catObj = new Category(null, null, $this->db);
        $flatCategories = $catObj->FlatternCategoryTree($catObj->GetAllCategories(0));

        $category_id = $tovar['category_id'];
        while(true) {
            if(isset($flatCategories[$category_id])) {
                $cat = $flatCategories[$category_id];
                $breadcrumbs[$cat['name']] = $this->baseUrl;
                $category_id = $cat['parent_id'];
            } else {
                break;
            }
        }
        $breadcrumbs['Главная'] = $this->baseUrl;
        $breadcrumbs = array_reverse( $breadcrumbs, true);
        $breadcrumbs['товар'] = $tovar['url'];

        return $breadcrumbs;
    }

    public function getTovars(int $category_id=0): array {
        $tovars = array();

        $whereSql = " category_id > 0";
        if(intval($category_id)>0) {
            $whereSql = " category_id = ".intval($category_id);
        }
        $sql = "SELECT id, category_id, name, slug, price, prio
                        FROM ".self::TABLE_NAME." 
                        WHERE ".$whereSql." 
                        ORDER BY category_id ASC, prio DESC, name ASC, id ASC";

        $rows = $this->db->query($sql); 
        if(!empty($rows)) {
            foreach($rows as $row) {
                $row['url'] = $this->genUrl($row);
                $tovars[$row['id']] = $row;
            }
        }

        return $tovars;
    }

    public function genUrl(array $tovar): string {
        $slug  = $tovar['slug'];
        if(empty($slug)) {
            $slug= $tovar['name'];
        }
        $slug = trim($this->ru2Lat($slug));
        $slug = preg_replace('/[^A-z0-9-]/i','-', $slug);
        $slug = preg_replace('/[-]+/s','-', $slug);
        $slug = strtolower($slug);
        $slug = trim($slug,' -');
        $url = $this->baseUrl.'tovar/'.$tovar['id'].(!empty($slug)?'-'.$slug:'');
        return $url;
    }

    protected function ru2Lat($str) {
          $tr = array(
           "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
           "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
           "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
           "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
           "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
           "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
           "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
           "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
           "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
           "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
           "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
           "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
           "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
          ); 
          return strtr($str,$tr);
    }

}
