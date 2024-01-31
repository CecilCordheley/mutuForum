<?php
//ici le code PHP de la page
#$template pour gérer le moteur
#$vars pour gérer les variables {var:???}
require_once "./include/param.php";
Main::setConnexion($vars, $template);
SqlEntities::LoadEntity("utilisateur_tbl");

if (Request::exist("act")) {
    switch (Request::get("act")) {
        case "UpdateUser": {
                $user = main::getUSer();
                $post = Query::getAll();
                $data = (json_encode($post["values"]["DATA_USER"]));
                if (!empty($post["values"]["user_mdp"]))
                    $user->MDP_USER = easyFrameWork::hashString($post["values"]["user_mdp"]);
                $user->PSEUDO_USER = $post["values"]["PSEUDO_USER"];
                $user->MAIL_USER = $post["values"]["MAIL_USER"];
                $user->DATA_USER = $data;
                UtilisateurTbl::update($sqlF, $user);
                sessionVar::setPrivate("USER", serialize($user));
                sessionVar::setPublic("alert", "Votre compte a été mis à jour !");
                header("Location:index.php");
                break;
            }
        case "update": {
                $pattern = "\n<div class=\"mb-3 row\">\n" .
                    "\t<label for=\"[#ID#]\" class=\"col-sm-2 col-form-label\">[#label#]</label>\n" .
                    "\t <div class=\"col-sm-10\">\n" .
                    "\t<input class=\"form-control\"  id=\"[#ID#]\" type=\"[#type#]\" name=\"[#name#]\" value='[#value#]' [#disable#]>\n" .
                    "\t</div>" .
                    "</div>";
                if (Main::getUSer()) {
                    $user = Main::getUSer()->getArray();
                    $vars->user=$user;
                    $form = new FormBuilder("account.php?act=UpdateUser", "POST", $pattern);
                    $fields = ($sqlF->getColumns("utilisateur_tbl"));
                    SqlEntities::LoadEntity("organisme_tbl");
                    array_walk($fields, function ($item) use ($form, $user, $sqlF) {

                        $exclude = ["ID_USER", "DATA_USER", "ARRIVE_UTILISATEUR", "VALID_USER", "TYPE_UTILISATEUR"];
                        if (!in_array($item["NAME"], $exclude)) {
                            $type = "text";
                            $disable = "";
                            $value = $user[$item["NAME"]];
                            //   var_dump($user[$item["NAME"]]);
                            if ($item["NAME"] == "ID_ORGA") {
                                $disable = "disabled";
                                $label = "ORGANISME";
                                $value = OrganismeTbl::getOrganismeTblBy($sqlF, "ID_ORGA", $user[$item["NAME"]])->NOM_ORGANISATION;
                            } elseif ($item["NAME"] == "MDP_USER") {
                                
                                $label = "Nouveau Mot de passe";
                                $value = "";
                            } elseif ($item["NAME"] == "AVATAR_USER") {
                                $type = "file";
                                $label = "Changer d'avatar";
                            } else {
                                $label = str_replace("_USER", "", $item["NAME"]);
    
                            }

                            $form->addCompoment([
                                "disabled" => $disable,
                                "ID" => $item["NAME"],
                                "type" => $type,
                                "value" => $value,
                                "name" => $item["NAME"],
                                "label" => $label
                            ]);
                        }
                        if ($item["NAME"] == "DATA_USER") {
                            $data = json_decode(str_replace("\\\"", "\"", $user[$item["NAME"]]));
                            //   easyFrameWork::Debug($data);
                            foreach ($data as $key => $value) {
                                $form->addCompoment([
                                    "disabled" => "",
                                    "ID" => "$key",
                                    "type" => "text",
                                    "value" => $value,
                                    "name" => "DATA_USER[$key]",
                                    "label" => $key
                                ]);
                            }
                        }
                    });
                    $template->callTemplate("content", "updateAccount.tpl");
                    $vars->user_frm = $form->generate(["submit" => "<button type='submit' class='btn btn-primary'>Mettre à jour</button>"]);
                }
                break;
            }
        case "addUser": {
                /*Permet d'ajouter l'utilisateur en base */
                $post = Query::getAll();
                //     var_dump($_FILES);
                $upload = Main::UploadFile($post["values"]["PSEUDO_USER"]);

                $avatar = ($upload["result"]) ? $upload["Filename"] : "default.png";
                $user = [
                    "PSEUDO_USER" => $post["values"]["PSEUDO_USER"],
                    "MAIL_USER" => $post["values"]["MAIL_USER"],
                    "ARRIVE_UTILISATEUR" => $post["date"],
                    "MDP_USER" => (easyFrameWork::hashString($post["values"]["MDP_USER"])),
                    "ID_ORGA" => $post["values"]["ID_ORGA"],
                    "AVATAR_USER" => $avatar,
                    "TYPE_UTILISATEUR" => 1
                ];
                if ($sqlF->addItem($user, "utilisateur_tbl"))
                    $vars->content = "Votre compte a été créé";
                if (!$upload["result"])
                    $vars->content .= "(" . $upload["error"] . ")";
                break;
            }
        case "updatePwd": {
                SQLEntities::LoadEntity("utilisateur_tbl");
                $user = main::getUSer();
                $post = Query::getAll();
                $user->MDP_USER = easyFrameWork::hashString($post["values"]["user_mdp"]);
                $user->VALID_USER = 1;
                UtilisateurTbl::update($sqlF, $user);
                sessionVar::setPublic("alert", "Mot de passe mis à jour !");
                header("Location:index.php");
                break;
            }
        case "updatePassword": {
                $template->callTemplate("content", "UpdatePassword.tpl");

                break;
            }
        case "connexion": {
                /*Permet de verifier la connexion */
                $post = Query::getAll();
                SQLEntities::LoadEntity("utilisateur_tbl");
                $user = UtilisateurTbl::Connexion($sqlF, $post["values"]["user_mail"], easyFrameWork::hashString($post["values"]["user_mdp"]));

                if ($user) {
                    sessionVar::setPrivate("USER", serialize($user));
                    if ($user->TYPE_UTILISATEUR != 1) {

                        if ($user->VALID_USER == 0) {
                            //      easyFrameWork::Debug($user);
                            header("Location:new_Password");
                        } else
                            header("Location:index.php");
                    } else
                        header("Location:index.php");
                } else {
                    $vars->alert = easyFrameWork::getMicroTtpl("alert");
                    $vars->contentAlert = "Mail ou mot de passe erroné !";
                    $template->callTemplate("content", "connexion.tpl");
                }
                break;
            }
        case "login": {
                /*Appel la page de connexion */
                $template->callTemplate("content", "connexion.tpl");
                break;
            }
        case "new": {
                /*Formulaire pour créer un compte utilisateur (type=CLIENT) */
                $pattern = "\n<div class=\"mb-3 row\">\n" .
                    "\t<label for=\"[#ID#]\" class=\"col-sm-2 col-form-label\">[#label#]</label>\n" .
                    "\t <div class=\"col-sm-10\">\n" .
                    "\t<input class=\"form-control\"  id=\"[#ID#]\" type=\"[#type#]\" name=\"[#name#]\">\n" .
                    "\t</div>" .
                    "</div>";
                $form = new FormBuilder("account.php", "POST", $pattern);
                $fields = ($sqlF->getColumns("utilisateur_tbl"));
                //   var_dump($fields);
                $data = getSelectData($sqlF);
                array_walk($fields, function ($item) use ($form, $data) {
                    $exclude = ["ID_USER", "DATA_USER", "ARRIVE_UTILISATEUR", "VALID_USER", "TYPE_UTILISATEUR", "AVATAR_USER"];
                    if (!in_array($item["NAME"], $exclude)) {
                        if ($item["PRIMARY"]) {
                            $type = "select";
                            $form->addCompoment([
                                "ID" => $item["NAME"],
                                "type" => $type,
                                "className" => "form-select",
                                "required" => true,
                                "name" => $item["NAME"],
                                "label" => str_replace("ID_ORGA", "ORGANISME", $item["NAME"]),
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
                    if ($item["NAME"] == "AVATAR_USER") {
                        $form->addCompoment([
                            "ID" => $item["NAME"],
                            "type" => "file",
                            "name" => $item["NAME"],
                            "label" => str_replace("_USER", "", $item["NAME"])
                        ]);
                    }
                });

                $template->callTemplate("content", "newAccount.tpl");
                $vars->user_frm = $form->generate(["submit" => "<button type='submit' class='btn btn-primary'>Enregistrer</button>"]);
                break;
            }
    }
}
function getSelectData(SQLFactoryV2 $sqlF)
{
    $return = [
        [
            "val" => "NULL",
            "label" => "selectionnez une mutuelle"
        ]
    ];
    $data = $sqlF->getTable("organisme_tbl");
    return array_reduce($data, function ($carry, $item) {
        $carry[] = [
            "val" => $item["ID_ORGA"],
            "label" => $item["NOM_ORGANISATION"]
        ];
        return $carry;
    }, $return);
}
