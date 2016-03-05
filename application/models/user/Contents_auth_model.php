<?php

class Contents_auth_model extends MY_Model {

	// テーブルカラムを public フィールドとして定義
	public $user_id;
	public $contents_id;
	public $auth_type;

	/**
	 * データ取得（ID指定）
	 * 結合して取得
	 *
	 * @param  user_id
	 * @return obj
	 */
	public function find_auth_contents($user_id)
	{
		$condition = "contents.id=contents_auth.contents_id";
		$this->db->join("contents", $condition);
		$this->db->where(array($this->_id_column => $user_id, "contents_auth.auth_type >=" => AUTH_TYPE_ABLE));
		return $this->db->get($this->_table)->result();
	}

	/**
	 * データ取得（ID指定 + controller名）
	 * 結合して取得
	 *
	 * @param  user_id
	 * @param  controller_name
	 * @return obj
	 */
	public function confirm_contents_auth($user_id, $controller_name)
	{
		$condition = "contents.id=contents_auth.contents_id";
		$this->db->join("contents", $condition);
		$this->db->where(array($this->_id_column => $user_id, "contents.path" => $controller_name, "contents_auth.auth_type >=" => AUTH_TYPE_ABLE));
		return $this->db->get($this->_table)->row();
	}

	/**
	 * constructor
	 */
	public function __construct()
	{
		// 第1引数でテーブル名を渡す
		//parent::__construct('contents_auth');
		// ID列名がID以外の場合は、第2引数で列名を渡す
		parent::__construct('contents_auth', 'contents_auth.user_id');
	}
}

?>