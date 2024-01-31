<?php
if (isset($argv[1])) {
    if (strpos($argv[1], ' ') !== false) {
        $argw = explode(" ", $argv[1]);
        array_unshift($argw, $argv[2]);
        $argv = $argw;
    }
    //var_dump($argv);

    parse_str(implode('&', array_slice($argv, 1)), $_GET);
    // var_dump($_GET);
}
/*Utiliser ce fichier via un terminal PHP */
require_once "../_class/_master/easyFrameWork.class.php";
/* Initialise le FrameWork et ses dépendances */
easyFrameWork::INIT("../_class/_master", "../include/router.json");
Router::Init("../include/router.json");
$sqlF = new SQLFactoryV2(null, "../include/config.ini");
SqlEntities::$DIRECTORY = "../SQLEntities";
if (isset($_GET)) {
    foreach ($sqlF->getTableArray() as $table => $key) {
        SqlEntities::generateEntity($sqlF, $table);
        echo "\n-----------------\n";
    }
} else {
    foreach ($sqlF->getTableArray() as $table => $key) {
        echo "$table\n";
    }
    $table = readline("Quelle entité souhaitez-vous générer : ");

    SqlEntities::generateEntity($sqlF, $table);
}
