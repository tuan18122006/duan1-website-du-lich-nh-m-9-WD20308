<?php
// app/models/UserModel.php

class UserModel extends Model
{
    // 1. Lấy danh sách user
    public function getAllUsers()
    {
        $sql = "SELECT * FROM users ORDER BY user_id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // 2. Lấy 1 user theo ID
    public function getOne($id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // 3. Thêm mới (Đã bổ sung cột avatar)
    public function insertUser($username, $password, $full_name, $email, $phone, $birthday, $role, $avatar)
    {
        $sql = "INSERT INTO users(username, password, full_name, email, phone, birthday, role, avatar) 
                VALUES(:username, :password, :full_name, :email, :phone, :birthday, :role, :avatar)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'username'  => $username,
            'password'  => $password,
            'full_name' => $full_name,
            'email'     => $email,
            'phone'     => $phone,
            'birthday'  => $birthday,
            'role'      => $role,
            'avatar'    => $avatar
        ]);
    }

    // 4. Cập nhật (Đã bổ sung cột avatar)
    public function updateUser($id, $username, $password, $full_name, $email, $phone, $birthday, $role, $avatar)
    {
        if (!empty($password)) {
            // Nếu có đổi mật khẩu
            $sql = "UPDATE users SET username=:u, password=:p, full_name=:fn, email=:e, phone=:ph, birthday=:b, role=:r, avatar=:a WHERE user_id=:id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'u' => $username,
                'p' => $password,
                'fn' => $full_name,
                'e' => $email,
                'ph' => $phone,
                'b' => $birthday,
                'r' => $role,
                'a' => $avatar,
                'id' => $id
            ]);
        } else {
            // Không đổi mật khẩu
            $sql = "UPDATE users SET username=:u, full_name=:fn, email=:e, phone=:ph, birthday=:b, role=:r, avatar=:a WHERE user_id=:id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'u' => $username,
                'fn' => $full_name,
                'e' => $email,
                'ph' => $phone,
                'b' => $birthday,
                'r' => $role,
                'a' => $avatar,
                'id' => $id
            ]);
        }
    }

    // 5. Xóa user
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE user_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // 6. Check đăng nhập
    // public function checkUser($username, $password)
    // {
    //     $sql = "SELECT * FROM users WHERE username = :username";
    //     $stmt = $this->db->prepare($sql);
    //     $stmt->execute(['username' => $username]);
    //     $user = $stmt->fetch();

    //     // Kiểm tra pass (Nếu dùng hash thì dùng password_verify)
    //     if ($user && $user['password'] == $password) {
    //         return $user;
    //     }
    //     return false;
    // }

    // 7. Check trùng username
    public function checkUsernameExists($username)
    {
        $sql = "SELECT COUNT(*) FROM users WHERE username = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }

    // 8. Cập nhật quyền (Thăng chức nhanh)
    public function updateRole($id, $role)
    {
        $sql = "UPDATE users SET role = ? WHERE user_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$role, $id]);
    }
}
