<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Top extends MY_Controller {

	/**
	 * Top画面を表示する
	 */
	public function index()
	{
		// googleoauth関連クラスの読み込み
		$this->load->library('google_oauth_class');
		$oauth_url = $this->google_oauth_class->create_oauth_url();

		// 未ログインのときのみ実行
		if (!$this->isLogin()) {
			$data = array(
	               'oauth_url' => $oauth_url
	        );
	    } else {
	    	$user_id = $this->session->userdata(SESSION_USER_ID);

	    	$this->load->model('user/Contents_auth_model', 'contents_auth');
			$contents = $this->contents_auth->find_auth_contents($user_id);
			$data = array(
					'contents' => $contents
			);
	    }

		// 画面表示
		$this->load->view('common/html_header');
		$this->load->view('top/index', $data);
		$this->load->view('common/html_footer');
	}

	/**
	 * ログアウトする
	 */
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url());
	}

	/**
	 * GoogleOauthからのコールバック用
	 */
	public function callback()
	{
		// 未ログインのときのみ実行
		if (!$this->isLogin()) {
			// codeを取得
			$code = $this->input->get('code');
			// codeを使ってtoken取得
			$this->load->library('google_oauth_class');
			$token = $this->google_oauth_class->get_token($code);
			// 戻り値をjsonDecode
			$token_json = json_decode($token);

			// 成功したら
			if (!array_key_exists("error", $token_json)) {
				// profile取得
				$profile = $this->google_oauth_class->get_profile($token_json->access_token);
				$profile_json = json_decode($profile);

				// ユーザーテーブル登録
				$this->load->library('user_manage_class');
				$user_id = $this->user_manage_class->confirm_and_regist_user($profile_json);

				// session設定
				$this->session->set_userdata(SESSION_NAME, $profile_json->name);
				$this->session->set_userdata(SESSION_USER_ID, $user_id);
			} else {
				$this->session->set_flashdata('error', 'ログインに失敗しました。再度試してください。');
			}
		}
		redirect(base_url());
	}
}
