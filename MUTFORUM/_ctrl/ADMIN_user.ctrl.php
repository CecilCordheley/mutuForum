<?php
//ici le code PHP de la page
#$template pour gérer le moteur
#$vars pour gérer les variables {var:???}
Main::setConnexion($vars, $template);
$user = Main::getUSer();
$vars->user=$user->getArray();
SqlEntities::LoadEntity("utilisateur_tbl");
if (isset($_GET["act"])) {
    if ($_GET["act"] == "add") {
        $post = Query::getAll();
        $user = [
            "PSEUDO_USER" => $post["values"]["PSEUDO_USER"],
            "MAIL_USER" => $post["values"]["MAIL_USER"],
            "ARRIVE_UTILISATEUR" => $post["date"],
            "MDP_USER" => (easyFrameWork::hashString("123456")),
            "ID_ORGA" => $post["values"]["ID_ORGA"],
            "DATA_USER" => $post["values"]["DATA_USER"],
            "VALID_USER" => "0",
            "TYPE_UTILISATEUR" => $post["values"]["TYPE_UTILISATEUR"]
        ];
        if ($sqlF->addItem($user, "utilisateur_tbl"))
            $vars->content = "Votre compte a été créé";
        header("Location:ADMIN_user.php");
    }elseif($_GET["act"]=="seeUser"){
       // echo "POP";
       SqlEntities::LoadEntity("message_tbl");
        $seeUser=UtilisateurTbl::getUtilisateurTblBy($sqlF,"ID_USER",$_GET["ID"]);
        $display=$seeUser->getArray();
        $display["ORGANISATION"]=$seeUser->getOrganisation($sqlF)->NOM_ORGANISATION;
        $messages=MessageTbl::getMessageTblBy($sqlF,"ID_USER",$seeUser->ID_USER);
        if(gettype($messages)=="array")
            $nb=count($messages);
        elseif(gettype($messages)=="MessageTbl")
            $nb=1;
        else
            $nb=0;
        $display["NBMESSAGE"]=$nb;
        $vars->seeUser=$display;
        $template->addScript("window.addEventListener('load', () => {
            var modal=document.querySelector('#userModal');
            modal.classList.add('show');
            modal.style.display='block';
           });");
    }
}

if ($user->TYPE_UTILISATEUR == 2)
    $a = UtilisateurTbl::getUtilisateurTblBy($sqlF, "ID_ORGA", $user->ID_ORGA);
else
    $a = UtilisateurTbl::getAll($sqlF);
$i = 0;
$display = array_reduce($a, function ($carry, $item) use (&$i, $sqlF) {
    $carry[$i] = $item->getArray();
    $carry[$i]["NOM_ORGANISATION"] = $item->getOrganisation($sqlF)->NOM_ORGANISATION;
    $carry[$i]["ID_STATUT"] = $item->getLastState($sqlF)["ID"];
    $carry[$i]["LIBELLE_STATUT"] = $item->getLastState($sqlF)["LIBELLE"];
    $carry[$i]["LIBELLE_TYPE"] = $item->getTypeUSer($sqlF)->LIBELLE_Type_Utilisateur;
    $i++;
    return $carry;
}, []);
//easyFrameWork::Debug($display);
$template->loop("user", $display);
$pattern = "\n<div class=\"mb-3 row\">\n" .
    "\t<label for=\"[#ID#]\" class=\"col-sm-2 col-form-label\">[#label#]</label>\n" .
    "\t <div class=\"col-sm-10\">\n" .
    "\t<input class=\"form-control\"  id=\"[#ID#]\" type=\"[#type#]\" name=\"[#name#]\">\n" .
    "\t</div>" .
    "</div>";
$form = new FormBuilder("ADMIN_user.php?act=add", "POST", $pattern);
$fields = ($sqlF->getColumns("utilisateur_tbl"));
array_walk($fields, function ($item) use ($form, $sqlF) {
    $exclude = ["ID_USER", "ARRIVE_UTILISATEUR", "VALID_USER", "MDP_USER"];
    if (!in_array($item["NAME"], $exclude)) {
        if (in_array($item["NAME"], ["ID_ORGA", "TYPE_UTILISATEUR"])) {
            $type = "select";
            if ($item["NAME"] == "ID_ORGA") {
                $data = Main::getSelectOrga($sqlF);
                $label = str_replace("ID_ORGA", "ORGANISME", $item["NAME"]);
                $required = false;
            } else {
                $data = Main::getSelectTypeUser($sqlF);
                $label = str_replace("TYPE_UTILISATEUR", "TYPE", $item["NAME"]);
                $required = true;
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
$vars->addUser = $form->generate(["submit" => "<button type='submit' class='btn btn-primary'>Enregistrer</button>"]);
