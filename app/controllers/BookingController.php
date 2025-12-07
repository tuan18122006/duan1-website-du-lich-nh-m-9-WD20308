<?php
class BookingController extends Controller {
    // ... (__construct, index, customList giữ nguyên) ...
    private $bookingModel;
    private $tourModel;
    private $guideModel;

    public function __construct() {
        $this->bookingModel = $this->model('BookingModel');
        $this->tourModel = $this->model('TourModel');
        $this->guideModel = $this->model('GuideModel'); 
    }

    public function customList() {
        $bookings = $this->bookingModel->getBookingsByType(1);
        $view_path = './app/views/bookings/custom_booking_list.php';
        require_once './app/views/layouts/main.php';
    }

    public function index() {
        $bookings = $this->bookingModel->getBookingsByType(0);
        $view_path = './app/views/bookings/list.php';
        require_once './app/views/layouts/main.php';
    }

// 1. Booking Tour Thường (Đã cập nhật tính năng lưu danh sách hành khách)
// Trong app/controllers/BookingController.php

    public function add() {
        $tours = $this->tourModel->getAllTour();
        
        // AJAX lấy lịch trình
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
                        ':schedule_id' => $schedule_id,
                        ':customer_name' => $_POST['customer_name'],
                        ':customer_phone' => $_POST['customer_phone'],
                        ':customer_email' => $_POST['customer_email'] ?? '',
                        ':customer_address' => $_POST['customer_address'] ?? '',
                        ':people' => $people_count,
                        ':total_price' => $total_price,
                        ':start_date' => $schedule['start_date'],
                        ':note' => $_POST['note'] ?? ''
                    ];

                    // --- [SỬA ĐOẠN NÀY] ---
                    // Gọi hàm tạo booking, hàm này giờ sẽ trả về ID (số > 0) nếu thành công
                    $new_booking_id = $this->bookingModel->createBooking($data);

                    if ($new_booking_id) { // Nếu có ID trả về (tức là thành công)
                        
                        // Lưu danh sách hành khách đi kèm (nếu có)
                        if (isset($_POST['passengers']) && is_array($_POST['passengers'])) {
                            $this->bookingModel->addPassengers($new_booking_id, $_POST['passengers']);
                        }

                        // Trừ số chỗ
                        $this->tourModel->updateScheduleBooked($schedule_id, $people_count);
                        
                        $_SESSION['success'] = "Đặt tour thành công!";
                        header('Location: index.php?act=booking_list');
                        exit;
                    } else {
                        $_SESSION['error'] = "Lỗi hệ thống, vui lòng thử lại!";
                    }
                }
            }
        }
        
        $view_path = './app/views/bookings/add.php';
        require_once './app/views/layouts/main.php';
    }

    // 2. Booking Tour Custom (Sửa logic lấy schedule_id)
    public function addCustom() {
        $categories = $this->tourModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // ... (Phần xử lý ảnh giữ nguyên) ...
            $image_name = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "assets/uploads/tours/";
                $safeName = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $safeName);
                $image_name = $safeName;
            }

            $tourData = [
                ':tour_name' => "Tour thiết kế: " . $_POST['destination'],
                ':description' => $_POST['description'],
                ':category_id' => $_POST['category_id'],
                ':image_url' => $image_name,
                ':duration_days' => $_POST['duration_days'],
                ':start_date' => $_POST['start_date'],
                ':people' => $_POST['people']
            ];
            
            $newTourId = $this->tourModel->createCustomTour($tourData);

            if ($newTourId) {
                // 1. Tạo Lịch trình và lấy ID
                $scheduleData = [
                    ':tour_id' => $newTourId,
                    ':start_date' => $_POST['start_date'],
                    ':end_date' => date('Y-m-d', strtotime($_POST['start_date'] . ' + ' . $_POST['duration_days'] . ' days')),
                    ':price' => 0,
                    ':stock' => $_POST['people'],
                    ':guide_id' => null
                ];
                $newScheduleId = $this->tourModel->addSchedule($scheduleData);

                // 2. Tạo Booking và LẤY ID BOOKING VỀ (Sửa đoạn này)
                $bookingData = [
                    ':tour_id' => $newTourId,
                    ':schedule_id' => $newScheduleId,
                    ':customer_name' => $_POST['customer_name'],
                    ':customer_phone' => $_POST['customer_phone'],
                    ':customer_email' => $_POST['customer_email'],
                    ':customer_address' => $_POST['customer_address'],
                    ':people' => $_POST['people'],
                    ':total_price' => 0,
                    ':start_date' => $_POST['start_date'],
                    ':note' => "Yêu cầu đặc biệt: " . $_POST['description']
                ];
                
                // Lưu booking và lấy ID
                $newBookingId = $this->bookingModel->createBooking($bookingData);

                // 3. [QUAN TRỌNG] LƯU DANH SÁCH KHÁCH (Đây là đoạn bạn đang thiếu)
                if ($newBookingId && isset($_POST['passengers'])) {
                    $this->bookingModel->addPassengers($newBookingId, $_POST['passengers']);
                }
                
                $_SESSION['success'] = "Gửi yêu cầu và lưu danh sách khách thành công!";
                header('Location: index.php?act=booking_add_custom'); 
                exit;
            }
        }
        $view_path = './app/views/bookings/add_custom.php';
        require_once './app/views/layouts/main.php';
    }

    // ... (Hàm detail giữ nguyên) ...
    public function detail() {
        $id = $_GET['id'] ?? 0;
        $booking = $this->bookingModel->getBookingById($id);

        if (!$booking) {
            $_SESSION['error'] = "Không tìm thấy đơn hàng!";
            header("Location: index.php?act=booking_list");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action == 'confirm') {
                $this->bookingModel->updateBookingStatus($id, 'Đã xác nhận');
                $_SESSION['success'] = "Đã xác nhận đơn hàng!";
            } elseif ($action == 'mark_completed') {
                $this->bookingModel->updateBookingStatus($id, 'Hoàn thành');
                $_SESSION['success'] = "Giao dịch thành công! Đơn hàng đã hoàn thành.";
            } elseif ($action == 'cancel') {
                 $this->bookingModel->updateBookingStatus($id, 'Đã hủy');
                 $_SESSION['success'] = "Đã hủy đơn hàng.";
            }
            header("Location: index.php?act=booking_detail&id=$id");
            exit;
        }

        $view_path = './app/views/bookings/detail.php';
        require_once './app/views/layouts/main.php';
    }
}