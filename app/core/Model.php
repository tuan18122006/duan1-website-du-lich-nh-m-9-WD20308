<?php
// File: app/core/Model.php

class Model {  // Tên class phải là Model
    protected $db;

    public function __construct() {
        // Đảm bảo hàm connectDB() đã có trong app/helpers/functions.php
        $this->db = connectDB(); 
    }
}
?>