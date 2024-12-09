<?php

class Controller
{
    protected $base_url;
    protected $functions;

    public function __construct()
    {
        $this->base_url = $GLOBALS['base_url'];
        $this->functions = new Functions();
    }
    public function model($model)
    {
        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }
}
