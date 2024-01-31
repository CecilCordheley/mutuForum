<?php
require_once "_class/_master/autoload.class.php";
Autoloader::register();
Autoloader::callRequires();
Router::Init();
$template=new easyTemplate();
$vars=new EasyTemplate_variable();
$template->loop("menu",Main::$links);
//$template->callTemplate("mainContent",Router::getTemplate());
Router::setMainTemplate($template,"mainContent");
include(Router::getCtrl());
Router::LoadStyles($template);
$template->loadDictionnary($vars);
$template->display();