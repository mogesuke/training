<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    function __construct() {
        parent::__construct();

        $CI =& get_instance();
        $CI->load = $this;
    }

    public function isLogin() 
    {
        return $this->session->userdata(SESSION_NAME);
    }
}