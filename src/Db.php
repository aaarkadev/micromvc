<?php

class Db {

    private $link;

    public function __construct(string $host, string $username, string $password, string $database) {

        @$this->link = mysqli_connect($host, $username, $password, $database);

        if(empty($this->link)) {
            throw new \Error("error connect to DB");
        }


    }

    public function &query(string $sql): array {

        $result = mysqli_query($this->link,$sql);
        $rows = array();
        while(($row=mysqli_fetch_assoc($result))) {
            $rows[] = $row;
        }

        return $rows;
    }
}
