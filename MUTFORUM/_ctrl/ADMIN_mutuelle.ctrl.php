<?php
//ici le code PHP de la page
#$template pour gérer le moteur
#$vars pour gérer les variables {var:???}
Main::setConnexion($vars, $template);
SqlEntities::LoadEntity("organisme_tbl");
SqlEntities::LoadEntity("utilisateur_tbl");
$displayUser = [["ID_USER" => "-", "MAIL_USER" => "-","TYPE_USER"=>"-", "PSEUDO_USER" => "-"]];
if (isset($_GET["act"])) {
    switch ($_GET["act"]) {
        case "seeUSers": {
                $users = UtilisateurTbl::getUtilisateurTblBy($sqlF, "ID_ORGA", $_GET["ID"]);
                $vars->NOM_ORGANISATION = OrganismeTbl::getOrganismeTblBy($sqlF, "ID_ORGA", $_GET["ID"])->NOM_ORGANISATION;
                if (gettype($users) != "array")
                    $users = [$users];

                break;
            }
    }
}
if (isset($users)) {
    SqlEntities::LoadEntity("type_utilisateur");
    $i = 0;
    $displayUser = array_reduce($users, function ($carry, $el) use (&$i,$sqlF) {
        $carry[$i] = $el->getArray();
        $carry[$i]["TYPE_USER"]=TypeUtilisateur::getTypeUtilisateurBy($sqlF,"idType_Utilisateur",$el->TYPE_UTILISATEUR)->LIBELLE_Type_Utilisateur;
        $i++;
        return $carry;
    }, []);
}
//  easyFrameWork::Debug($display);
$template->loop("user", $displayUser);
$orgas = OrganismeTbl::getAll($sqlF);
$i = 0;
$display = array_reduce($orgas, function ($carry, $el) use (&$i, $sqlF) {
    $carry[$i] = $el->getArray();
    $users = (UtilisateurTbl::getUtilisateurTblBy($sqlF, "ID_ORGA", $el->ID_ORGA));
    $carry[$i]["NB_USERS"] = (gettype($users) == "array") ? count($users) : 1;
    $i++;
    return $carry;
}, []);
$template->loop("orga", $display);
