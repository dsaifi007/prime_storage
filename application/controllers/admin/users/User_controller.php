<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User_controller extends MY_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->user_not_loggedin();
        $this->load->model("admin/users/user_model", "user_model");
    }

    public function index() {
        //dd($_SESSION);
        $this->data['view'] = "admin/users/users_listing";
        $this->data['items'] = $this->user_model->getAllUserList();
        $this->displayview($this->data);
    }

    function update_user_status() {
        $input = $this->input->post();
        $this->user_model->update_user_status_model($input);
        if ((int) $input['status'] == 1) {
            echo json_encode(["message" => "User Successfully Blocked"]);
        } else {
            echo json_encode(["message" => "User Successfully Unblocked"]);
        }
        exit();
        die;
    }
    public function userDetail($id) {
       
        $this->data['view'] = "admin/users/user_payment_list";
        $this->data['items'] = $this->user_model->userDetailModel($id);
        
        $this->displayview($this->data);
    }


    #---------------------------------------- User query ---------------------------------------------------------

    public function userQueries() {
        $this->data['view'] = "admin/users/user_queries";
        $this->data['items'] = $this->user_model->user_queries();
        $this->displayview($this->data);
    }

}

?>