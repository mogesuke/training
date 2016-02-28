<?php

class Contents_auth_model extends MY_Model {

	// テーブルカラムを public フィールドとして定義
	public $user_id;
	public $contents_id;
	public $auth_flg;
	public $name;
	public $path;
	public $class;

	/**
	 * データ取得（ID指定）
	 * 結合して取得
	 *
	 * @param  integer
	 * @return obj
	 */
	public function find_auth_contents($user_id)
	{
		$condition = "contents.id=contents_auth.contents_id";
		$this->db->join("contents", $condition);
		$this->db->where(array($this->_id_column => $user_id));
		return $this->db->get($this->_table)->result();
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