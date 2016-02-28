<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class English extends MY_Controller {

	/**
	 * Top画面を表示する
	 */
	public function index()
	{
		$this->load->model('english/english_category_model', 'english_category');
		$categorys = $this->english_category->find_result();
		$data = array(
			'categorys' => $categorys
		);

		// 画面表示
		$this->load->view('common/html_header');
		$this->load->view('English/index', $data);
		$this->load->view('common/html_footer');
	}

	/**
	 * 出題画面を表示する
	 */
	public function question($english_category_id) {
		$this->load->model('english/english_category_model', 'english_category');
		$this->load->model('english/english_model', 'english');
		
		$english_category = $this->english_category->find_id($english_category_id);
		$english = $this->english->find_result(array('english_category_id' => $english_category_id));

		$data = array(
			'english_category' => $english_category,
			'english' => $english
		);

		// 画面表示
		$this->load->view('common/html_header');
		$this->load->view('English/question', $data);
		$this->load->view('common/html_footer');
	}
}
