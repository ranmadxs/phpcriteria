<?php
define("LIB_CRITERIA_ADDENDUM", "../addendum");
##############################################################
########### PATH PHP CRITERIA ###############################
#############################################################
define("CRITERIA_PATH_RELATIVE",dirname(__FILE__).DIRECTORY_SEPARATOR);
define("CRITERIA_PATH_XML_CLASS_GENERATED", CRITERIA_PATH_RELATIVE."generation".DIRECTORY_SEPARATOR);
require_once 'utils/LoadPersistence.php';
##############################################################
########### DATABASE PHP CRITERIA ###########################
#############################################################
define("CRITERIA_DB_HOST", DB_HOST);
define("CRITERIA_DB_USER", DB_USER);
define("CRITERIA_DB_PASSWORD", DB_PASSWORD);
define("CRITERIA_DB_DEFAUTL", DB_DEFAUTL);
##############################################################
############ TYPE DB PHP CRITERIA ############################
##############################################################
define("CRITERIA_TYPE_DB_MYSQL", $_SESSION["persistence"]["persistenceUnit"]);
?>
