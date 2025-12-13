<?php

class WelcomeController extends Controller {

    public function index() {
        // Gọi trang welcome
        require_once 'app/views/welcome/welcome.php';
    }
}
