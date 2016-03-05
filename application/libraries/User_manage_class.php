<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class User_manage_class {

	/**
	 * googleOauthの戻り値を使ってユーザーテーブルに登録する。
	 */
	public function confirm_and_regist_user($profile)
	{
		$CI =& get_instance();
		$CI->load->model('user/User_model', 'user');
		$user = $CI->user->find_row(array('sub' => $profile->sub));
		if (is_null($user)) {
			$CI->user->sub = $profile->sub;
			$CI->user->mail = $profile->email;
			$CI->user->user_type = USER_TYPE_NORMAL;
			$user_id = $CI->user->insert();
			$this->regist_user_contents($user_id);
		} else {
			$user_id = $user->id;
		}
		return $user_id;
	}

	/**
	 * 指定されたユーザーIDにコンテンツの利用権限を付ける。
	 *
	 */
	private function regist_user_contents($user_id)
	{
		// contentsの権限追加
		$CI =& get_instance();
		$CI->load->model('user/Contents_model', 'contents');
		$CI->load->model('user/Contents_auth_model', 'contents_auth');
		$contents = $CI->contents->find_result(array('auth_grant_type' => AUTH_GRANT_TYPE_AUTO));
		foreach ($contents as $content) {
			$CI->contents_auth->user_id = $user_id;
			$CI->contents_auth->contents_id = $content->id;
			$CI->contents_auth->auth_type = AUTH_TYPE_ABLE;
			$CI->contents_auth->insert();
		}
	}
}