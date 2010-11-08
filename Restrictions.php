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

    /**
     * Restricción: igualdad
     * <br> $field > $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function eq($field, $valor){
        return $field."='".$valor."'";
    }

    /**
     * Restricción: menor o igual
     * <br> $field <= $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function le($field, $valor){
        return $field."<='".$valor."'";
    }

    /**
     * Restricción: mayor o igual
     * <br> $field >= $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function ge($field, $valor){
        return $field.">='".$valor."'";
    }

    /**
     * Restricción: mayor que
     * <br> $field > $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function gt($field, $valor){
        return $field.">'".$valor."'";
    }

    /**
     * Restricción: menor que
     * <br> $field < $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function lt($field, $valor){
        return $field."<'".$valor."'";
    }

    /**
     * Restricción: Entremedio de valores
     * <br> $valor_menor < $field < $valor_mayor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function between($field, $valor_menor, $valor_mayor){
        return Restrictions::gt($field, $valor_menor)." AND ".Restrictions::lt($field, $valor_mayor);
    }
}
?>
