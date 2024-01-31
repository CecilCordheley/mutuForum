<?php

interface ISubject{
    public function attach($obs);
    public function detach($obs);
    public function notifyObs();
}

interface IObserver{
    public function update($subject);
}
?>