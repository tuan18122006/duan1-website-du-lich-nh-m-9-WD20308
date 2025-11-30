<?php
    class BookingController extends Controller{

        public $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
    }
    
    public function sellTour(){

        $view_path = './app/views/bookings/sell_tour.php';
        $page_css = "./assets/css/tour.css";
        require_once './app/views/layouts/main.php';
    }
}