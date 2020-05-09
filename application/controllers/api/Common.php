<?php

/**
 * 
 */
class Common extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("api/user_model", "user_model");
    }

    public function index($reset_code) {
        $email = base64_decode($reset_code);
        $response = $this->user_model->email_verified($email);
       
        if (@$response['datetime'] >= $this->config->item("date_time")) {
            $this->load->helper('form');
            $data['reset_key'] = $reset_code;
            $this->load->view('update_pass/update_pass', $data);
            //$this->load->view("update_pass/update_pass");
        } else {
            echo "Link Has been Expired";
        }
    }

    function pass_update() {
        $data = $this->input->post();
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
        if ($this->form_validation->run() == FALSE) {
            $data['reset_key'] = @$data['reset_code'];
            $this->load->view('update_pass/update_pass', $data);
        }else{
            //$this->session->set_flashdata('message', 'Password successfully updated');
            $this->user_model->update_user_password($data['reset_code'],$data['password']);
            echo "Password successfully updated";
        }
        
    }

}

?>