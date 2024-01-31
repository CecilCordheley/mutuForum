<?php
require_once "../_class/_master/easyFrameWork.class.php";
require_once "../_class/_master/autoload.class.php";
require_once "../_class/formBuilder.class.php";
require_once "../_class/Main.class.php";
easyFrameWork::INIT("../_class/_master","../include/router.json");
Autoloader::register();
Autoloader::callRequires("../_class/_master");
Router::Init("../include/router.json");
//SQlFactory
$sqlF=new SQLFactoryV2(null,"../include/config.ini");
$fields = ($sqlF->getColumns("utilisateur_tbl"));
$pattern = "\n<div class=\"mb-3 row\">\n" .
    "\t<label for=\"[#ID#]\" class=\"col-sm-2 col-form-label\">[#label#]</label>\n" .
    "\t <div class=\"col-sm-10\">\n" .
    "\t<input class=\"form-control\"  id=\"[#ID#]\" type=\"[#type#]\" name=\"[#name#]\">\n" .
    "\t</div>" .
    "</div>";
$form = new FormBuilder("ADMIN_user.php?act=add", "POST", $pattern);
array_walk($fields, function ($item) use ($form, $sqlF) {
    $exclude = ["ID_USER", "ARRIVE_UTILISATEUR","VALID_USER","MDP_USER"];
    if (!in_array($item["NAME"], $exclude)) {
        if (in_array($item["NAME"], ["ID_ORGA", "TYPE_UTILISATEUR"])) {
            $type = "select";
            if ($item["NAME"] == "ID_ORGA") {
                $data = Main::getSelectOrga($sqlF);
                $label = str_replace("ID_ORGA", "ORGANISME", $item["NAME"]);
                $required=false;
            } else {
                $data = Main::getSelectTypeUser($sqlF);
                $label = str_replace("TYPE_UTILISATEUR", "TYPE", $item["NAME"]);
                $required=true;
            }
            
            
            $form->addCompoment([
                "ID" => $item["NAME"],
                "type" => $type,
                "className" => "form-select",
                "required" => $required,
                "name" => $item["NAME"],
                "label" => $label,
                "data" => $data
            ]);
        } else {
            $type = ($item["NAME"] == "MDP_USER") ? "password" : "text";
            $form->addCompoment([
                "ID" => $item["NAME"],
                "type" => $type,
                "name" => $item["NAME"],
                "label" => str_replace("_USER", "", $item["NAME"])
            ]);
        }
    }
});
echo ($form->generate());