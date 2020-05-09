<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Rating_controller extends MY_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->user_not_loggedin();
        $this->load->model("admin/rating/rating_model", "rating_model");
    }

    public function index() {
        $this->data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->data['view'] = "admin/rating/rating_listing";
        $this->data['items'] = $this->rating_model->getAllRatingList();    
        $this->displayview($this->data);
    }  
    public function delete($id) {       
        $this->db->where("id",$id);
        $this->db->delete("reservation_history");
        $this->session->set_flashdata("success","Item has been successfully deleted");
        redirect("rating");
    }
}

?>