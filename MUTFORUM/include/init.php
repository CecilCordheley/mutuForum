<?php
require_once "../_class/_master/autoload.class.php";
Autoloader::register();
Autoloader::callRequires();
Router::Init();
echo "---Initalize template config.ini---\n";
$configName = readline("ConfigName : ");
$masterPage = readline("$configName\n-- master Page : ");
$templateDirectory = readline("$configName\n-- template Directory : ");
$StyleDirectory = readline("$configName\n-- Style Directory : ");
$JSDirectory = readline("$configName\n-- js Directory : ");
$imageDirectory = readline("$configName\n-- image Directory : ");