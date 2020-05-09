<?php

require(APPPATH . '/libraries/REST_Controller.php');

//use Restserver\Libraries\REST_Controller;

class Users extends REST_Controller {

    private $user_data = [];
    private $response_send = [];

    public function __construct() {
        try {
            parent::__construct();

            $this->headers = apache_request_headers();
            $this->load->model("api/user_model", "user_model");
            //content_type($this->headers);
            change_languge($this->headers);
        } catch (Exception $exc) {
            $this->response_send = ["message" => $exc->getMessage(), "status" => $this->config->item("status_false")];
            $this->response($this->response_send);
        }
    }

    /*
      |-----------------------------------------------------------------------------------------
      | This Function will insert the data of user in database (only user information add in DB)
      |-----------------------------------------------------------------------------------------
     */

    public function createUseraccount_post() {
        try {
            $this->user_data = $this->post();
            $user_input_array = ["email", "full_name", "password", "device_id", "device_type", "device_token"];
            if (check_form_array_keys_existance($this->user_data, $user_input_array) && check_user_input_values($this->user_data)) {
                if ($this->userFormValidation()) {
                    $result = $this->user_model->createAccountDataInsert($this->user_data);
                    $this->response_send = display_api_response($result, $this->config->item("status_true"), $this->lang->line("account_created"));
                    unset($this->response_send['payload']->is_authorized);
                    $this->response_send['is_authorized'] = $result['is_authorized'];
                } else {
                    $error = $this->form_validation->error_array();
                    $error_key = array_keys($error);
                    $this->response_send = display_api_response(array(), $this->config->item("status_false"), $error[$error_key[0]]);
                }
            } else {
                $this->response_send = display_api_response(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'));
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_response(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function UserLogin_post() {
        try {
            $this->user_data = $this->post();
            $user_input_array = ["email", "password"];
            if (check_form_array_keys_existance($this->user_data, $user_input_array) && check_user_input_values($this->user_data)) {
                if ($this->userFormValidation(1)) {
                    $result = $this->user_model->userLoginModel($this->user_data);
                    if ($result) {
                        $this->user_model->updatestate($this->user_data, true); // True = success count
                        $this->response_send = display_api_response($result, $this->config->item("status_true"), $this->lang->line("login_success"), $result['is_authorized']);
                        unset($this->response_send['payload']->is_authorized);
                    } else {
                        $this->user_model->updatestate($this->user_data, false); // True = failed count
                        $this->response_send = display_api_response([], $this->config->item("status_false"), $this->lang->line("invalid_credential"));
                    }
                } else {
                    $error = $this->form_validation->error_array();
                    $error_key = array_keys($error);
                    $this->response_send = display_api_response(array(), $this->config->item("status_false"), $error[$error_key[0]]);
                }
            } else {
                $this->response_send = display_api_response(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'));
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_response(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function userFormValidation($login_credential = null) {
        $this->load->library('form_validation');
        if ($login_credential == null) {
            $this->form_validation->set_rules('full_name', 'Full Name', 'required');
            $is_unique = "|is_unique[users.email]";
        } else {
            $is_unique = '';
        }
        $this->form_validation->set_rules('device_id', 'Device Id', 'required');
        $this->form_validation->set_rules('device_type', 'Device Type', 'min_length[3]|max_length[7]|required|callback_alpha_dash_space');
        $this->form_validation->set_rules('device_token', 'Device Token', 'required');
        $this->form_validation->set_rules('password', 'lang:password', 'required');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email' . $is_unique, array('valid_email' => $this->lang->line("invalid_email")));
        return $this->form_validation->run();
    }

    public function forget_password_get() {
        try {
            $this->user_data = $this->input->get();
            if ($this->user_data['email'] != '') {
                $response = $this->user_model->check_email_existence(trim($this->user_data['email']));
                if ($response == false) {
                    $this->response_send = display_api_response(array(), $this->config->item("status_false"), $this->lang->line('email_not_found'));
                } else {
                    $this->send_temp_password($this->user_data['email']);
                    //$this->response_send = ["message" => $this->lang->line('sent_temp_email'), "status" => $this->config->item("status_true")];
                    $this->response_send = display_api_response(array(), $this->config->item("status_true"), $this->lang->line('sent_temp_email'));
                }
            } else {
                $this->response_send = display_api_response(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'));
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_response(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function send_temp_password($email) {
        $this->load->helper('string');
        $em = base64_encode($email);

        $this->load->library("email_setting");
        $subject = "Forgot Password"; // language file is not working
        $message = "Please click this link " . base_url() . "api/common/index/$em";

        $response = $this->email_setting->send_email($email, $message, $subject);
        if ($response) {
            // update status
            $this->user_model->update_email_link_status($email);
        }
        return true;
    }

    public function profile_update_post() {
        try {

            //$authorization = is_user_authorization_check($this->headers);
            check_acces_token($this->headers);
            $this->user_data = $this->post();
            $user_input_array = ["full_name"];
            if (check_form_array_keys_existance($this->user_data, $user_input_array)) {
                if ($this->userEditFormValidation()) {
                    $this->user_model->update_profile($this->user_data);
                    $this->response_send = display_api_response(array(), $this->config->item("status_true"), $this->lang->line("profile_updated"), $this->headers);
                } else {
                    $error = $this->form_validation->error_array();
                    $error_key = array_keys($error);
                    $this->response_send = display_api_response(array(), $this->config->item("status_false"), $error[$error_key[0]], $this->headers);
                }
            } else {
                $this->response_send = display_api_response(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_response(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    function userEditFormValidation() {
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('full_name', 'Full Name', 'required|callback_alpha_dash_space');
        $this->form_validation->set_rules('user_id', 'User Id', 'required|integer');
        $this->form_validation->set_rules('phone', 'Phone', 'xss_clean|max_length[16]|min_length[10]|integer'); // regex_match[/^[0-9]{10}$/]
        //$this->form_validation->set_rules('email', 'email', 'required|valid_email' . $is_unique, array('valid_email' => $this->lang->line("invalid_email")));
        return $this->form_validation->run();
    }

    public function alpha_dash_space($fullname) {
        if (!preg_match('/^[a-zA-Z\s]+$/', $fullname)) {
            $this->form_validation->set_message('alpha_dash_space', 'The %s field may only contain alpha characters & White spaces');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function upload_profile_img_post() {
        try {
            $this->user_data = $this->post();
            //$this->user_data = ["user_id"=>20, "is_image"=>1];

            check_acces_token($this->headers);
            if (check_form_array_keys_existance($this->user_data, ["user_id", "is_image"]) && (int) $this->user_data['user_id']) {

                if (count($_FILES) > 0 && isset($_FILES['user_image_url']['name']) && (int) $this->user_data['is_image'] == 1) {

                    $file_name = $_FILES['user_image_url']['name'];
                    $this->load->library("common");
                    $this->load->helper('string');
                    $rename_image = (random_string('numeric') + time()) . random_string();
                    $img_data = $this->common->file_upload("assets/api/img/profile_img", "user_image_url", $rename_image);
                    if (isset($img_data["upload_data"]['file_name'])) {
                        remove_existing_img($this->user_data['user_id'], "users", "user_image", "assets/api/img/profile_img");
                        $new_file_name = $img_data["upload_data"]['file_name'];
                        $file_url = base_url() . "assets/api/img/profile_img/" . $new_file_name;
                        $this->user_model->update_profile_img_update($this->user_data['user_id'], $file_url, $new_file_name);
                        //$this->response_send = ["message" => $this->lang->line('profile_image_updated'), "profile_image_url" => $file_url, "status" => $this->config->item("status_true")];
                        $data = ["user_image_url" => $file_url];
                        $this->response_send = display_api_response($data, $this->config->item("status_true"), $this->lang->line('profile_image_updated'), $this->headers);
                    } else {
                        //$this->response_send = ["message" => strip_tags($img_data['error']), "status" => $this->config->item("status_false")];
                        $this->response_send = display_api_response(array(), $this->config->item("status_false"), strip_tags($img_data['error']), $this->headers);
                    }
                } else {

                    // It means user want to remove the profile image
                    $this->user_model->remove_profile_img($this->user_data['user_id']);
                    $this->response_send = display_api_response(array(), $this->config->item("status_true"), $this->lang->line('profile_image_removed'), $this->headers);
                }
            } else {
                $this->response_send = display_api_response(["user_id", "is_image", "user_image_url"], $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_response(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function change_password_post() {
        try {
            //$authorization = is_user_authorization_check($this->headers);
            check_acces_token($this->headers);
            $this->user_data = $this->post();
            $user_input_array = ["user_id", "password", "old_password"];
            if (check_form_array_keys_existance($this->user_data, $user_input_array)) {
                if ($this->changePassValidation()) {
                    $token = $this->user_model->change_password($this->user_data);
                    $this->response_send = display_api_response(array("access_token" => $token), $this->config->item("status_true"), $this->lang->line("password_updated"), $this->headers);
                } else {
                    $error = $this->form_validation->error_array();
                    $error_key = array_keys($error);
                    $this->response_send = display_api_response(array(), $this->config->item("status_false"), $error[$error_key[0]], $this->headers);
                }
            } else {
                $this->response_send = display_api_response(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_response(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    function changePassValidation() {
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[20]');
        $this->form_validation->set_rules('old_password', 'Old Password', 'required|callback_oldpassword_check');
        $this->form_validation->set_rules('user_id', 'User Id', 'required|integer');
        return $this->form_validation->run();
    }

    public function oldpassword_check($old_password) {
        $old_password_hash = md5($old_password);

        $old_password_db_hash = $this->user_model->get_old_pass($this->input->post());

        if ($old_password_hash != $old_password_db_hash['password']) {
            $this->form_validation->set_message('oldpassword_check', 'Old password not match');
            return FALSE;
        }
        return TRUE;
    }

    public function needHelp_post() {
        try {
            //$authorization = is_user_authorization_check($this->headers);
            check_acces_token($this->headers);
            $this->user_data = $this->post();
            $user_input_array = ["name", "email", "phone", "query", "result_key"];
            $this->user_data['result_key'] = (isset($this->user_data["result_key"]) && trim($this->user_data["result_key"]) != '') ? $this->user_data["result_key"] : "payload";

            if (check_form_array_keys_existance($this->user_data, $user_input_array) && check_user_input_values($this->user_data)) {
                $this->user_model->need_help_model($this->user_data);
                $this->response_send = display_api_res(array(), $this->config->item("status_true"), $this->lang->line('date_added'), $this->headers, $this->user_data['result_key']);
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function contactUs_get() {
        try {
            //$authorization = is_user_authorization_check($this->headers);
            check_acces_token($this->headers);
            $key = $this->get();
            $data = $this->user_model->get_contact_us_model();
            if ($data) {
                $result_key = (isset($key['result_key']) && trim($key['result_key'])) ? $key['result_key'] : "payload";
                //echo $result_key;die;
                $this->response_send = display_api_res([$data], $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $result_key);
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res(array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function aboutUs_get() {
        try {

            //$authorization = is_user_authorization_check($this->headers);
            //check_acces_token($this->headers);
            $key = $this->get();
            $data = $this->user_model->get_about_us_model();

            if ($data) {
                $result_key = (isset($key['result_key']) && trim($key['result_key'])) ? $key['result_key'] : "payload";
                //echo $result_key;die;
                $this->response_send = display_api_res($data, $this->config->item("status_true"), $this->lang->line('success'), "no_auth", $result_key);
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), "no_auth");
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function termsCondtn_get() {
        try {
            //$authorization = is_user_authorization_check($this->headers);
            //check_acces_token($this->headers);
            $key = $this->get();
            $data = $this->user_model->get_terms_condtn_model();
            //dd($data);
            if ($data) {
                $result_key = (isset($key['result_key']) && trim($key['result_key'])) ? $key['result_key'] : "payload";
                //echo $result_key;die;
                $this->response_send = display_api_res($data, $this->config->item("status_true"), $this->lang->line('success'), "no_auth", $result_key);
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), "no_auth");
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    function auto_login_post() {
        try {
            //$authorization = is_user_authorization_check($this->headers);
            check_acces_token($this->headers);
            $user_id = $this->post();
            $user_id['result_key'] = (isset($user_id['result_key']) && trim($user_id['result_key'])) ? $user_id['result_key'] : "payload";
            if (check_form_array_keys_existance($user_id, ["user_id"]) && check_user_input_values($user_id)) {
                $data = $this->user_model->get_user_detail_model($user_id['user_id']);
                if ($data) {
                    $this->response_send = display_api_res($data, $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $user_id['result_key']);
                } else {
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $user_id['result_key']);
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $user_id['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function userLogout_post() {
        try {
            //$authorization = is_user_authorization_check($this->headers);
            check_acces_token($this->headers);
            $key = $this->post();
            $key['result_key'] = (isset($key['result_key']) && trim($key['result_key'])) ? $key['result_key'] : "payload";
            if (check_form_array_keys_existance($key, ['user_id']) && check_user_input_values($key)) {
                $data = $this->user_model->user_logout_model($key['user_id']);
                if ($data) {
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $key['result_key']);
                } else {
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers);
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function addAppIssue_post() {
        try {

            check_acces_token($this->headers);
            $app_issue = $this->post();
            $input_array = ["issues", "result_key"];
            $app_issue['result_key'] = (isset($app_issue["result_key"]) && trim($app_issue["result_key"]) != '') ? $app_issue["result_key"] : "payload";

            if (check_form_array_keys_existance($app_issue, $input_array) && check_user_input_values($app_issue)) {
                $this->user_model->add_app_issue_model($app_issue);
                $this->response_send = display_api_res((object) array(), $this->config->item("status_true"), $this->lang->line('date_added'), $this->headers, $app_issue['result_key']);
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function offerFeaturedList_post() {
        try {
            check_acces_token($this->headers);
            $app_issue = $this->post();
            $app_issue['result_key'] = (isset($app_issue["result_key"]) && trim($app_issue["result_key"]) != '') ? $app_issue["result_key"] : "payload";
            $result = $this->user_model->get_all_offer_featured_model($app_issue);
            if ($result) {
                $this->response_send = display_api_res($result, $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $app_issue['result_key']);
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function getStoreRatingByUser_post() {
        try {

            check_acces_token($this->headers);
            $input = $this->post();
            $input['result_key'] = (isset($input["result_key"]) && trim($input["result_key"]) != '') ? $input["result_key"] : "payload";
            $result = $this->user_model->GetStoreRatingModal($input);
            if ($result) {
                $this->response_send = display_api_res($result, $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $input['result_key']);
            } else {
                $this->response_send = display_api_res(array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function userNotificationStatusChange_post() {
        try {
            check_acces_token($this->headers);
            $input = $this->post();
            if (check_form_array_keys_existance($input, ["user_id", "notification_status"]) && check_user_input_values($input)) {
                $input['result_key'] = (isset($input["result_key"]) && trim($input["result_key"]) != '') ? $input["result_key"] : "payload";
                $result = $this->user_model->userNotificationStatusChange_model($input);
                if ($result) {
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $input['result_key']);
                } else {
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers);
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    public function sendUserQueryToAdminEmail_post() {
        try {
            check_acces_token($this->headers);
            $input = $this->input->post();
            $input['result_key'] = (isset($input["result_key"]) && trim($input["result_key"]) != '') ? $input["result_key"] : "payload";
            if (check_form_array_keys_existance($input, ["user_query", "user_id"])) {
                $data["email_data"] = json_decode($input['user_query'], true);
                $result = $this->user_model->user_info_modal($input['user_id']);
                if (!empty($result)) {
                    $this->emailSend($data["email_data"], $result);
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_true"), $this->lang->line('success'), $this->headers, $input['result_key']);
                } else {
                    $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('no_data_found'), $this->headers, $input['result_key']);
                }
            } else {
                $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $this->lang->line('all_field_required'), $this->headers, $input['result_key']);
            }
        } catch (Exception $exc) {
            $this->response_send = display_api_res((object) array(), $this->config->item("status_false"), $exc->getMessage());
        }
        $this->response($this->response_send);
    }

    private function emailSend($data, $user_info) {
        $html = '';
        $html .= "<p>Name : " . $user_info['full_name'] . "<p>";
        $html .= "<p>Email : " . $user_info['email'] . "</p><hr>";
        foreach ($data as $key => $value) {
            $html .= "<p>" . $value['question'] . "</p>";
            $html .= "<p>" . $value['answer'] . "</p>";
        }
        $this->load->library("email_setting");
        $subject = "User Query"; // language file is not working
        $this->email_setting->send_email("danishk@chromeinfotech.com", $html, $subject);
    }

}

?>
