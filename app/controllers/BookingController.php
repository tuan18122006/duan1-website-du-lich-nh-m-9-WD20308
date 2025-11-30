<?php
class BookingController extends Controller {
    private $bookingModel;
    private $tourModel;
    private $guideModel;

    public function __construct() {
        $this->bookingModel = $this->model('BookingModel');
        $this->tourModel = $this->model('TourModel');
        $this->guideModel = $this->model('GuideModel'); // Nhớ tạo GuideModel nếu chưa có
    }

    public function index() {
        $bookings = $this->bookingModel->getAllBookings();
        $view_path = './app/views/bookings/list.php';
        require_once './app/views/layouts/main.php';
    }

    // --- CHỨC NĂNG 1: TẠO BOOKING ---
        public function add() {
        $tours = $this->tourModel->getAllTour();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = $_POST['tour_id'];
            $people_count = (int)$_POST['people'];
            
            // 1. Kiểm tra Tour còn chỗ không
            $tour = $this->tourModel->getTourById($tour_id);
            $current_booked = $this->bookingModel->getBookedSeats($tour_id);
            $remaining = $tour['people'] - $current_booked;

            if ($people_count > $remaining) {
                $_SESSION['error'] = "Tour này chỉ còn $remaining chỗ!";
            } else {
                $total_price = $tour['base_price'] * $people_count;

                // 2. Chuẩn bị dữ liệu (Lấy từ input khách nhập)
                $data = [
                    ':tour_id' => $tour_id,
                    ':customer_name' => $_POST['customer_name'],       // Mới
                    ':customer_phone' => $_POST['customer_phone'],     // Mới
                    ':customer_email' => $_POST['customer_email'] ?? '', // Mới
                    ':customer_address' => $_POST['customer_address'] ?? '', // Mới
                    ':people' => $people_count,
                    ':total_price' => $total_price,
                    ':start_date' => $tour['start_date'],
                    ':note' => $_POST['note'] ?? ''
                ];

                if ($this->bookingModel->createBooking($data)) {
                    if ($people_count == $remaining) {
                        $this->tourModel->updateTourStatus($tour_id, 0); 
                    }
                    $_SESSION['success'] = "Tạo booking thành công!";
                    header('Location: index.php?act=booking_list');
                    exit;
                } else {
                     $_SESSION['error'] = "Lỗi khi lưu vào database.";
                }
            }
        }
        
        $view_path = './app/views/bookings/add.php';
        require_once './app/views/layouts/main.php';
    }

    // --- CHỨC NĂNG 2: XỬ LÝ BOOKING (Xác nhận, Gán HDV, Hoàn thành) ---
    public function detail() {
        $id = $_GET['id'];
        $booking = $this->bookingModel->getBookingById($id);
        
        // Lấy danh sách HDV để hiển thị trong dropdown
        $guides = $this->guideModel->getAllGuides(); 

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action']; // Xác định hành động: confirm, assign_guide, complete

            // 1. Xác nhận đơn & Thanh toán
            if ($action == 'confirm') {
                $this->bookingModel->updateBookingStatus($id, 'Đã xác nhận');
                $_SESSION['success'] = "Đã xác nhận đơn hàng!";
            }
            
            // 2. Gán HDV cho Tour (Admin chọn)
            elseif ($action == 'assign_guide') {
                $guide_id = $_POST['guide_id'];
                // Cập nhật bảng tours (như yêu cầu của bạn)
                $this->bookingModel->updateTourGuide($booking['tour_id'], $guide_id);
                $_SESSION['success'] = "Đã phân công hướng dẫn viên cho Tour!";
            }
            
            // 3. Hoàn thành tour & Đánh giá
            elseif ($action == 'complete') {
                // Đổi trạng thái booking
                $this->bookingModel->updateBookingStatus($id, 'Hoàn thành');
                
                // Lưu đánh giá nếu có
                if (!empty($_POST['feedback_content'])) {
                    $feedbackData = [
                        ':booking_id' => $id,
                        ':guide_id' => $booking['tour_guide_id'], // Lấy HDV của tour đó
                        ':content' => $_POST['feedback_content']
                    ];
                    $this->bookingModel->saveFeedback($feedbackData);
                }
                $_SESSION['success'] = "Tour đã hoàn thành và lưu đánh giá!";
            }
            
            // 4. Hủy tour
            elseif ($action == 'cancel') {
                 $this->bookingModel->updateBookingStatus($id, 'Đã hủy');
                 // Mở lại trạng thái Tour (Còn chỗ) nếu trước đó bị đóng
                 $this->tourModel->updateTourStatus($booking['tour_id'], 1);
                 $_SESSION['success'] = "Đã hủy đơn đặt tour.";
            }

            header("Location: index.php?act=booking_detail&id=$id");
            exit;
        }

        $view_path = './app/views/bookings/detail.php';
        require_once './app/views/layouts/main.php';
    }
}