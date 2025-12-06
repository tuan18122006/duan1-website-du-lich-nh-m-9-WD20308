<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vi Vu Travel - Khám phá thế giới</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- 1. CẤU HÌNH CHUNG (RESET CSS) --- */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { background-color: #f9f9f9; color: #333; }
        a { text-decoration: none; }
        ul { list-style: none; }

        /* --- 2. THANH ĐIỀU HƯỚNG (NAVBAR) --- */
        header {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 20px;
        }
        .logo { font-size: 24px; font-weight: 700; color: #2d3436; }
        .logo span { color: #0984e3; }

        .nav-links { display: flex; gap: 30px; align-items: center; }
        .nav-links a { color: #2d3436; font-weight: 500; transition: 0.3s; }
        .nav-links a:hover { color: #0984e3; }

        /* Nút Đăng nhập nổi bật */
        .btn-login {
            background-color: #0984e3;
            color: #fff !important;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(9, 132, 227, 0.3);
        }
        .btn-login:hover {
            background-color: #00cec9;
            transform: translateY(-2px);
        }

        /* --- 3. BANNER CHÍNH (HERO SECTION) --- */
        .hero {
            height: 80vh; /* Chiều cao bằng 80% màn hình */
            background: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: #fff;
            margin-top: 60px; /* Tránh bị Navbar che */
        }
        .hero h1 { font-size: 3.5rem; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.5); }
        .hero p { font-size: 1.2rem; margin-bottom: 30px; max-width: 700px; }
        
        .btn-explore {
            padding: 15px 40px;
            background: #fff;
            color: #333;
            font-weight: 600;
            border-radius: 50px;
            font-size: 1rem;
            transition: 0.3s;
        }
        .btn-explore:hover { background: #0984e3; color: #fff; }

        /* --- 4. DANH SÁCH TOUR (GRID LAYOUT) --- */
        .container { max-width: 1200px; margin: 0 auto; padding: 60px 20px; }
        .section-title { text-align: center; margin-bottom: 50px; }
        .section-title h2 { font-size: 2.5rem; margin-bottom: 10px; color: #2d3436; }
        .section-title p { color: #636e72; }

        .tour-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); /* Tự động chia cột */
            gap: 30px;
        }

        .tour-card {
            background: #fff;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        .tour-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        
        .tour-img { width: 100%; height: 200px; object-fit: cover; }
        
        .tour-content { padding: 20px; }
        .tour-location { color: #0984e3; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; margin-bottom: 5px; display: block; }
        .tour-title { font-size: 1.3rem; margin-bottom: 10px; color: #2d3436; }
        .tour-price { color: #d63031; font-weight: 700; font-size: 1.2rem; float: right; }
        
        .tour-footer {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .rating { color: #f1c40f; font-size: 0.9rem; }
        .btn-book { color: #0984e3; font-weight: 600; }
        .btn-book:hover { text-decoration: underline; }

        /* --- 5. FOOTER --- */
        footer { background: #2d3436; color: #fff; padding: 20px 0; text-align: center; margin-top: 50px; }
    </style>
</head>
<body>

    <header>
        <nav>
            <!-- <a href="#" class="logo"><i class="fas fa-plane-departure"></i> ViVu<span>Travel</span></a> -->
            
            <ul class="nav-links">
                <li><a href="#">Trang chủ</a></li>
                <li><a href="#tours">Danh sách Tour</a></li>
                <li><a href="#">Tin tức</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>

            <a href="index.php?act=login" class="btn-login">
                <i class="fas fa-user"></i> Đăng nhập
            </a>
        </nav>
    </header>

    <div class="hero">
        <h1>Du Lịch & Trải Nghiệm</h1>
        <p>Hệ thống quản lý và đặt tour du lịch hàng đầu Việt Nam. Khám phá những vùng đất mới cùng chúng tôi ngay hôm nay.</p>
        <a href="#tours" class="btn-explore">Xem Tour Ngay <i class="fas fa-arrow-down"></i></a>
    </div>

    <section id="tours" class="container">
        <div class="section-title">
            <h2>Tour Nổi Bật 2025</h2>
            <p>Những địa điểm được yêu thích nhất mùa hè này</p>
        </div>

        <div class="tour-grid">
            <div class="tour-card">
                <img src="https://tourdulichgiare.com.vn/wp-content/uploads/2019/09/cau-vang-ba-na-hill-1.jpg" alt="Đà Nẵng" class="tour-img">
                <div class="tour-content">
                    <span class="tour-location"><i class="fas fa-map-marker-alt"></i> Đà Nẵng</span>
                    <h3 class="tour-title">Khám Phá Cầu Vàng & Bà Nà Hills</h3>
                    <div>
                        <span class="rating"><i class="fas fa-star"></i> 4.8 (120 reviews)</span>
                        <span class="tour-price">2.500.000₫</span>
                    </div>
                    <div class="tour-footer">
                        <span><i class="far fa-clock"></i> 3 ngày 2 đêm</span>
                        <a href="#" class="btn-book">Chi tiết &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="tour-card">
                <img src="https://images.unsplash.com/photo-1528127269322-539801943592?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Hạ Long" class="tour-img">
                <div class="tour-content">
                    <span class="tour-location"><i class="fas fa-map-marker-alt"></i> Quảng Ninh</span>
                    <h3 class="tour-title">Du Thuyền Vịnh Hạ Long 5 Sao</h3>
                    <div>
                        <span class="rating"><i class="fas fa-star"></i> 5.0 (85 reviews)</span>
                        <span class="tour-price">4.200.000₫</span>
                    </div>
                    <div class="tour-footer">
                        <span><i class="far fa-clock"></i> 2 ngày 1 đêm</span>
                        <a href="#" class="btn-book">Chi tiết &rarr;</a>
                    </div>
                </div>
            </div>

            <div class="tour-card">
                <img src="https://images.unsplash.com/photo-1504214208698-ea1916a2195a?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Sapa" class="tour-img">
                <div class="tour-content">
                    <span class="tour-location"><i class="fas fa-map-marker-alt"></i> Lào Cai</span>
                    <h3 class="tour-title">Săn Mây Sapa - Đỉnh Fansipan</h3>
                    <div>
                        <span class="rating"><i class="fas fa-star"></i> 4.7 (200 reviews)</span>
                        <span class="tour-price">3.100.000₫</span>
                    </div>
                    <div class="tour-footer">
                        <span><i class="far fa-clock"></i> 4 ngày 3 đêm</span>
                        <a href="#" class="btn-book">Chi tiết &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>Quản lý Tour du lịch</p>
    </footer>

</body>
</html>