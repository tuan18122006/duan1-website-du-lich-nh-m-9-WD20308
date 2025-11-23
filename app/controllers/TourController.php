<?php
require_once "./app/models/TourModel.php";

class TourController extends Controller
{

    public $tourModel;

    public function __construct()
    {
        $this->tourModel = new TourModel();
    }

    //hiển thị tour category 
    public function showTourCategory()
    {
        $tour_cate_list = $this->tourModel->getAllTourCategory();

        $view_path = "./app/views/tours/category_list.php";

        require_once "./app/views/layouts/main.php";
    }
}
