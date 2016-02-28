<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * MY_Model
 * @since	Version 0.0.1
 * */
class MY_Model extends CI_Model {

	// table name
	protected $_table;

	// id_column
	protected $_id_column;

	/**
	 * constructor
	 *
	 * @return	void
	 */
	public function __construct($table = NULL, $id_column = 'id')
	{
		parent::__construct();
		$this->load->database();
		$this->_table = $table;
		$this->_id_column = $id_column;
	}

	/**
	 * 登録処理
	 *
	 * @param	array
	 * @return	mixed
	 */
	public function insert($data = NULL)
	{
		$exectime = $this->_exectime();
		if ($data === NULL) {
			$data = $this;
		}
		$this->db->set(array('created_date' => $exectime, 'updated_date' => $exectime));
		if ($this->db->insert($this->_table, $data) === FALSE) {
			return FALSE;
		}
		return $this->db->insert_id();
	}

	/**
	 * 更新処理
	 *
	 * @param	integer
	 * @param	array
	 * @return	mixed
	 */
	public function update($id, $data = NULL)
	{
		$exectime = $this->_exectime();
		if ($data === NULL) {
			$data = $this;
		}
		$this->db->set(array('updated_date' => $exectime));
		return $this->db->update($this->_table, $data, array($this->_id_column => $id));
	}

	/**
	 * 削除処理
	 *
	 * @param	integer
	 * @return	bool
	 */
	public function delete($id)
	{
		return $this->db->delete($this->_table, array($this->_id_column => $id));
	}

	/**
	 * データ取得（一覧）
	 *
	 * @param	array
	 * @param	string
	 * @param	string
	 * @return	array
	 */
	public function find_result($where = NULL, $sort = NULL, $order = 'asc')
	{
		if ($where !== NULL)
		{
			$this->db->where($where);
		}
		if ($sort !== NULL)
		{
			$this->db->order_by($sort, $order);
		}
		return $this->db->get($this->_table)->result();
	}

	/**
	 * データ取得（1件）
	 *
	 * @param	array
	 * @return obj
	 */
	public function find_row($where)
	{
		return $this->db->where($where)->get($this->_table)->row();
	}

	/**
	 * データ取得（ID指定）
	 *
	 * @param  integer
	 * @return obj
	 */
	public function find_id($id)
	{
		return $this->find_row(array($this->_id_column => $id));
	}

	/**
	 * 件数取得
	 *
	 * @param	array
	 * @return integer
	 */
	public function count($where = NULL)
	{
		if ($where !== NULL)
		{
			$this->db->where($where);
		}
		return $this->db->count_all_results($this->_table);
	}

	/**
	 * 実行日時取得
	 *
	 * @return string
	 */
	private function _exectime()
	{
		return date('Y-m-d H:i:s');
	}
}