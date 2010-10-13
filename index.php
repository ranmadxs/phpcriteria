<?php
require_once 'config-inc.php';
require_once 'CriteriaProperty.php';
require_once 'CriteriaGenerate.php';
require_once 'Criteria.php';
require_once 'Restrictions.php';
require_once 'lib/dpr_Lib.php';
if(isset ($_POST['button'])){

    switch ($_POST['button']) {
        case CriteriaProperty::FORM_PERSIST_GENERATED:
            echo "Guardando Persist";
            CriteriaGenerate::instance()->generateXML();
            break;

        case CriteriaProperty::FORM_PERSIST_LOAD:
            echo "Cargando Pruebas";
            require_once 'generation/EntityArancel.php';
            $criteria = new Criteria();
            $arancel = new EntityArancel();
            $criteria->createCriteria($arancel);
//            $criteria->addCriteria(Restrictions::eq("aran_anio", "2010"));
//            $criteria->addCriteria(Restrictions::bet("aran_ID", "1", "11"));
            dpr($criteria->getSQL());
            break;

        default:
            echo "default";
            break;
        }

}
?>
<div>
    <strong>PHPCriteria 1.01</strong>
</div>
<form method="post" id="formGenerarPersist" name="formGenerarPersist" action="">
    <input type="submit" name="button" value="GENERAR_PERSIST" />
    <input type="submit" name="button" value="CARGAR_PERSIST" />
</form>