<?php

class Contents_model extends MY_Model {

	// テーブルカラムを public フィールドとして定義
	public $id;
	public $name;
	public $path;
	public $class;

	/**
	 * constructor
	 */
	public function __construct()
	{
		// 第1引数でテーブル名を渡す
		parent::__construct('contents');
		// ID列名がID以外の場合は、第2引数で列名を渡す
		// parent::__construct('contents', 'id');
	}
}

?>