<?php

require "database.php";

class table
{
    private $query;
    private $dataperPage;
    private $currentPage;
    private $datas;
    private $columnName;

    /**
     * table constructor.
     * @param $query
     * @param $dataperPage
     */
    public function __construct($query, $dataperPage, $colname)
    {
        $this->query = $query;
        $this->dataperPage = $dataperPage;
        $this->currentPage = 1;
        $this->columnName = $colname;
        $this->requery();
    }

    public function requery(){
        $db = new database();
        $conn = $db->connectDB();
        $currentQuery = $this->query;
        $stmt = $conn->prepare($currentQuery);
        $stmt->execute();
        $this->datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function pagedQuery($page){
        $db = new database();
        $conn = $db->connectDB();
        $currentQuery = $this->query;
        $stmt = $conn->prepare($currentQuery);
        $stmt->execute();
        $this->datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @return mixed
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param mixed $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }

    /**
     * @return mixed
     */
    public function getDataperPage()
    {
        return $this->dataperPage;
    }

    /**
     * @param mixed $dataperPage
     */
    public function setDataperPage($dataperPage)
    {
        $this->dataperPage = $dataperPage;
    }

    /**
     * @return mixed
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @param mixed $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    public function generateView($page){
        $html ="<table><tr>";
        foreach ($this->columnName as $th){
            $html = $html."<th>".$th." </th>";
        }
        $html = $html."</tr>";

        foreach ($this->datas as $dataRow){
            $html = $html."<tr>";

            foreach ($dataRow as $data){
                $html = $html."<td>";
                $html = $html.$data;
                $html = $html."</td>";
            }
            $html = $html."</tr>";
        }
        echo $html;
    }
}