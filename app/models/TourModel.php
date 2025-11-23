<?php

class TourModel extends Model{


    public function getAllTourCategory(){
        $sql = "SELECT * FROM tour_categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getOneCategory($id){
        $sql = "SELECT * FROM tour_categories WHERE category_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }



}