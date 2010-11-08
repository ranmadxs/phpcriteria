<?php
include_once 'lib/addendum/annotations.php';

class Entity extends Annotation{
   public $Table;
}

class Id extends Annotation{}

class Column extends Annotation{
    public $Field;
    public $Type;
    public $Key;
    public $Null;
    public $Default;
    public $Extra;
}

?>
