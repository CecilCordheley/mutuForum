<?php

/**
 * Moteur de template du frameWork
 *
 * @author Cecil Cordheley
 */
class EasyTemplate
{

    /**
     * Configuration
     * @var array 
     */
    private $config;

    /**
     * contenu courant
     * @var string 
     */
    private $content;

    /**
     * Dictionnaire 
     * @var EasyTemplate_variable 
     */
    private $dictionnary;

    /**
     * Instancie un nouveau template
     * @param string $configName Nom de la configuration (par défault :"config"
     */
    public function __construct($configName = "config")
    {
        $ini = parse_ini_file("include/config.ini", true);
        $this->config = $ini[$configName];
        $this->load();
    }

    /**
     * Permet de charger la configuration du template 
     */
    private function load()
    {
        $this->content = file_get_contents($this->config['templateDirectory'] . "/" . $this->config['masterPage']);
    }

    /**
     * permet de charger plusieurs bibliothèque JS ou CSS
     * @param array $array esembles des librairies
     */
    public function libraries($array)
    {
        foreach ($array as $lib) {
            $this->loadScript($lib);
        }
    }
    /**
     * Charge un dictionnaire 
     * @param EasyTemplate_variable $dictionnary 
     */
    public function loadDictionnary($dictionnary)
    {
        $this->dictionnary = $dictionnary;
    }

    /**
     * Retourne le tableau des variable présentes sur le template
     * 
     */
    public function getVariableArray(): array
    {
        $array = array();

        if (preg_match_all("/\\{var:(.*?)\\}/is", $this->content, $matches)) {
            $i = 0;
            array_walk($matches[1], function ($m) use (&$i, &$array) {
                $tabName = explode(".", $m);

                if (count($tabName) > 1) {
                    $array['array'][$i]['key'] = $tabName[0] . "." . $tabName[1];
                    $v = $this->dictionnary->__get($tabName[0]);
                    if (isset($v[$tabName[1]]))
                        $array['array'][$i]['value'] = htmlentities($v[$tabName[1]]);
                    else
                        $array['array'][$i]['value'] = "";
                } else {
                    $array['variable'][$i]['key'] = $m;
                    if (isset($this->dictionnary))
                        $array['variable'][$i]['value'] = htmlspecialchars($this->dictionnary->__get($m) ?? '');
                }
                $i++;
            });
        }
        $pattern = "\\{LOOP:(.*?)\\}.*?\\{\\/LOOP\\}";
        if (preg_match_all("/$pattern/is", $this->content, $matches)) {
            $i = 0;
            foreach ($matches[1] as $m) {
                $array['loop'][$i]['key'] = $m;
                if (isset($this->dictionnary))
                    $array['loop'][$i]['value'] = $this->dictionnary->__get($m);
                $i++;
            }
        }
        return $array;
    }

    /**
     * Remplace une variable du template par sa valeur
     * @param string $key nom de la variable à remplacer
     * @param string $value valeur assignée
     */
    private function replace($key, $value)
    {
        //    var_dump($value);
        $this->content = str_replace("{var:$key}", $value, $this->content);
    }

    public function Variable($key, $value)
    {

        $this->content = str_replace("{var:$key}", $value, $this->content);
    }

    /**
     * permet de charger une implémentation JS
     * @param string $implement code à charger
     */
    public function addScript($implement)
    {
        $html = "<script type='text/javascript'>$implement</script>";
        $this->content = str_replace("</head>", "$html\n\t</head>", $this->content);
    }

    /**
     * permet de remplacer une variable du template par le contenu d'un fichier *.tpl
     * @param string $key nom de la variable
     * @param string $templateName nom du fichier *.tpl à charger
     */
    public function callTemplate($key, $templateName)
    {
        $c = file_get_contents($this->config['templateDirectory'] . "/$templateName");
        $this->content = str_replace("{var:$key}", $c, $this->content);
    }

    /**
     * permet de charger un script *.js ou *.css sur le template
     * @param string $script nom du fichier *.js ou *.css
     * @throws Exception 
     */
    public function loadScript($script, $properties = null)
    {
        $mime = explode(".", $script);
        $_m = $mime[count($mime) - 1];
        $html = "";
        $pattern = "https\:\/\/";
        switch ($_m) {
            case "js": {
                    if (file_exists($this->config['JSDirectory'] . "/$script"))
                        $html = "<script type=\"text/javascript\" src=\"{:racine}" . $this->config['JSDirectory'] . "/$script\"></script>";
                    elseif (preg_match("/$pattern/is", $script)) {
                        $html = "<script type=\"text/javascript\" src=\"$script\"></script>";
                    } else
                        throw new Exception("$script n'existe pas dans le context actuel");
                    break;
                }
            case "css": {

                    if (file_exists($this->config['StyleDirectory'] . "/$script")) {

                        $html = "<link rel=\"stylesheet\" type=\"text/css\" href=\"{:racine}" . $this->config['StyleDirectory'] . "/$script\"";
                        if (isset($properties)) {
                            if (isset($properties["media"])) {
                                $html .= " media=\"" . $properties["media"] . "\"";
                            }
                        }
                        $html .= ">";
                    } elseif (preg_match("/$pattern/is", $script)) {
                        $html = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$script\"";
                        if (isset($properties)) {
                            if (isset($properties["media"])) {
                                $html .= " media=\"" . $properties["media"] . "\"";
                            }
                        }
                        $html .= ">";
                    } else
                        throw new Exception("$script n'existe pas dans le context actuel");
                    break;
                }
            default:
                echo "pop";
        }
        $this->content = str_replace("</head>", "$html\n</head>", $this->content);
    }

    /**
     * Affiche la page et effectue les remplacement nécéssaires
     * @throws Exception 
     */
    private function _replace($item)
    {
        $this->replace($item["key"], html_entity_decode($item['value']));
        unset($item);
    }
    /**
     * @param $key string
     * @param $sqlView SQLtoView
     * @param $p array
     */
    public function _view($key, $sqlView, $p)
    {
        $pattern = "{view:$key}";
        $replace = $sqlView->generate($p);
        $this->content = str_replace($pattern, $replace, $this->content);
        return $this;
    }
    /**
     * @param string $key
     * @param string $content
     */
    public function replaceView($key, $content)
    {
        $pattern = "{view:$key}";
        $this->content = str_replace($pattern, $content, $this->content);
        return $this;
    }
    private function matchReplace($m, $key, $type)
    {
        switch ($type) {
            case "SESSION": {
                    $context = $m[0];
                    $name = $m[1];
                    $this->content = str_replace("{:SESSION context=\"$context\" name=\"$name\"}", (isset($_SESSION[$context][$name])) ? $_SESSION[$context][$name] : "", $this->content);
                    break;
                }
            case "GET":
                $this->content = str_replace("{:GET name=\"$m\"}", (isset($_GET[$m])) ? $_GET[$m] : "", $this->content);
                break;
        }
    }
    private function replaceAsync(){
        $pattern = "\\{async file=\"(.*?)\" fnc=\"(.*?)\" args=\[(.*?)\]\\}";
        $subst = "$1?fnc=$2&args=$3";
       $this->content= preg_replace("/$pattern/is",$subst, $this->content);
    }
    public function display()
    {
        if ($this->dictionnary) {
            $array = $this->getVariableArray();
            while (isset($array["variable"])) {
                if (isset($array["variable"])) {
                    array_walk($array["variable"], EasyTemplate::class . '::_replace');
                    unset($array["variable"]);
                }
                if (isset($array['array'])) {
                    array_walk($array["array"], EasyTemplate::class . '::_replace');
                    unset($array["array"]);
                }
                if (isset($array["loop"])) {
                    array_walk($array["loop"], EasyTemplate::class . '::_replace');
                    unset($array["loop"]);
                }
                if (isset($array["view"])) {
                    array_walk($array["view"], EasyTemplate::class . '::_view');
                    unset($array["view"]);
                }

                $array = $this->getVariableArray();
                $this->replaceCondition();
                $this->replaceGetVariable();
                $this->replaceUNICODE();
                $this->replaceAsync();
                if (isset($_SESSION))
                    $this->replaceSessionVariable();
            }
            $this->replaceCondition();
            $this->replaceGetVariable();
            $this->replaceUNICODE();
            if (isset($_SESSION))
                $this->replaceSessionVariable();
            // var_dump(isset($array["variable"]));

            //  var_dump($this->config);
            $this->content = str_replace("{:racine}", $this->config['racineProject'] . "/", $this->content);
            $this->content = str_replace("{:url}", $_SERVER['PHP_SELF'] . "/", $this->content);
            $this->content = str_replace("{:image}", $this->config["imageDirectory"], $this->content);
            //  $this->content=str_replace("{:now}",date("Y-m-d"),$this->content);
            $this->content = preg_replace("/\{:SESSION context=\"(\w+)\" name=\"(\w+)\"}/is", "", $this->content);
            echo $this->content;
        } else
            throw new Exception("Vous n'avez pas charg&eacute; de dictionnaire de variables");
    }
    private function replaceCondition()
    {
        $pattern = "\\{\\:IF (.*?)(=|!|>|<)(.*?)\\}(.*?)\\{\\:\\/IF\\}";
        if (preg_match_all("/$pattern/is", $this->content, $matches)) {
            //var_dump($matches);
            for ($i = 0; $i < count($matches[0]); $i++) {
                $replace = "";
                //   var_dump($matches);
                switch ($matches[2][$i]) {
                    case "=":
                        $replace = ($matches[1][$i] == $matches[3][$i]) ? $matches[4][$i] : "";
                        break;
                    case ">":
                        $replace = ($matches[1][$i] > $matches[3][$i]) ? $matches[4][$i] : "";
                        break;
                    case "<":
                        $replace = ($matches[1][$i] < $matches[3][$i]) ? $matches[4][$i] : "";
                        break;
                    case "!": {

                            $replace = ($matches[1][$i] !== $matches[3][$i]) ? $matches[4][$i] : "";
                            break;
                        }
                }
                $this->content = str_replace($matches[0][$i], $replace, $this->content);
            }
        }
    }
    private function replaceSessionVariable()
    {
        if (preg_match_all("/\{:SESSION context=\"(\w+)\" name=\"(\w+)\"}/is", $this->content, $matches)) {
            $a = array_map(null, $matches[1], $matches[2]);
            array_walk($a, EasyTemplate::class . '::matchReplace', "SESSION");
        }
    }
    private function replaceUNICODE()
    {
        if (preg_match_all("/\{:UNICODE:(\w+)}/is", $this->content, $matches)) {
            foreach ($matches[1] as $m) {
                switch ($m) {
                    case "sharp":
                        $this->content = str_replace("{:UNICODE:sharp}", "#", $this->content);
                        break;
                    case "pipe":
                        $this->content = str_replace("{:UNICODE:pipe}", "|", $this->content);
                        break;
                }
            }
        }
    }
    private function replaceGetVariable()
    {
        if (preg_match_all("/\{:GET name=\"(\w+)\"}/is", $this->content, $matches)) {
            /*  foreach ($matches[1] as $m) {
                  $this->content = str_replace("{:GET name=\"$m\"}", (isset($_GET[$m])) ? $_GET[$m] : "", $this->content);
              }*/
            array_walk($matches[1], EasyTemplate::class . '::matchReplace', "GET");
        }
    }
    /**
     * permet d'effectuer le remplacement d'une boucle (LOOP)
     * @param string $key nom de la boucle sur le tempalte
     * @param array $array tableau à deux dimension
     * @param bool $UTF8Encode spécifie l'encode UTF8 (oui/non)
     */
    public function loop($key, $array, $UTF8Encode = false)
    {
        $pattern = "\\{LOOP:$key\\}(.*?)\\{\\/LOOP\\}";
        if (preg_match_all("/$pattern/is", $this->content, $matches)) {
            $content = array_reduce($array, function ($html, $lines) use ($matches, $UTF8Encode) {
                $html .= $matches[1][0];
                foreach ($lines as $key => $value) {
                    //       var_dump($key,$value);
                    if (gettype($value) != "array")
                        if ($UTF8Encode)
                            $html = str_replace("{#$key#}", mb_convert_encoding($value, "UTF-8"), $html);
                        else
                            $html = str_replace("{#$key#}", $value, $html);
                }
                return $html;
            }, "");
            $this->content = str_replace($matches[0][0], $content, $this->content);
        }
    }
    public function clearView($key)
    {
        $this->content = str_replace("{view:$key}", "", $this->content);
    }


    /**
     * Efface les variables inutilisées ainsi que les commentaires 
     */
    public function clear()
    {
        $this->content = preg_replace("/\{comment\:(.*?)\}/is", "", $this->content);
        $this->content = preg_replace("/\\{LOOP:.*?\\}(.*?)\\{\\/LOOP\\}/is", "", $this->content);
        $this->content = preg_replace("/\{view:.*?\}/is", "", $this->content);
    }
}
