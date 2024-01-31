<?php
abstract class SqlEntities
{
    public static $DIRECTORY = "./SQLEntities";
    private static function replaceCallBack($table, $pattern)
    {
        return array_reduce($table, function ($carry, $field) use ($pattern) {
            $str = $pattern;
            $str = str_replace("%field%", $field["NAME"], $str);
            $carry[] = $str;
            return $carry;
        }, []);
    }
    /**
     * Converti une date Format long (Y-m-d h:i:s) en Date Format cours (Y-m-d)
     */
    public static function toShortDate($date){
        preg_match("/([0-9]{4}-[0-9]{2}-[0-9]{2})/i", $date, $matches);
        return $matches[1];
    }
    public static function getArrayEntities($array,$callBack=null)
    {
        if (!empty($array)) {
            return array_reduce($array, function ($carry, $el) use($callBack) {
                if($callBack!=null){
                    $item=$el;
                    $carry=call_user_func_array($callBack,array(&$carry,$item));
                }else
                    $carry[] = $el->getArray();
                return $carry;
            }, []);
        }else{
            return [];
        }
    }
    /**
     * @param SQLFactoryV2 $sqF
     */
    public static function generateEntity(SQLFactoryV2 $sqlF, $table)
    {
        $content = file_get_contents(self::$DIRECTORY . "/EntityModel");
        $className = easyFrameWork::toCamelCase($table);
        $columns = $sqlF->getColumns($table);
        $pattern = "\"%field%\"=>''";
        $attrs = self::replaceCallBack($columns, $pattern);

        $pattern = "\$entity->%field%=\$element[\"%field%\"];";
        $affect = self::replaceCallBack($columns, $pattern);

        $content = str_replace("[%className%]", $className, $content);
        $content = str_replace("[%table%]", $table, $content);
        $content = str_replace("[%attr%]", implode(",", $attrs), $content);
        $content = str_replace("[%affect%]", implode("\n", $affect), $content);

        if (file_put_contents(self::$DIRECTORY . "/$className.class.php", $content)) {
            echo ">Class $table [$className] - genérée";
        }
    }
    public static function LoadEntity($table)
    {
        $filename = easyFrameWork::toCamelCase($table);
        require_once self::$DIRECTORY . "/$filename.class.php";
    }
}
