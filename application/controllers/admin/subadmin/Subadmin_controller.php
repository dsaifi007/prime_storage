<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subadmin_controller extends MY_Controller {

    protected $data = [];

    public function __construct() {
        parent::__construct();
        $this->user_not_loggedin();
        $this->load->model("admin/subadmin/subadmin_model", "subadmin_model");
    }

    public function index() {
        $this->data['view'] = "admin/subadmin/subadmin_listing";
        $this->data['items'] = $this->subadmin_model->getlist();
        $this->displayview($this->data);
    }

    public function loadSubadminView($user_id = NULL) {        
        $this->data['error'] = ($this->session->flashdata("error")) ? $this->session->flashdata("error") : '';
        $this->data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->data['module_name'] = $this->subadmin_model->getModule();
        if ($user_id) {
            $this->data['access_control'] = $this->subadmin_model->getAdminPrivilege($user_id);  
            //dd($this->data['access_control']);
            $this->data['view'] = "admin/subadmin/edit_subadmin";
        } else {
            $this->data['view'] = "admin/subadmin/add_subadmin";
        }

        $this->displayview($this->data);
    }

    public function SubadminSubmited() {
        
        if ($this->SubadminValidation()) {
            $input = $this->input->post();
            $input['password'] = mt_rand(1, 99999999);
            $input['control_id'] = ($input['control_id'])?$input['control_id']:array();
            $this->subadmin_model->addSubAdmin($input);
            $this->send_email($input['email'],$input['password']);
            $this->session->set_flashdata("success", "Subadmin has been successfully added");
            redirect("sub-admin/0");
        }
        $this->loadSubadminView();
    }
    public function send_email($email,$pass) {
        $this->load->library("email_setting");
        $subject = "New Account Detail"; // language file is not working
        $message ='';
        $message.="Email : ".$email."<br>";
        $message.="Password : ".$pass;
        $response = $this->email_setting->send_email($email, $message, $subject);
        return true;
    }
    private function SubadminValidation() {
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('full_name', 'Name', 'required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[admin.email]');
        $this->form_validation->set_rules('phone', 'Phone', 'required');
        $this->form_validation->set_rules('control_id[]', 'Role', 'required',["required"=>"Please Active at least one module"]);
        //$this->form_validation->set_rules('user_id', 'User Id', 'required|integer');
        return $this->form_validation->run();
    }

    function update_user_status() {
        $input = $this->input->post();
        //dd($input);
        $this->subadmin_model->update_user_status_model($input);
        if ((int) $input['status'] == 1) {
            echo json_encode(["message" => "User Successfully Blocked"]);
        } else {
            echo json_encode(["message" => "User Successfully Unblocked"]);
        }
        exit();
        die;
    }
    function update_edit_info() {
        $input = $this->input->post();
       
        $this->subadmin_model->update_edit_info_model($input);
        if ((int) $input['status'] == 1) {
            echo json_encode(["message" => "Subadmin Successfully Blocked"]);
        } else {
            echo json_encode(["message" => "Subadmin Successfully Unblocked"]);
        }
        exit();
        die;
    }
}

?>