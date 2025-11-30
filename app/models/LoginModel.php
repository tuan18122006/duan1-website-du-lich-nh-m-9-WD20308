<?php
class LoginModel extends Model
{
    // Kiểm tra login
    public function checkLogin($username, $password)
    {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch();

        // Nếu dùng hash password
        // if($user && password_verify($password, $user['password'])) return $user;

        // Nếu chưa hash
        if($user && $user['password'] == $password) return $user;

        return false;
    }
}
