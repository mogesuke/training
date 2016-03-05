<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct()
    {
        parent::__construct();

        $controller_name = $this->router->fetch_class();
        if ($controller_name != "top") 
        {
            if (!$this->isLogin())
            {
                $this->session->set_flashdata('error', 'ログインできていないか、長時間操作されなかったため、ログアウトされました。<br>ログインしてから再度お試しください。');
                redirect(base_url());
                return;
            }

            $user_id = $this->session->userdata(SESSION_USER_ID);
            $this->load->model('user/Contents_auth_model', 'contents_auth');
            $auth = $this->contents_auth->confirm_contents_auth($user_id, $controller_name);
            if (is_null($auth)) {
                $this->session->set_flashdata('error', 'その機能を使う権限がないようです。<br>他の機能でお楽しみください。');
                redirect(base_url());
                return;
            }                
        }
    }

    public function isLogin() 
    {
   		return $this->session->userdata(SESSION_NAME);
    }
}
