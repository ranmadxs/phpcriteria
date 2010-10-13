<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Restrictions
 *
 * @author edgar
 */
class Restrictions{

    public static function eq($field, $valor){
        return $field."='".$valor."'";
    }

    public static function le($field, $valor){
        return $field."<='".$valor."'";
    }

    public static function ge($field, $valor){
        return $field.">='".$valor."'";
    }

    public static function bet($field, $valor_menor, $valor_mayor){
        return Restrictions::ge($field, $valor_menor)." AND ".Restrictions::le($field, $valor_mayor);
    }
}
?>
