<?php
//var_dump($_GET);
require_once "../_class/_master/easyFrameWork.class.php";
/* Initialise le FrameWork et ses dépendances */
easyFrameWork::INIT("../_class/_master", "../include/router.json");
Router::Init("../include/router.json");
$common=(easyFrameWork::getAsyncArgs());


$sqlF = new SQLFactoryV2(null, "../include/config.ini");
$common["sqlF"]=$sqlF;
SqlEntities::$DIRECTORY = "../SQLEntities";
echo call_user_func($_GET["fnc"],$common);
function getMutuelle($args){
    $sqlF=$args["sqlF"];
    SqlEntities::LoadEntity("organisme_tbl");
    $orga=OrganismeTbl::getOrganismeTblBy($sqlF,"ID_ORGA",$args["ID"]);
    return json_encode($orga->getArray()); 
}