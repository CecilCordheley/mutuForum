<?php
class Cache
{
    private $file;
    /**
     * @type array
     */
    private $arr;

    public function __construct($file)
    {
        $this->file = $file;
        if (file_exists($this->file)) {
            $this->arr = json_decode(file_get_contents($this->file), true);
        } else {
            $this->arr = [];
        }
    }

    public function get($key)
    {
        return $this->arr[$key];
    }
    public function set($key, $value)
    {
        $this->arr[$key] = $value;
        file_put_contents($this->file, json_encode($this->arr));
    }
    public function delete($key)
    {
        unset($this->arr[$key]);
        file_put_contents($this->file, json_encode($this->arr));
    }
    public function clear()
    {
        $this->arr = [];
        file_put_contents($this->file, json_encode($this->arr));
    }
}
