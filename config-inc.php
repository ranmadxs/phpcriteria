<?php
##############################################################
############ PATH PHP CRITERIA ###############################
##############################################################

define("CRITERIA_PATH_RELATIVE",dirname(__FILE__).DIRECTORY_SEPARATOR);
define("CRITERIA_PATH_XML_CLASS_GENERATED", CRITERIA_PATH_RELATIVE."generation".DIRECTORY_SEPARATOR);


##############################################################
############ DATABASE PHP CRITERIA ###########################
##############################################################

define("CRITERIA_DB_HOST", BD_HOST);
define("CRITERIA_DB_USER", BD_USUARIO);
define("CRITERIA_DB_PASSWORD", BD_CLAVE);
define("CRITERIA_DB_DEFAUTL", $_SESSION['base_datos']->nombrebd);

##############################################################
############ TYPE DB PHP CRITERIA ############################
##############################################################
define("CRITERIA_TYPE_DB_MYSQL", "MySQL");

?>