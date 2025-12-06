<?php
class LoginModel extends Model
{
    // Kiểm tra login
public function checkLogin($username, $password)
{
    $sql = "SELECT users.*, guides.guide_id AS guide_id
            FROM users
            LEFT JOIN guides ON guides.user_id = users.user_id
            WHERE users.username = :username";

    $stmt = $this->db->prepare($sql);
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && $user['password'] == $password) {
        return $user;
    }

    return false;
}
}
