<?php
//ici le code PHP de la page
#$template pour gérer le moteur
#$vars pour gérer les variables {var:???}
Main::setConnexion($vars, $template);
$user = Main::getUSer();
$vars->user = $user->getArray();
SqlEntities::LoadEntity("message_tbl");
SqlEntities::LoadEntity("reponse_tbl");
if (isset($_GET["ID"])) {
    $template->callTemplate("contentMessage", "ADMIN/contentMessage.tpl");
    $m = MessageTbl::getMessageTblBy($sqlF, "ID_MESSAGE", $_GET["ID"]);
    $display = $m->getArray();
    $display["USER"] = $m->getUSer($sqlF)->PSEUDO_USER;
    $display["LIB_SUJET"] = $m->getTheme($sqlF)->LIB_SUJET;
    $display["CONTENT_MESSAGE"] = str_replace("[b]", "<b>", $display["CONTENT_MESSAGE"]);
    $display["CONTENT_MESSAGE"] = str_replace("[/b]", "</b>", $display["CONTENT_MESSAGE"]);
    $display["CONTENT_MESSAGE"] = str_replace("[i]", "<i>", $display["CONTENT_MESSAGE"]);
    $display["CONTENT_MESSAGE"] = str_replace("[/i]", "</i>", $display["CONTENT_MESSAGE"]);
    $display["CONTENT_MESSAGE"] = str_replace("\n", "<br>", $display["CONTENT_MESSAGE"]);
    $display["SHORT_DATE"] = SqlEntities::toShortDate($m->DATE_MESSAGE);
    $vars->message = $display;
    $reponse = (SqlEntities::getArrayEntities($m->getReponses($sqlF), function ($carry, $item) use (&$i, $sqlF) {
        $carry[$i] = $item->getArray();
        $carry[$i]["USER"] = UtilisateurTbl::getUtilisateurTblBy($sqlF, "ID_USER", $item->ID_USER)->PSEUDO_USER;
        preg_match("/([0-9]{4}-[0-9]{2}-[0-9]{2})/i", $item->DATE_REPONSE, $matches);
        $carry[$i]["SHORT_DATE"] = $matches[1];
        $carry[$i]["DISPLAY"] = ($item->DISPLAY == "1") ? "OUI" : "NON";
        return $carry;
    }));
    $template->loop("reponse", $reponse);
}
if (isset($_GET["act"])) {
    if ($_GET["act"] == "disable") {
        $m = MessageTbl::getMessageTblBy($sqlF, "ID_MESSAGE", $_GET["ID"]);
        $m->DISPLAY_ = $m->DISPLAY_ == 0 ? 1 : 0;
        MessageTbl::update($sqlF, $m);
        header("Location:ADMIN_message.php");
    } elseif ($_GET["act"] == "delete") {
        $m = MessageTbl::getMessageTblBy($sqlF, "ID_MESSAGE", $_GET["ID"]);
        //   $m->DISPLAY_=$m->DISPLAY_==0?1:0;
        MessageTbl::del($sqlF, $m);
        header("Location:ADMIN_message.php");
    } elseif ($_GET["act"] == "displayReponse") {
        $r = ReponseTbl::findReponse($sqlF, $_GET["user"], $_GET["ID"], "'" . $_GET["DATE"] . "'");
        $r->DISPLAY = ((int)$r->DISPLAY) ? 0 : 1;
        ReponseTbl::update($sqlF, $r);
        header("Location:ADMIN_message.php?ID=" . $_GET["ID"]);
    }
}
if ($user->TYPE_UTILISATEUR == 2) {
    $orga = $user->getOrganisation($sqlF);
    $a = $orga->getMessages($sqlF);
    // $a = MessageTbl::getAll($sqlF);  
} else
    $a = MessageTbl::getAll($sqlF);
$display = array_reduce($a, function ($carry, $item) use (&$i, $sqlF) {
    $carry[$i] = $item->getArray();
    //  easyFrameWork::Debug($item->DISPLAY_);
    $carry[$i]["PSEUDO_UTILISATEUR"] = $item->getUser($sqlF)->PSEUDO_USER;
    $carry[$i]["LIB_SUJET"] = $item->getTheme($sqlF)->LIB_SUJET;
    $carry[$i]["DISPLAY"] = ($item->DISPLAY_ == "1") ? "OUI" : "NON";
    preg_match("/([0-9]{4}-[0-9]{2}-[0-9]{2})/i", $item->DATE_MESSAGE, $matches);
    $carry[$i]["SHORT_DATE"] = $matches[1];
    $carry[$i]["REPONSE"] = count($item->getReponses($sqlF));
    $i++;
    return $carry;
}, []);
//var_dump($display);
$template->loop("message", $display);
$template->addScript("window.addEventListener('load', () => {
    document.querySelector('[title=supprimer]').onclick=function(){
       return confirm('Voulez-vous supprimer ce message ?');
    }});");
