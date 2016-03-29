<?php
include_once 'MySQL_DB.php';

/**
 * Esta se debe utilizar para el patrón factory
 */


class DBSQL{

   private static $dbsql;

   public static function getInstance()
   {
      if (  !self::$dbsql instanceof self)
      {
         self::$dbsql = new self;
      }
      return self::$dbsql;
   }


}


?>
