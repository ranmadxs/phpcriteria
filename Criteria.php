<?php
include_once 'config-inc.php';
include_once 'CriteriaResult.php';
include_once 'CriteriaProperty.php';
include_once 'conection/DBSQL.php';
include_once 'CriteriaEntityDescriptors.php';
include_once 'CriteriaEntityMgr.php';
/**
 * Descripción de criteria
 * Framework que permite abstraer la capa de persistencia de datos, modelando en objetos sus entidades.
 * Criteria es un ORM (Objeto de Modelo Relacional) que nos permite efectuar consultas
 * a la base de datos sin necesidad de efectuar consultas directas en SQL, facilitandonos
 * de esta manera el trabajo.
 * @version 1.1 07/Nov/2010
 * @uses
 * $criteria = new Criteria();
 * $entity = new Entity();
 * $criteria->createCriteria($entity);
 * $criteria->add(Restrictions::ge("field", value));
 * $criteria->lista();
 *
 * @author edgar
 */
class Criteria extends CriteriaResult {

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
    private $objectClass;

    public function getSQL() {
        return $this->SQL;
    }

    public function setSQL($SQL) {
        $this->SQL = $SQL;
        return $this;
    }

    public function execute($queryType = CriteriaProperty::QUERY_SQL_SELECT) {
        $this->result = MySQL_DB::instance()->DBQuery($this->SQL, $this->dbh);
        try {
            if(mysql_affected_rows() > 0 && $queryType != CriteriaProperty::QUERY_SQL_UPDATE && $queryType != CriteriaProperty::QUERY_SQL_INSERT)
                $this->setNumRows(mysql_num_rows($this->result));
            $this->setInsertID(mysql_insert_id($this->dbh));
        } catch (Exception $e) {
            $e->getTrace();
        }

        return $this;
    }

    public function createCriteria($object) {
        $oReflectionClass = new ReflectionClass($object);
        $properties = $oReflectionClass->getProperties();
        $this->className = $oReflectionClass->getName();
        $this->table = CriteriaEntityMgr::instance()->findTable($this->className);
        $this->flagCreateCtriteria = true;
        $this->objectClass = $object;
        $this->SQL = MySQL_DB::instance()->DBSQLSelect($this->table, null, $this->array_restrictions, $this->array_order, $this->type_order, true);
    }

    public function add($restrictions) {
        if(!$this->flagCreateCtriteria)
            throw new Exception('Necesita inicializar el criteria');

        if(!is_array($this->array_restrictions))
            $this->array_restrictions = array("1"=>$restrictions);
        else
            array_push($this->array_restrictions, $restrictions);
        $this->SQL = MySQL_DB::instance()->DBSQLSelect($this->table, null, $this->array_restrictions, $this->array_order, $this->type_order, true);
    }

    public function lista() {

        if($this->flagCreateCtriteria) {
            $this->execute();
            $oReflectionClass = new ReflectionClass($this->objectClass);
            $properties = $oReflectionClass->getProperties();
            $class = $oReflectionClass->getName();
            if($this->getNumRows() > 0) {
                while ($row = MySQL_DB::instance()->DBFetchArray($this->result)) {
                    $object_new = $oReflectionClass->newInstance($oReflectionClass);
                    $object_new = $this->iterateProperty($class, $object_new, $row, $properties);
                    $list[] = $object_new;
                }
                $this->setList($list);
            }
        }
        return $this->listResult();
    }

    public function getDatabaseTables() {
        $this->setSQL("SHOW TABLE STATUS FROM ".$this->db)->execute();
        return $this->getArrayList();
    }

    protected function showCreateTable($tableName){
        $this->setSQL("SHOW CREATE TABLE ".$tableName)->execute();
        return $this->getArrayList();
    }


    public function getDescTable($tableName) {
        $this->setSQL("DESC ".$tableName)->execute();
        return $this->getArrayList();
    }

    public function setOrder($atribute, $type_order = CriteriaProperty::ORDER_ASC) {
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
        $this->db = $db;
        //$this->xml = simplexml_load_file(CRITERIA_PATH_XML_PERSIST);
    }

    public function begin() {
        MySQL_DB::instance()->DBBegin($this->dbh);
        //DBBegin($this->dbh);
        return $this;
    }

    public function commit() {
        MySQL_DB::instance()->DBCommit($this->dbh);
        return $this;
    }

    public function rollBack() {
        MySQL_DB::instance()->DBRollback($this->dbh);
        return $this;
    }

    public function persist($object) {
        $oReflectionClass = new ReflectionClass($object);
        $properties = $oReflectionClass->getProperties();
        $this->className = $class = $oReflectionClass->getName();
        $this->table = CriteriaEntityMgr::instance()->findTable($this->className);
        foreach ($properties as $key => $reflectionProperty)
            $datos_set[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);
        $this->SQL = MySQL_DB::instance()->DBSQLInsert($datos_set, $this->table);
        $this->execute(CriteriaProperty::QUERY_SQL_INSERT);
        return $this;
    }

    public function merge($object) {
        $oReflectionClass = new ReflectionClass($object);
        $properties = $oReflectionClass->getProperties();
        $this->className = $class = $oReflectionClass->getName();
        $pks = CriteriaEntityMgr::instance()->findPks($this->className);
        $object_old = $oReflectionClass->newInstance($oReflectionClass);
        foreach ($pks as $key_pk => $name_pk) {
            $prop = new ReflectionProperty($class, $name_pk);
            $value = $prop->getValue($object);
            if(strlen($value) == 0)
                Throw new Exception ("La clave primaria ".$name_pk." viene nula");
            $prop->setValue($object_old, $value);
            $datos_where[$name_pk] = $value;
        }

        $this->find($object_old);

        if(!$this->uniqueResult()) {
            $this->rollBack();
            throw new Exception('El objeto '.$class." no tiene resultado unico");
        }
        $table = $this->table = CriteriaEntityMgr::instance()->findTable($this->className);
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
    public function find($object) {
        $oReflectionClass = new ReflectionClass($object);
        //$properties = $oReflectionClass->getProperties(ReflectionProperty::IS_PUBLIC);
        $properties = $oReflectionClass->getProperties();
        $this->className = $class = $oReflectionClass->getName();

        foreach ($properties as $key => $reflectionProperty)
            $datos_where[$reflectionProperty->getName()] = $reflectionProperty->getValue($object);
        $this->setList(null);
        $this->setObject(null);
        $table = $this->table = CriteriaEntityMgr::instance()->findTable($this->className);
        $this->SQL = MySQL_DB::instance()->DBSQLSelect($table, null, $datos_where, $this->array_order, $this->type_order);
        $this->execute();
        if($this->getNumRows() == 1) {
            $row = MySQL_DB::instance()->DBFetchArray($this->result);
            $object = $this->iterateProperty($class, $object, $row, $properties);
            $this->setObject($object);
        }
        if($this->getNumRows() > 1) {
            while ($row = MySQL_DB::instance()->DBFetchArray($this->result)) {
                $object_new = $oReflectionClass->newInstance($oReflectionClass);
                $object_new = $this->iterateProperty($class, $object_new, $row, $properties);
                $list[] = $object_new;
            }
            $this->setList($list);
        }
        return $this;
    }

    private function iterateProperty($class, $object, $row, $properties) {
        foreach ($properties as $key => $reflectionProperty) {
            $prop = new ReflectionProperty($class, $reflectionProperty->getName());
            $prop->setValue($object, $row[$reflectionProperty->getName()]);
        }
        return $object;
    }

}
?>
