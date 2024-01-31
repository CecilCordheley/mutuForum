<?php
interface GlobalManager
{
    public static function getAll();
    public static function get($key);
}
class Cookie implements GlobalManager
{
    public static function getAll()
    {
        return $_COOKIE;
    }
    public static function get($key)
    {
        return $_COOKIE[$key] ?? false;
    }
    public static function set(
        string $name,
        string $value = "",
        int $expires_or_options = 0,
        string $path = "",
        string $domain = "",
        bool $secure = false,
        bool $httponly = false
    ) {
        return setcookie(
            $name,
            $value,
            $expires_or_options,
            $path,
            $domain,
            $secure,
            $httponly
        );
    }
}
class Server implements GlobalManager
{
    public static function getAll()
    {
        return $_SERVER;
    }
    public static function get($key)
    {
        return $_SERVER[$key] ?? null;
    }
    public static function GetSelF()
    {
        return self::get("PHP_SELF");
    }
    public static function GetScriptName()
    {
        return self::get("SCRIPT_NAME");
    }
    public static function GetScriptFilename()
    {
        return self::get("SCRIPT_FILENAME");
    }
    public static function GetPath()
    {
        return self::get("PATH_TRANSLATED");
    }
    public static function GetRoot()
    {
        return self::get("DOCUMENT_ROOT");
    }
    public static function GetRequestFloatTime()
    {
        return self::get("REQUEST_TIME_FLOAT");
    }
    public static function GetRacine()
    {
        return explode('/', self::GetScriptName())[1];
    }
    public static function GetRequestTime()
    {
        return self::get("REQUEST_TIME");
    }
}
/**
 * Regroupe les traitement des variables GET
 */
class Request implements GlobalManager
{
    public static function get($key)
    {
        return $_GET[$key] ?? null;
    }
    public static function getAll()
    {
        return $_GET ?? null;
    }
    public static function set($key, $val)
    {
        $_GET[$key] = $val;
    }
    /**
     * @return bool
     */
    public static function exist($key)
    {
        return isset($_GET[$key]);
    }
    public function clearRequest()
    {
        unset($_GET);
    }
}
class Query implements GlobalManager
{
    public static function getAll()
    {
        return [
            "date" => date("Y-m-d"),
            "values" => $_POST
        ];
    }
    public static function hasPostValues()
    {
        return count($_POST) ?? false;
    }
    public static function get($key)
    {
        $type = ["string", "array"];
        if (in_array(gettype($key), $type)) {
            if (gettype($key) == "string")
                return $_POST[$key] ?? null;
            else if (gettype($key) == "array") {
                return $_POST[$key[0]][$key[1]];
            }
        } else
            throw new Exception("\$key not a valid type should be array or string type " . gettype($key) . " given");
    }
}
class sessionVar implements GlobalManager
{
    public static function getAll()
    {
        return $_SESSION ?? null;
    }
    public static function get($key)
    {
        if (gettype($key) != "array") {
            throw new Exception("\$key must be an associate array with 'context' key and 'name' key");
        }
        $context = $key["context"];
        $_key = $key["name"];
        return $_SESSION[$context][$_key] ?? false;
    }
    public static function unset($key){
        if (gettype($key) != "array") {
            throw new Exception("\$key must be an associate array with 'context' key and 'name' key");
        }
        $context = $key["context"];
        $_key = $key["name"];
        unset($_SESSION[$context][$_key]);
        
    }
    public static function setPublic($key, $value)
    {
        if (!isset($_SESSION["public"])) {
            $_SESSION['public'] = [];
        }
        $_SESSION['public'][$key] = $value;
    }
    public static function getPrivate($key)
    {
        if (isset($_SESSION["private"])) {
            
            if (isset($_SESSION["private"][$key])) {
                return easyFrameWork::decrypt($_SESSION['private'][$key], session_id());
            } else
                return null;
        } else
            return null;
    }
    public static function setPrivate($key, $value)
    {
        if (!isset($_SESSION["private"])) {
            $_SESSION['private'] = [];
        }
        $_SESSION['private'][$key] = easyFrameWork::encrypt($value, session_id());
    }
}
