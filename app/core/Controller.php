<?php

class Controller 
{
    protected function view($view, $data = [])
    {
        extract($data);
        require_once "app/views/$view.php";
    }

    public function model($model) {
        // Vì index.php đang ở gốc, nên đường dẫn vào app là trực tiếp
        require_once "app/models/" . $model . ".php"; 
        return new $model;
    }
}
