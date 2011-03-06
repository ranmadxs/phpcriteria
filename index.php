<?php
include_once("../main_Lib.php");
require_once 'config-inc.php';
require_once 'CriteriaProperty.php';
require_once 'CriteriaGenerate.php';
require_once 'Criteria.php';
require_once 'Restrictions.php';

//exit();

//require_once 'lib/dpr_Lib.php';
//phpinfo();
//$lol = dirname(__FILE__);
//dpr($lol);
//dpr( php_uname( 's') );
//dpr(DIRECTORY_SEPARATOR);
/*
if (DIRECTORY_SEPARATOR=='/')
  $absolute_path = dirname(__FILE__).'/';
else
  $absolute_path = str_replace('\\', '/', dirname(__FILE__)).'/'; 
*/
if(isset ($_POST['button'])){

    switch ($_POST['button']) {
        case CriteriaProperty::FORM_PERSIST_GENERATED:
            echo "Guardando Persist";
            CriteriaGenerate::instance()->generateEntity();
            break;

        case CriteriaProperty::FORM_PERSIST_LOAD:
            echo "Cargando Pruebas";
            require_once 'generation/EntityCCA_FILIAL.php';
//            require_once 'generation/EntityArancel.php';
//            $criteria = new Criteria();
//            $filial = new EntityCCA_FILIAL();
//            $filial->ID_FILIAL = 1;
//            $criteria->find($filial);
//            dpr($filial);
//            $filial->APLICACION_ACTUALIZACION = "PruebMerg2";
//            $criteria->merge($filial);
//            $filial->ID_FILIAL = null;
//            $filial->APLICACION_ACTUALIZACION = "Persist";
//            $criteria->persist($filial);
//            $criteria = new Criteria();
//            $criteria->createCriteria(new EntityCCA_FILIAL());
//            dpr($criteria->lista());



//            $arancel = new EntityArancel();
//
//            $criteria->setSQL("SHOW TABLE STATUS FROM baseMAS")->execute();
//            dpr($criteria->getArrayList());
//


            
//            $criteria->createCriteria($arancel);
            //dpr($criteria->lista());
//            $criteria->add(Restrictions::eq("aran_anio", "2010"));
//            $criteria->add(Restrictions::le("aran_monto", 34000));
//            $criteria->add(Restrictions::between("aran_ID", "1", "3"));
//            dpr($criteria->getSQL());
//            $lista = $criteria->lista();
//            dpr($lista);
//            $arancel->aran_ID = 101;
//            $arancel->aran_anio = "2222";
//            $arancel->aran_monto = 22222;
//            $arancel->FK_colegios_colegio_ID = "22222";
//            $arancel->FK_curso = "2222";
//            $criteria->merge($arancel);
//            dpr($criteria);
//            dpr($criteria->getSQL());
           // dpr($lista);
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
