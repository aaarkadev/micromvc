<?php

require_once(APP_BASE.'src/Response.php');
require_once(APP_BASE.'src/controllers/Tovar.php');

class Category {

    const TABLE_NAME = 'categories';
    private $db;

    public function __construct(Request $request=null, View $view=null, Db $db=null) {
        $this->db = $db;
    }

    public function __invoke(Request $request, View $view, Db $db): Response {
        $this->db = $db;
        $view->SetTemplate('category');

        $uri = $request->data['server']['REQUEST_URI'];
        $uri = explode('/',trim($uri,' /'));
        $uri = end($uri);
        if($uri == 'tovar') {
            header($request->data['server']['SERVER_PROTOCOL'] . ' 404 not found', true, 404);
            throw new \Error('404 category not found');
        }

        $pageData = array();
        $pageData['allCategories'] = $this->GetAllCategories(0);
        $pageData['flatCategories'] = $this->FlatternCategoryTree($pageData['allCategories']);

        $tovarObj = new Tovar($request, null, $db);
        $pageData['tovars'] = $tovarObj->getTovars();
        $pageData['treeWithTovars'] = $this->fillTreeWithTovars($pageData['flatCategories'], $pageData['tovars']);

        return new Response($pageData);
    }

    public function fillTreeWithTovars(array $tree, array $tovars): array {
        $fullTree = $tree;
        foreach($fullTree as $key=>$val) { 
            $val['tovars'] = array();
            $fullTree[$key] = $val;
        }
        foreach($tovars as $tovar) {
            $category = (isset($fullTree[$tovar['category_id']])?$fullTree[$tovar['category_id']]:array());
            if(!isset($fullTree[$tovar['category_id']])) {
                continue;
            }
            if(!isset($fullTree[$tovar['category_id']]['tovars'])) {
                $fullTree[$tovar['category_id']]['tovars'] = array();
            }
            $fullTree[$tovar['category_id']]['tovars'][$tovar['id']] = $tovar;
        }
        return $fullTree;
    }

    public function FlatternCategoryTree(array $tree): array {

        $categories = array();
        foreach($tree as $value) {
            $childs = array();
            if(!empty($value['childs'])) {
                $childs = $value['childs'];
                unset($value['childs']);
            }
            $categories[$value['id']] = $value;
            if(!empty($childs)) {
                $categories+=$this->FlatternCategoryTree($childs);
             }
        }
 
        return $categories;
    }
 

    public function GetAllCategories(int $parent_id = 0, int $level=1): array {

        $sql = "SELECT id, parent_id, name, prio 
                        FROM ".self::TABLE_NAME." 
                        WHERE parent_id = ".intval($parent_id)." 
                        ORDER BY prio DESC, name ASC, id ASC";

        $rows = $this->db->query($sql);
        $categories = array();
        if(!empty($rows)) {
            foreach($rows as $row) {
                $childs = $this->GetAllCategories($row['id'], ($level+1));
                if(!empty($childs)) {
                    $row['childs'] = $childs;
                }
                $row['level'] = $level;
                $categories[$row['id']] = $row;
            }
        }
        return $categories;
    }
}
