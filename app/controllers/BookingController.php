<?php
class BookingController extends Controller
{
    private $bookingModel;
    private $tourModel;
    private $guideModel;

    public function __construct()
    {
        $this->bookingModel = $this->model('BookingModel');
        $this->tourModel = $this->model('TourModel');
        $this->guideModel = $this->model('GuideModel');
    }

    public function customList() {
        $keyword = $_GET['keyword'] ?? null;
        $date = $_GET['date'] ?? null;
        $bookings = $this->bookingModel->getBookingsByType(1, $keyword, $date);
        $view_path = './app/views/bookings/custom_booking_list.php';
        require_once './app/views/layouts/main.php';
    }

    public function index() {
        $keyword = $_GET['keyword'] ?? null;
        $date = $_GET['date'] ?? null;
        $bookings = $this->bookingModel->getBookingsByType(0, $keyword, $date);
        $view_path = './app/views/bookings/list.php';
        require_once './app/views/layouts/main.php';
    }

    // =========================================================================
    // 1. ĐẶT TOUR CỐ ĐỊNH (FIXED / STANDARD)
    // =========================================================================
public function add()
    {
        // ============================================================
        // 1. XỬ LÝ AJAX (Khi Javascript gọi để lấy lịch trình)
        // ============================================================
        if (isset($_GET['ajax_tour_id'])) {
            $tour_id = $_GET['ajax_tour_id'];
            $schedules = $this->tourModel->getSchedules($tour_id);
            
            // [MỚI] Lọc bỏ các lịch trình đã quá hạn
            $current_time = time(); // Thời gian hiện tại
            $valid_schedules = [];

            foreach ($schedules as $s) {
                // Chỉ lấy lịch có thời gian khởi hành lớn hơn hiện tại
                if (strtotime($s['start_date']) > $current_time) {
                    $valid_schedules[] = $s;
                }
            }
            
            // Trả về JSON danh sách đã lọc
            header('Content-Type: application/json');
            echo json_encode($valid_schedules); // Chỉ trả về lịch tương lai
            exit; 
        }

        // ============================================================
        // 2. XỬ LÝ POST (Khi người dùng bấm nút Xác nhận đặt)
        // ============================================================
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tour_id = $_POST['tour_id'];
            $schedule_id = $_POST['schedule_id'] ?? 0;
            $people_count = (int)$_POST['people'];

            $schedule = $this->tourModel->getScheduleById($schedule_id);
            $current_time = time();

            // Validate dữ liệu
            if (!$schedule) {
                $_SESSION['error'] = "Vui lòng chọn lịch trình!";
            } 
            // [MỚI] Kiểm tra quá hạn (Backend check kĩ lần nữa)
            elseif (strtotime($schedule['start_date']) < $current_time) {
                $_SESSION['error'] = "Lịch trình này đã khởi hành hoặc kết thúc, không thể đặt nữa!";
            }
            elseif (($schedule['stock'] - $schedule['booked']) < $people_count) {
                $_SESSION['error'] = "Số chỗ còn lại không đủ!";
            } 
            else {
                // ... (Phần xử lý lưu booking giữ nguyên như cũ) ...
                // Tính tiền
                $total_price = $schedule['price'] * $people_count;

                $bookingData = [
                    ':tour_id' => $tour_id,
                    ':schedule_id' => $schedule_id,
                    ':customer_name' => $_POST['customer_name'],
                    ':customer_phone' => $_POST['customer_phone'],
                    ':customer_email' => $_POST['customer_email'],
                    ':customer_address' => $_POST['customer_address'],
                    ':people' => $people_count,
                    ':total_price' => $total_price,
                    ':start_date' => $schedule['start_date'],
                    ':note' => $_POST['note'] ?? ''
                ];

                $new_booking_id = $this->bookingModel->createBooking($bookingData);

                if ($new_booking_id) {
                    $passengers = $_POST['passengers'] ?? [];
                    $booker = [
                        'name' => $_POST['customer_name'],
                        'gender' => 'Khác', 
                        'age' => 0,
                        'is_booker' => true
                    ];
                    array_unshift($passengers, $booker);

                    $this->bookingModel->addPassengers($new_booking_id, $schedule_id, $passengers);
                    $this->tourModel->updateScheduleBooked($schedule_id, $people_count);

                    $_SESSION['success'] = "Tạo booking thành công!";
                    header('Location: index.php?act=booking_list');
                    exit;
                }
            }
        }

        // ============================================================
        // 3. HIỂN THỊ GIAO DIỆN MẶC ĐỊNH
        // ============================================================
        $tours = $this->tourModel->getAllTour();
        $data = ['tours' => $tours];
        extract($data);

        $view_path = './app/views/bookings/add.php';
        require_once './app/views/layouts/main.php';
    }

    // =========================================================================
    // 2. ĐẶT TOUR THIẾT KẾ (CUSTOM)
    // =========================================================================
 public function addCustom()
    {
        $categories = $this->tourModel->getAllCategories();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // 1. Xử lý ảnh (Giữ nguyên)
            $image_name = "";
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "assets/uploads/tours/";
                if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true); 
                $safeName = time() . '_' . str_replace(' ', '_', $_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $safeName);
                $image_name = $safeName;
            }

            // 2. Tạo Tour Custom (Type = 1)
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
                // 3. Tạo Lịch trình ảo
                $scheduleData = [
                    ':tour_id' => $newTourId,
                    ':start_date' => $_POST['start_date'],
                    ':end_date' => date('Y-m-d', strtotime($_POST['start_date'] . ' + ' . $_POST['duration_days'] . ' days')),
                    ':price' => 0, // Giá 0 chờ báo giá
                    ':stock' => $_POST['people'],
                    ':guide_id' => null
                ];
                $newScheduleId = $this->tourModel->addSchedule($scheduleData);

                // 4. Tạo Booking
                $bookingData = [
                    ':tour_id' => $newTourId,
                    ':schedule_id' => $newScheduleId,
                    ':customer_name' => $_POST['customer_name'],
                    ':customer_phone' => $_POST['customer_phone'],
                    ':customer_email' => $_POST['customer_email'],
                    ':customer_address' => $_POST['customer_address'],
                    ':people' => $_POST['people'],
                    ':total_price' => 0, // Chưa có giá
                    ':start_date' => $_POST['start_date'],
                    ':note' => "Yêu cầu đặc biệt: " . $_POST['description']
                ];
                $newBookingId = $this->bookingModel->createBooking($bookingData);

                // 5. Lưu danh sách khách
                if ($newBookingId) {
                    $passengers = $_POST['passengers'] ?? [];
                    // Truyền đủ 3 tham số: ID Đơn, ID Lịch, Danh sách khách
                    $this->bookingModel->addPassengers($newBookingId, $newScheduleId, $passengers);
                }

                // === [THAY ĐỔI Ở ĐÂY] ===
                $_SESSION['success'] = "Tạo yêu cầu tour thiết kế thành công!";
                
                // Chuyển hướng về danh sách Tour thiết kế (Custom List)
                header('Location: index.php?act=custom_booking_list'); 
                exit;
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi tạo Tour!";
            }
        }
        
        // Đưa biến categories ra view
        $data = ['categories' => $categories];
        extract($data);

        $view_path = './app/views/bookings/add_custom.php';
        require_once './app/views/layouts/main.php';
    }

    // =========================================================================
    // 3. CHI TIẾT ĐƠN HÀNG (DETAIL)
    // =========================================================================
    public function detail()
    {
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
            // Load lại trang chi tiết để thấy cập nhật
            header("Location: index.php?act=booking_detail&id=$id");
            exit;
        }

        $view_path = './app/views/bookings/detail.php';
        require_once './app/views/layouts/main.php';
    }
}