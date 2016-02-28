<?php

class English_model extends MY_Model {

	// テーブルカラムを public フィールドとして定義
	public $id;
	public $english_category_id;
	public $voice_path;
	public $spell;
	public $japanese;
	public $description;

	/**
	 * constructor
	 */
	public function __construct()
	{
		// 第1引数でテーブル名を渡す
		parent::__construct('english');
		// ID列名がID以外の場合は、第2引数で列名を渡す
		// parent::__construct('user', 'id');
	}
}

?>