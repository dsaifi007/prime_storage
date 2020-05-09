<?php

/**
 * 
 */
class Common extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("admin/login/login_model", "login_model");
    }

    public function index($reset_code) {
        $email = base64_decode($reset_code);
        $response = $this->login_model->get_email_data($email);     
        if (isset($response['datetime']) && $response['datetime'] >= $this->config->item("date_time")) {
            $data['code'] = $reset_code;
            $this->load->view('admin/login/update_pass',$data);
        } else {
            echo "Link Has been Expired";
        }
    }

    function pass_update($reset_code) {
        $data = $this->input->post();;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'trim|required|matches[password]');
        //vd($this->form_validation->run());
        if ($this->form_validation->run() == FALSE) {
            $data['code'] = $reset_code;
            $this->load->view('admin/login/update_pass', $data);
        }else{
            //$this->session->set_flashdata('message', 'Password successfully updated');
            $this->login_model->update_user_password($reset_code,$data['password']);
            echo "Password successfully updated";
        }
        
    }
    public function change_password() {
        $this->data['error'] = ($this->session->flashdata("error")) ? $this->session->flashdata("error") : '';
        $this->data['success'] = ($this->session->flashdata("success")) ? $this->session->flashdata("success") : '';
        $this->data['view'] = "admin/login/change_password";
        $this->displayview($this->data);
    }

    public function ChangePasswordSubmited() {
        if ($this->changePassValidation()) {
            $this->load->model("admin/users/user_model", "user_model");
            $this->user_model->update_old_password($this->input->post());
            $this->session->set_flashdata("success", "Your Password has been Successfully updated");
            redirect("change-password");
        }
        $this->change_password();
    }

    private function changePassValidation() {
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[5]|max_length[20]');
        $this->form_validation->set_rules('current_pass', 'Old Password', 'required|callback_oldpassword_check');
        //$this->form_validation->set_rules('user_id', 'User Id', 'required|integer');
        return $this->form_validation->run();
    }

    public function oldpassword_check($old_password) {
        $old_password_hash = md5($old_password);
        $this->load->model("admin/users/user_model", "user_model");
        $old_password_db_hash = $this->user_model->get_old_pass($this->input->post());

        if ($old_password_hash != $old_password_db_hash['password']) {
            $this->form_validation->set_message('oldpassword_check', 'Current password not match');
            return FALSE;
        }
        return TRUE;
    }
    public function subadmindashboard() {
        
        $this->data['view'] = "admin/dashboard/subadmin";       
        $this->displayview($this->data);
    }
}

?>