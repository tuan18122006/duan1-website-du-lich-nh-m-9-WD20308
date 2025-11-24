<?php

class TourModel extends Model
{


    public function getAllTour()
    {
        $sql = "SELECT tours.*, tour_categories.category_name 
                FROM tours JOIN 
                tour_categories ON tours.category_id = tour_categories.category_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
    public function getToursByCategoryId($category_id)
    {
        $sql = "SELECT tours.*, tour_categories.category_name 
                FROM tours AS tou 
                JOIN tour_categories AS to_cate ON tou.category_id = to_cate.category_id
                WHERE tou.category_id = :category_id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);

        return $stmt->fetchAll();
    }

    public function getAllCategories()
    {
        try {
            $sql = "SELECT * FROM tour_categories";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $tour_categories = $stmt->fetchAll();
            return $tour_categories;
        } catch (Exception $e) {
            return [];
        }
    }


    public function addTourInfo($data)
    {
        try {
            $sql = "INSERT INTO tours (
                    category_id, tour_name, short_description, description, 
                    duration_days, price, end_location, start_location, 
                    supplier, policy, image_url, status 
                ) 
                VALUES (
                    :category_id, :tour_name, :short_description, :description, 
                    :duration_days, :price, :end_location, :start_location, 
                    :supplier, :policy, :image_url, :status
                )";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function deleteTour($id)
    {
        try {
            $sql = "DELETE FROM tours WHERE tour_id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getTourById($id)
    {
        try {
            $sql = "SELECT * FROM tours WHERE tour_id = :id";

            $stmt = $this->db->prepare($sql);

            $stmt->execute(['id' => $id]);

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateTour($data)
    {
        try {
            $sql = "UPDATE tours SET 
                    category_id       = :category_id,
                    tour_name         = :tour_name,
                    short_description = :short_description,
                    description       = :description,
                    duration_days     = :duration_days,
                    price             = :price,
                    image_url         = :image_url, 
                    end_location      = :end_location,
                    start_location    = :start_location,
                    supplier          = :supplier,
                    policy            = :policy,
                    status            = :status
                WHERE tour_id = :tour_id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
