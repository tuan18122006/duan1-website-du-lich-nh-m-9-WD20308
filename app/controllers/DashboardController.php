<?php

class DashboardController extends Controller
{
    public function index()
    {
        echo "Dashboard hoạt động ✔ – MVC OK!";
    }
    
    //hiển thị tour category 
    public function showDashboardCategory()
    {

        $view_path = "./app/views/dashboard/dashboad.php";

        require_once "./app/views/layouts/main.php";
    }
}
