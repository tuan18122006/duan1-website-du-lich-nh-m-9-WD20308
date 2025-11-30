<?php
class GuideModel extends Model {
    
    // Lấy danh sách HDV (JOIN 2 bảng)
    public function getAllGuides() {
        $sql = "SELECT u.*, g.* FROM users u 
                JOIN guides g ON u.user_id = g.user_id 
                WHERE u.role = 2 
                ORDER BY u.user_id DESC";
        return $this->db->query($sql)->fetchAll();
    }

    // Lấy chi tiết 1 HDV
    public function getGuideById($user_id) {
        $sql = "SELECT u.*, g.experience_years, g.languages, g.guide_id 
                FROM users u 
                LEFT JOIN guides g ON u.user_id = g.user_id 
                WHERE u.user_id = :id AND u.role = 2";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $user_id]);
        return $stmt->fetch();
    }

    // Thêm mới (Transaction)
    public function createGuide($dataUser, $dataGuide) {
        try {
            $this->db->beginTransaction();

            // 1. Insert User
            $sqlUser = "INSERT INTO users (username, password, full_name, email, phone, role, birthday, avatar) 
                        VALUES (:username, :password, :full_name, :email, :phone, 2, :birthday, :avatar)";
            $stmtUser = $this->db->prepare($sqlUser);
            $stmtUser->execute($dataUser);
            
            $user_id = $this->db->lastInsertId();

            // 2. Insert Guide
            $sqlGuide = "INSERT INTO guides (user_id, full_name, date_of_birth, phone, email, experience_years, languages, avatar) 
                         VALUES (:user_id, :full_name, :birthday, :phone, :email, :exp, :lang, :avatar)";
            $stmtGuide = $this->db->prepare($sqlGuide);
            $stmtGuide->execute([
                ':user_id' => $user_id,
                ':full_name' => $dataUser[':full_name'],
                ':birthday' => $dataUser[':birthday'],
                ':phone' => $dataUser[':phone'],
                ':email' => $dataUser[':email'],
                ':exp' => $dataGuide['experience_years'],
                ':lang' => $dataGuide['languages'],
                ':avatar' => $dataUser[':avatar']
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    // Cập nhật (Transaction)
    public function updateGuide($user_id, $dataUser, $dataGuide) {
        try {
            $this->db->beginTransaction();

            // 1. Update User
            $sqlUser = "UPDATE users SET full_name=:name, email=:email, phone=:phone, birthday=:dob, avatar=:ava";
            if (!empty($dataUser['password'])) {
                $sqlUser .= ", password=:pass";
            }
            $sqlUser .= " WHERE user_id=:uid";
            
            $paramsUser = [
                ':name' => $dataUser['full_name'],
                ':email' => $dataUser['email'],
                ':phone' => $dataUser['phone'],
                ':dob' => $dataUser['birthday'],
                ':ava' => $dataUser['avatar'],
                ':uid' => $user_id
            ];
            if (!empty($dataUser['password'])) {
                $paramsUser[':pass'] = $dataUser['password'];
            }
            $this->db->prepare($sqlUser)->execute($paramsUser);

            // 2. Update Guide
            $sqlGuide = "UPDATE guides SET full_name=:name, email=:email, phone=:phone, date_of_birth=:dob, 
                         experience_years=:exp, languages=:lang, avatar=:ava 
                         WHERE user_id=:uid";
            
            $this->db->prepare($sqlGuide)->execute([
                ':name' => $dataUser['full_name'],
                ':email' => $dataUser['email'],
                ':phone' => $dataUser['phone'],
                ':dob' => $dataUser['birthday'],
                ':exp' => $dataGuide['experience_years'],
                ':lang' => $dataGuide['languages'],
                ':ava' => $dataUser['avatar'],
                ':uid' => $user_id
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}