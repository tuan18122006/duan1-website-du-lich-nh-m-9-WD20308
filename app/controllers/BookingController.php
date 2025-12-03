<?php
class BookingController extends Controller {
    private $bookingModel;
    private $tourModel;
    private $guideModel;

    public function __construct() {
        $this->bookingModel = $this->model('BookingModel');
        $this->tourModel = $this->model('TourModel');
        $this->guideModel = $this->model('GuideModel'); 
    }

    // Danh sách booking
    public function index() {
        $bookings = $this->bookingModel->getAllBookings();
        $view_path = './app/views/bookings/list.php';
        require_once './app/views/layouts/main.php';
    }

    // Tạo booking mới
    public function add() {
        $tours = $this->tourModel->getAllTour();
        
        // AJAX: Lấy lịch trình
        if (isset($_GET['ajax_tour_id'])) {
            $schedules = $this->tourModel->getSchedules($_GET['ajax_tour_id']);
            echo json_encode($schedules);
            exit; 
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = $_POST['tour_id'];
            $schedule_id = $_POST['schedule_id'] ?? 0;
            $people_count = (int)$_POST['people'];
            
            $schedule = $this->tourModel->getScheduleById($schedule_id);
            
            if (!$schedule) {
                 $_SESSION['error'] = "Vui lòng chọn ngày khởi hành!";
            } else {
                $remaining = $schedule['stock'] - $schedule['booked'];

                if ($people_count > $remaining) {
                    $_SESSION['error'] = "Ngày này chỉ còn $remaining chỗ!";
                } else {
                    $total_price = $schedule['price'] * $people_count;

                    $data = [
                        ':tour_id' => $tour_id,
                        ':customer_name' => $_POST['customer_name'],
                        ':customer_phone' => $_POST['customer_phone'],
                        ':customer_email' => $_POST['customer_email'] ?? '',
                        ':customer_address' => $_POST['customer_address'] ?? '',
                        ':people' => $people_count,
                        ':total_price' => $total_price,
                        ':start_date' => $schedule['start_date'],
                        ':note' => $_POST['note'] ?? ''
                    ];

                    if ($this->bookingModel->createBooking($data)) {
                        // Cập nhật số lượng đã đặt
                        $this->tourModel->updateScheduleBooked($schedule_id, $people_count);
                        
                        $_SESSION['success'] = "Tạo booking thành công!";
                        header('Location: index.php?act=booking_list');
                        exit;
                    } 
                }
            }
        }
        
        $view_path = './app/views/bookings/add.php';
        require_once './app/views/layouts/main.php';
    }

    // Chi tiết Booking & Xử lý trạng thái
    public function detail() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->getBookingById($id);

        if (!$booking) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng!";
            header("Location: index.php?act=booking_list");
            exit;
        }

        // XỬ LÝ POST (Xác nhận, Hủy, Hoàn thành)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';

            // 1. Xác nhận Booking (Giữ chỗ)
            if ($action == 'confirm') {
                $this->bookingModel->updateBookingStatus($id, 'Đã xác nhận');
                $_SESSION['success'] = "Đã xác nhận đơn hàng!";
            }
            
            // 2. Xác nhận Giao dịch xong -> Hoàn thành
            elseif ($action == 'mark_completed') {
                $this->bookingModel->updateBookingStatus($id, 'Hoàn thành');
                $_SESSION['success'] = "Giao dịch thành công! Đơn hàng đã hoàn thành.";
            }

            // 3. Hủy đơn
            elseif ($action == 'cancel') {
                 $this->bookingModel->updateBookingStatus($id, 'Đã hủy');
                 // (Tùy chọn: Nếu muốn cộng lại số chỗ trống cho lịch tour thì xử lý thêm ở đây)
                 $_SESSION['success'] = "Đã hủy đơn hàng.";
            }

            // Load lại trang để thấy thay đổi
            header("Location: index.php?act=booking_detail&id=$id");
            exit;
        }

        $view_path = './app/views/bookings/detail.php';
        require_once './app/views/layouts/main.php';
    }
}