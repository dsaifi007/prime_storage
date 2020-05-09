<?php

class Rating_model extends CI_Model {

    protected $table = "users";

    function __construct() {
        parent::__construct();
    }

    public function getAllRatingList() {
        $this->db->select("rating_review.id,reservation_history.id AS rating_id,rating_review.rating,rating_review.review,reservation_history.address,reservation_history.size,users.full_name")->from("rating_review");
        $this->db->join("reservation_history","reservation_history.id=rating_review.reservation_id","INNER");
        $this->db->join("users","users.id=reservation_history.user_id","INNER");
        $q = $this->db->get();
        return ($q->num_rows() > 0) ? $q->result() : false;
    }
}

?>