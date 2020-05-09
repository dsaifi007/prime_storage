<?php

class Email_setting {

    protected $CI;

    /* public function email_config() {
      $this->CI =& get_instance();

      $this->CI->config->load('shared');

      //$headers = "From: danishk@chromeinfotech.com\r\n";
      // $headers = "From: verify@docmdapp.com\r\n";
      // $headers .= 'MIME-Version: 1.0' . "\r\n";
      // $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";


      //$config['from_email'] = 'danishk@chromeinfotech.com';
      //$config['from_name'] = 'Docmd';

      $config = array(
      'smtp_crypto'=>'tls',
      'protocol' => $this->CI->config->item('smtp'), // smtp/sendmail
      //'useragent'=> $this->config->item('useragent');"CodeIgniter",
      //'mailpath' => $this->config->item('mailpath'),
      'smtp_host' => $this->CI->config->item('smtp_host'), //change this
      'smtp_port' => $this->CI->config->item('smtp_port'),//'465',
      'smtp_user' => $this->CI->config->item('smtp_user'), //change this
      'smtp_pass' => $this->CI->config->item('smtp_pass'), //change this
      'mailtype' =>  $this->CI->config->item('mailtype'),
      'charset'  =>  $this->CI->config->item('charset')
      //'wordwrap' =>  $CI->config->item('wordwrap'),
      //'newline' =>   $CI->config->item('newline'),
      //'starttls'  => true,
      );
      $this->CI->load->library('email',$config);

      //$CI->email->initialize($config);
      }
      public function send1_email($to=[],$from="",$message="",$subject="",$cc=[],$bcc="")
      {

      $this->email_config();
      $this->CI->email->set_newline("\r\n");
      //$CI= & get_instance();
      //$CI->email->set_header("From", "verify@docmdapp.com"."\r\n");
      //$CI->email->set_header("MIME-Version", "1.0");
      //$CI->email->set_header("Content-type", "text/plain; charset=UTF-8"."\r\n");

      $this->CI->email->from($this->CI->config->item('smtp_user'), 'DOC MD');
      $this->CI->email->to($to);
      //$CI->email->cc($cc);
      //$CI->email->bcc($bcc);
      $this->CI->email->subject($subject);
      $this->CI->email->message($message);
      return $this->CI->email->send();
      //$debug = $this->email->print_debugger();
      //print_r($debug);die;
      } */

    public function send_email($to = [], $message = "", $subject = "", $cc = [], $bcc = "", $attatch = "") {
        try {
            $CI = & get_instance();
            $CI->config->load('shared');
            $account_name = 'Prime Storage';
            $body = $message;

            $config['smtp_crypto'] = $CI->config->item('smtp_crypto'); //smtp_crypto
            $config['protocol'] = $CI->config->item('protocol');
            $config['smtp_host'] = $CI->config->item('smtp_host');
            $config['smtp_port'] = $CI->config->item('smtp_port');
            $config['smtp_user'] = $CI->config->item('smtp_user');
            $config['smtp_pass'] = $CI->config->item('smtp_pass');
            $config['mailtype'] = $CI->config->item('mailtype'); //
            $config['charset'] = $CI->config->item('charset');

            //$this->email->initialize($config);
            $CI->load->library('email', $config);
            $CI->email->set_newline("\r\n");
            $CI->email->from($CI->config->item('smtp_user'), $account_name);
            $CI->email->to($to);
            //$CI->email->cc($cc);
            //$CI->email->bcc($bcc);
            $CI->email->subject($subject);
            $CI->email->message($body);
            $CI->email->attach($attatch);
            return $CI->email->send();
        } catch (Exception $e) {
            echo json_encode(['Message' => $e->getMessage(), "status" => "false"]);
            die;
        }
    }

}

?>