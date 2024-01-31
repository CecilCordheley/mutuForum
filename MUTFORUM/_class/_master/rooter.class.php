<?php
class Router
{
    private static $path;
    private static $pageName;
    private static $ROUTER_INFO;
    public static function INIT($path = "include/router.json")
    {
        $page = explode("/", $_SERVER['PHP_SELF']);
        self::$path = $path;
        self::$pageName = $page[count($page) - 1];
       // echo self::$pageName;
  //      echo $page[count($page) - 1];
        self::$ROUTER_INFO = json_decode(file_get_contents($path), true);
        if (isset(self::$ROUTER_INFO[self::$pageName]["parent"])) {
            if (self::$ROUTER_INFO[self::$pageName]["GET"]) {
                $GET=self::$ROUTER_INFO[self::$pageName]["GET"][0];
                    foreach($GET as $key=>$value)
                        Request::set($key, $value);
                
            }else{

            }
            self::$pageName = self::$ROUTER_INFO[self::$pageName]["parent"];
        }
        //   var_dump(self::$pageName);
    }
    public static function addRouterInfo($name, $infos)
    {
        self::$ROUTER_INFO[$name] = $infos;
        //var_dump(self::$ROUTER_INFO);
        $a = json_encode(self::$ROUTER_INFO, true);
        file_put_contents(self::$path, $a);
        self::createCtrl_file($infos["ctrl"]);
        self::createTplt_file($infos["template"]);
        self::createCSS_file($infos["style"]);
    }
    private static function createCSS_file($a)
    {
        array_walk($a, function ($item) {
            if (!file_exists("../_css/$item"))
                file_put_contents("../_css/$item", "/**Ici le contenu CSS de la page");
        });
    }
    private static function createCtrl_file($filename)
    {
        if (!file_exists("../_ctrl/$filename"))
            file_put_contents("../_ctrl/$filename", "<?php \n//ici le code PHP de la page\n#\$template pour gérer le moteur\n#\$vars pour gérer les variables {var:???}\n");
    }
    private static function createTplt_file($filename)
    {
        if (!file_exists("../_template/$filename"))
            file_put_contents("../_template/$filename", "<!--ICI LE TEMPLATE SPECIFIQUE DE VOTRE PAGE-->");
    }
    public static function getCtrl()
    {
        return "_ctrl/" . self::$ROUTER_INFO[self::$pageName]["ctrl"] ?? false;
    }
    public static function getView(): string
    {
        return self::$ROUTER_INFO[self::$pageName]["view"] ?? false;
    }
    private static function getTemplate()
    {
        //  var_dump(self::$ROUTER_INFO[self::$pageName]["template"]);
        return self::$ROUTER_INFO[self::$pageName]["template"] ?? false;
    }
    public static function setMainTemplate(EasyTemplate &$tpl, $name)
    {
        // var_dump();
        $tpl->callTemplate($name, Router::getTemplate());
    }
    /**
     * @param EasyTemplate $tpl
     */
    public static function LoadStyles(&$tpl)
    {
        $a = self::$ROUTER_INFO[self::$pageName]["style"] ?? "no";
        if ($a == "no")
            return;
        array_walk($a, function ($item) use (&$tpl) {
            $tpl->loadScript($item);
        });
    }
}
