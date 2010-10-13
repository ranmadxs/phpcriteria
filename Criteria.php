<?php
include_once 'config-inc.php';
include_once 'CriteriaResult.php';
include_once 'conection/DBSQL.php';
//include_once(MAIN_PATH."phpLib/MySQL_Lib.php");
/**
 * Description of criteria
 *
 * @author edgar
 */


class Criteria extends CriteriaResult{
    //put your code here
    private $dbh;
    private $xml;
    private $array_order;
    private $type_order;
    private $SQL;
    private $db;
    private $className;
    private $table;
    private $flagCreateCtriteria;
    private $array_restrictions;
    private $object;

    public function getSQL() {
        return $this->SQL;
    }

    public function setSQL($SQL) {
        $this->SQL = $SQL;
        return $this;
    }

    public function execute($queryType = CriteriaProperty::QUERY_SQL_SELECT){
        $this->result = MySQL_DB::instance()->DBQuery($this->SQL, $this->dbh);

        try {
            if(mysql_affected_rows() > 0 && $queryType != CriteriaProperty::QUERY_SQL_UPDATE)
                $this->setNumRows(mysql_num_rows($this->result));
            $this->setInsertID(mysql_insert_id($this->dbh));
        } catch (Exception $e) {
            $e->getTrace();
        }

        return $this;
    }

    public function createCriteria($object){		
        $oReflectionClass = new ReflectionClass($object);
        $properties = $oReflectionClass->getProperties();
        $this->className = $oReflectionClass->getName();    
        $this->table = $this->findTable($this->className);
        $this->flagCreateCtriteria = true;
        $this->object = $object;
        $this->SQL = MySQL_DB::instance()->DBSQLSelect($this->table, null, $this->array_restrictions, $this->array_order, $this->type_order, true);

    }

    public function addCriteria($restrictions){
        if(!$this->flagCreateCtriteria)
            throw new Exception('Necesita inicializar el criteria');

        if(!is_array($this->array_restrictions))
            $this->array_restrictions = array("1"=>$restrictions);
        else
            array_push($this->array_restrictions, $restrictions);
        $this->SQL = MySQL_DB::instance()->DBSQLSelect($this->table, null, $this->array_restrictions, $this->array_order, $this->type_order, true);
    }

    public function lista(){
        if($this->flagCreateCtriteria){
            $this->execute();
            Esto de abajo hay que corregir el reflecction, pero debe funcionar

           $oReflectionClass = new ReflectionClass($this->object);
           $properties = $oReflectionClass->getProperties();
           $class = $oReflectionClass->getName();


            if($this->getNumRows() > 1){
                while ($row = MySQL_DB::instance()->DBFetchArray($this->result)){
                    $object_new = $oReflectionClass->newInstance($oReflectionClass);
                    $object_new = $this->iterateProperty($class, $object_new, $row, $properties);
                    $list[] = $object_new;
                }
            $this->setList($list);
            }
            
        }
        $this->listResult();
    }

    protected function getDatabaseTables(){
        $this->setSQL("SHOW TABLES")->execute();
        return $this->getArrayList();
    }

    protected function getDescTable($table){
        $this->setSQL("DESC ".$table)->execute();
        return $this->getArrayList();
    }

    public function setOrder($atribute, $type_order = CriteriaProperty::ORDER_ASC){
        if(!is_array($this->array_order))
            $this->array_order = array("1"=>$atribute);
        else
            array_push($this->array_order, $atribute);
        $this->type_order = $type_order;

        return $this;
    }

    public function setType_order($type_order) {
        $this->type_order = $type_order;
    }

    function __construct($db = null) {
        MySQL_DB::instance()->DBConnect($this->dbh, $db);
        $this->xml = simplexml_load_file(CRITERIA_PATH_XML_PERSIST);
    }
    
    public function begin(){
        MySQL_DB::instance()->DBBegin($this->dbh);
        //DBBegin($this->dbh);
        return $this;
    }

    public function commit(){
        MySQL_DB::instance()->DBCommit($this->dbh);
        return $this;
    }
    
    public function rollBack(){
        MySQL_DB::instance()->DBRollback($this->dbh);
        return $this;
    }

    public function persist($object){
        
    }

    public function merge($object){
        $oReflectionClass = new ReflectionClass($object);
        $properties = $oReflectionClass->getProperties();
        $class = $oReflectionClass->getName();
        $pks = $this->findPkXML($class);
        $object_old = $oReflectionClass->newInstance($oReflectionClass);
        foreach ($pks as $key_pk => $name_pk) {
            $prop = new ReflectionProperty($class, $name_pk);
            $value = $prop->getValue($object);
            $prop->setValue($object_old, $value);
            $datos_where[$name_pk] = $value;            
        }
        $this->find($object_old);
        
        if(!$this->uniqueResult()){
           $this->rollBack();
           throw new Exception('El objeto '.$class." no tiene resultado unico");
        }
        $table = $this->findTable($class);
        $oReflectionClass = new ReflectionClass($object);
        $properties = $oReflectionClass->getProperties();
        foreach ($properties as $key => $reflectionProperty)
            $datos_set[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);

        $this->SQL = MySQL_DB::instance()->DBSQLUpdate($datos_set, $datos_where, $table, true);
        $this->execute(CriteriaProperty::QUERY_SQL_UPDATE);
        return $this;
    }
    /**
     * Se debe explorar el Criteria Result para ver el tipo de objeto que retorna
     * @param <type> Object
     * @return <type> CriteriaResult
     */
    public function find($object){
        $oReflectionClass = new ReflectionClass($object);
        //$properties = $oReflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        $properties = $oReflectionClass->getProperties();
        $class = $oReflectionClass->getName();
        //dpr($properties);

        foreach ($properties as $key => $reflectionProperty) 
            $datos_where[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);

//            $prop = new ReflectionProperty($class, $reflectionProperty->getName());
//            $prop->setAccessible(true); /* As of PHP 5.3.0 */
            //var_dump($prop->getValue($obj)); // int(2)

        $this->setList(null);
        $this->setObject(null);

        $table = $this->findTable($class);
        $this->SQL = MySQL_DB::instance()->DBSQLSelect($table, null, $datos_where, $this->array_order, $this->type_order);
        //$this->SQL = DBSQLSelect($table, null, $datos_where, $this->array_order, $this->type_order);
        $this->execute();
        //$this->result = DBQuery($this->SQL, $this->dbh);
        if($this->getNumRows() == 1){
            $row = MySQL_DB::instance()->DBFetchArray($this->result);
            $object = $this->iterateProperty($class, $object, $row, $properties);
            $this->setObject($object);
        }

        if($this->getNumRows() > 1){
            while ($row = MySQL_DB::instance()->DBFetchArray($this->result)){
                $object_new = $oReflectionClass->newInstance($oReflectionClass);
                $object_new = $this->iterateProperty($class, $object_new, $row, $properties);
                $list[] = $object_new;
            }
            $this->setList($list);
        }

        return $this;
    }

    private function iterateProperty($class, $object, $row, $properties){
            foreach ($properties as $key => $reflectionProperty){
                $prop = new ReflectionProperty($class, $reflectionProperty->getName());
                $prop->setValue($object, $row[$reflectionProperty->getName()]);
            }
            return $object;
    }

    private function findPkXML($class){
        foreach ($this->xml->table as $key => $dataXML) {
            $column = $dataXML->column;
            $atributes_table = $dataXML->attributes();
            if($atributes_table->class == $class){
                foreach ($column as $key_p => $data) {
                    $atributes = $data->attributes();
                    $pkKey = (string) $atributes->Key;
                    if(strlen($pkKey)>0){
                        $atributes_column[] = (string) $atributes->Field;
                    }
                }
                return $atributes_column;
            }
        }
        $this->rollBack();
        throw new Exception('La clase '.$class." no tiene definidas claves");
    }

    private function findTable($class){
        foreach ($this->xml->table as $key => $dataXML) {
            $atributes = $dataXML->attributes();
            if($atributes->class == $class){
                return (string) $atributes->name;
            }
        }
        $this->rollBack();
        throw new Exception('La clase '.$class." no se encuentra en el contexto de persistencia del xml");
    }

}
?>