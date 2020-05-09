<?php

class Content_model extends CI_Model {

    protected $table = "about_us";

    function __construct() {
        parent::__construct();
    }

    public function getContent_model($data = null) {
        if ($data) {
            $this->db->where("id", 1);
            $this->db->update("about_us", ['about_text' => $data['about_text']]);
        } else {
            $this->db->select("id,about_text")->from("about_us");
            $q = $this->db->get();
            return ($q->num_rows() > 0) ? $q->row() : false;
        }
    }

    public function getterms_model($data = null) {
        if ($data) {
            $this->db->where("id", 1);
            $this->db->update("terms_conditions", ['text' => $data['terms_text']]);
        } else {
            $this->db->select("id,text")->from("terms_conditions");
            $q = $this->db->get();
            return ($q->num_rows() > 0) ? $q->row() : false;
        }
    }

    public function getcontact_model($data = null) {
        
        if ($data) {
            
            $this->db->where("id", 1);
            $this->db->update("contact_us", ['email' => $data['email'], "phone" => $data['phone']]);
        } else {
            $this->db->select("*")->from("contact_us");
            $q = $this->db->get();
            return ($q->num_rows() > 0) ? $q->row() : false;
        }
    }

}

?>