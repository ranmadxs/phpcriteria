<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CriteriaProperty
 *
 * @author edgar
 */
class CriteriaProperty {
    const ORDER_ASC = "ASC";
    const ORDER_DESC = "DESC";

    const QUERY_SQL_UPDATE = "UPDATE";
    const QUERY_SQL_SELECT = "SELECT";
    const QUERY_SQL_INSERT = "INSERT";

    const FORM_PERSIST_GENERATED = "GENERAR_PERSIST";
    const FORM_PERSIST_LOAD = "CARGAR_PERSIST";
}

class FetchType {
    const INNER_JOIN = "INNER JOIN";
    const LEFT_JOIN = "LEFT JOIN";
}
?>
