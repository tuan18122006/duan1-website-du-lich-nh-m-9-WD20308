<?php
class HomeController {
    public function index() {
        include_once "./view/admin/home.php";
    }
        function error404()
    {
        echo "404 FILE NOT FOUND";
    }

}
