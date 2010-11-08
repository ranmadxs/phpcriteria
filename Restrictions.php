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
     * Restricci�n: igualdad
     * <br> $field > $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function eq($field, $valor){
        return $field."='".$valor."'";
    }

    /**
     * Restricci�n: menor o igual
     * <br> $field <= $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function le($field, $valor){
        return $field."<='".$valor."'";
    }

    /**
     * Restricci�n: mayor o igual
     * <br> $field >= $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function ge($field, $valor){
        return $field.">='".$valor."'";
    }

    /**
     * Restricci�n: mayor que
     * <br> $field > $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function gt($field, $valor){
        return $field.">'".$valor."'";
    }

    /**
     * Restricci�n: menor que
     * <br> $field < $valor
     * @param <string> $field
     * @param <string> $valor
     * @return <string> $stringRestriction
     */
    public static function lt($field, $valor){
        return $field."<'".$valor."'";
    }

    /**
     * Restricci�n: Entremedio de valores
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
