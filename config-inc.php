<?php
##############################################################
############ PATH PHP CRITERIA ###############################
##############################################################

define("CRITERIA_PATH_RELATIVE",dirname(__FILE__).DIRECTORY_SEPARATOR);
define("CRITERIA_PATH_XML_PERSIST", CRITERIA_PATH_RELATIVE."persist.xml");
define("CRITERIA_PATH_XML_PERSIST_GENERATED", CRITERIA_PATH_RELATIVE."generation".DIRECTORY_SEPARATOR."persist_generated.xml");
define("CRITERIA_PATH_XML_CLASS_GENERATED", CRITERIA_PATH_RELATIVE."generation".DIRECTORY_SEPARATOR);


##############################################################
############ DATABASE PHP CRITERIA ###########################
##############################################################

define("CRITERIA_DB_HOST", "");
define("CRITERIA_DB_USER", "");
define("CRITERIA_DB_PASSWORD", "");
define("CRITERIA_DB_DEFAUTL", "");

##############################################################
############ TYPE DB PHP CRITERIA ############################
##############################################################

define("CRITERIA_TYPE_DB_MYSQL", "MySQL");

?>