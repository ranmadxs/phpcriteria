<?php
/* 
 *   PHPCriteria Criteria Result
 */

/**
 * Description of CriteriaResult
 *
 * @author edgar
 */
class CriteriaResult {
    protected $object;
    protected $list;
    protected $numRows;
    protected $arrayList;
    protected $insertID;
    protected $result;

    function __construct($criteria) {
        $this->object = $criteria->uniqueResult();
        $this->list = $criteria->listResult();
        $this->numRows = $criteria->getNumRows();
        $this->insertID = $criteria->getInsertID();
        $this->result = $criteria->getResult();
    }

    /**
     * Función que retorna el resultado de la query en un arreglo
     * @return Array
     */
    public function getArrayList() {
       if(is_array($this->arrayList))
            unset($this->arrayList);

       $this->arrayList = array();

       while ($row = MySQL_DB::instance()->DBFetchArray($this->result)){
                $this->arrayList[] = $row;
       }
       return $this->arrayList;
    }

    public function getResult() {
        return $this->result;
    }

    public function getInsertID() {
        return $this->insertID;
    }

    protected function setInsertID($insertID) {
        $this->insertID = $insertID;
    }

    public function getNumRows() {
        return $this->numRows;
    }

    protected function setNumRows($numRows) {
        $this->numRows = $numRows;
    }

    /*
     * Función que retorna un objeto único
     * @return Object
     */
    public function uniqueResult(){
        return $this->object;
    }

    /*
     * Función que retorna un arreglo de objetos
     * @return Array[Object]
     */
    public function listResult(){
        return $this->list;
    }

    protected function setObject($object) {
        $this->object = $object;
    }

    protected function setList($list) {
        $this->list = $list;
    }

}
?>