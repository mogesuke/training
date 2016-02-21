<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Top extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$oauth_url = $this->createOauthUrlGetCode();
		$data = array(
               'oauth_url' => $oauth_url
        );

		$this->load->view('common/html_header');
		$this->load->view('top/index', $data);
		$this->load->view('common/html_footer');
	}

	public function callback()
	{
		// codeを取得
		$code = $this->input->get('code');
		// codeを使ってtoken取得
		$token = $this->oauthGetToken($code);
		// 戻り値をjsonエンコード
		$token_json = json_decode($token);
		// id_tokenから一部情報を取得
		// $id_token = explode(".", $token_json->id_token);

		// profile取得
		$profile = $this->getProfile($token_json->access_token);
		$profile_json = json_decode($profile);

		$this->session->set_userdata('name', $profile_json->name);

		$this->load->view('common/html_header');
		$this->load->view('top/sessiontest');
		$this->load->view('common/html_footer');
	}

	private function createOauthUrlGetCode() {
		return OAUTH_URL_BASE ."auth"
						."?client_id=" .OAUTH_CLIENT_ID
						."&scope=openid email profile"
						."&response_type=code"
						."&redirect_uri=http://" .$_SERVER["HTTP_HOST"] ."/top/callback"
						."&approval_prompt=force"
						."&access_type=offline";
	}

	private function oauthGetToken($code) {
		$url = OAUTH_URL_BASE ."token";

		$data = array(
            "client_id" => OAUTH_CLIENT_ID,
            "client_secret" => OAUTH_CLIENT_SECRET,
            "grant_type" => "authorization_code",
            "redirect_uri" => "http://" .$_SERVER["HTTP_HOST"] ."/top/callback",
            "code" => $code
        );
        return $this->executePost($url, $data);
	}

	private function executePost($url, $data)
	{
		$data = http_build_query($data, "", "&");
        // header
        $header = array(
            "Content-Type: application/x-www-form-urlencoded",
            "Content-Length: ".strlen($data)
        );
        // request setting
        $context = array(
            "http" => array(
                "method"  => "POST",
                "header"  => implode("\r\n", $header),
                "content" => $data
            ),
            "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
        );
        return file_get_contents($url, false, stream_context_create($context));
	}

	private function getProfile($access_token)
	{
		$url = "https://www.googleapis.com/plus/v1/people/me/openIdConnect";
        // header
        $header = array(
            "Authorization: OAuth " .$access_token
        );
        // request setting
        $context = array(
            "http" => array(
                "method"  => "GET",
                "header"  => implode("\r\n", $header),
            ),
            "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
        );
        return file_get_contents($url, false, stream_context_create($context));
	}
}
