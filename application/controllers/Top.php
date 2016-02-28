<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Top extends MY_Controller {

	/**
	 * Top画面を表示する
	 */
	public function index()
	{
		// googleoauth関連クラスの読み込み
		$this->load->library('google_oauthclass');
		$oauth_url = $this->google_oauthclass->create_oauth_url();

		// 未ログインのときのみ実行
		if (!$this->isLogin()) {
			$data = array(
	               'oauth_url' => $oauth_url
	        );
	    } else {
	    	$id = $this->session->userdata('id');

	    	$this->load->model('user/Contents_auth_model', 'contents_auth');
			$contents = $this->contents_auth->find_auth_contents($id);
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
			$this->load->library('google_oauthclass');
			$token = $this->google_oauthclass->get_token($code);
			// 戻り値をjsonDecode
			$token_json = json_decode($token);

			// 成功したら
			if (!array_key_exists("error", $token_json)) {
				// profile取得
				$profile = $this->google_oauthclass->get_profile($token_json->access_token);
				$profile_json = json_decode($profile);

				// ユーザーテーブル登録
				$this->load->model('user/User_model', 'user');
				$row = $this->user->find_row(array('sub' => $profile_json->sub));
				if (is_null($row)) {
					$this->user->sub = $profile_json->sub;
					$id = $this->user->insert();
				} else {
					$id = $row->id;
				}

				// session設定
				$this->session->set_userdata('name', $profile_json->name);
				$this->session->set_userdata('id', $id);
			} else {
				$this->session->set_flashdata('error', 'ログインに失敗しました。再度試してください。');
			}
		}
		redirect(base_url());
	}
}
