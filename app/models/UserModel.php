<?php
// app/models/UserModel.php

class UserModel extends Model {
    
    // 1. Lấy danh sách (Sửa 'taikhoan' -> 'users', 'id' -> 'user_id')
    public function getAllUsers() {
        $sql = "SELECT * FROM users ORDER BY user_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy 1 người theo ID (Sửa 'id' -> 'user_id')
    public function getOne($id) {
        $sql = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // 3. Thêm mới (Sửa tên cột cho đúng DB duan1)
    // DB duan1: username, password, email, role
    public function insertUser($username, $password, $full_name, $email, $phone, $birthday, $role) {
        $sql = "INSERT INTO users(username, password, full_name, email, phone, birthday, role) 
                VALUES(:username, :password, :full_name, :email, :phone, :birthday, :role)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'username' => $username, 
            'password' => $password, 
            'full_name'=> $full_name,
            'email'    => $email, 
            'phone'    => $phone,
            'birthday' => $birthday,
            'role'     => $role
        ]);
    }

    // 4. Cập nhật (Sửa tên cột)
    public function updateUser($id, $username, $password, $full_name, $email, $phone, $birthday, $role) {
            if (!empty($password)) {
                // Nếu có đổi mật khẩu
                $sql = "UPDATE users SET username=:u, password=:p, full_name=:fn, email=:e, phone=:ph, birthday=:b, role=:r WHERE user_id=:id";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'u' => $username, 'p' => $password, 'fn' => $full_name,
                    'e' => $email, 'ph' => $phone, 'b' => $birthday, 'r' => $role, 'id' => $id
                ]);
            } else {
                // Không đổi mật khẩu
                $sql = "UPDATE users SET username=:u, full_name=:fn, email=:e, phone=:ph, birthday=:b, role=:r WHERE user_id=:id";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'u' => $username, 'fn' => $full_name,
                    'e' => $email, 'ph' => $phone, 'b' => $birthday, 'r' => $role, 'id' => $id
                ]);
            }
        }

    // 5. Xóa (Sửa 'id' -> 'user_id')
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // 6. Check đăng nhập (Sửa 'user' -> 'username', 'pass' -> 'password')
    public function checkUser($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Nếu tìm thấy user, kiểm tra password
        // Lưu ý: Nếu DB bạn lưu pass thường (không mã hóa) thì so sánh trực tiếp:
        // if ($user && $user['password'] == $password) ...
        
        // Nếu DB lưu password_hash (khuyên dùng) thì:
        // if ($user && password_verify($password, $user['password'])) ...

        // Tạm thời để so sánh thường cho dễ chạy trước:
        if ($user && $user['password'] == $password) {
            return $user;
        }
        return false;
    }
}