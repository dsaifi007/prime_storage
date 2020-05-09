<?php

class Payment_model extends CI_Model {

    protected $table = "users";

    function __construct() {
        parent::__construct();
    }

    public function getAllPaymentList() {
        //$this->db->where("payment_status",1);
        $this->db->select("reservation_history.id,users.phone,users.email,reservation_history.created_date,reservation_history.price,users.full_name,reservation_history.payment_status")->from("reservation_history");
        $this->db->join("users", "users.id=reservation_history.user_id","INNER");
        $q = $this->db->get();
        //echo $this->db->last_query();die;
        return ($q->num_rows() > 0) ? $q->result() : false;
    }

}

?>