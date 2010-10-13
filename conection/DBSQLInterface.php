<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author edgar
 */
interface DBSQLInterface {
    public function DBConnect(&$dbh, $db = null);
    public function DBQuery($SQL_query, $dbh);
    public function DBCambioPermanente($anio);
    public function DBFetchArray($result);
    public function DBError($sentencia);
    public function DBBegin(&$dbh);
    public function DBCommit(&$dbh);
    public function DBRollback(&$dbh);
    public function DBSQLSelect($table, $array_atributos = null, $datos_where = null, $array_order = null, $type_order = "");
    public function DBSQLInsert($array, $table);
    public function DBSQLUpdate($datos_set, $datos_where, $table, $autocompleteNull=false);
}
?>
