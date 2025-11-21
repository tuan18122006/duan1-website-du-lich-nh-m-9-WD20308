<?php
class HomeController {
    public function index() {
        require_once "home.php";
    }
        function error404()
    {
        echo "404 FILE NOT FOUND";
    }

}
