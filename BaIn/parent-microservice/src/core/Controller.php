<?php

class Controller
{
    public function model($model)
    {
        require_once '../src/model/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = [])
    {
        require_once '../src/view/' . $view . '.php';
    }
}