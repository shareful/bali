<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Findbill extends My_Controller {

	function __construct()
	{
		parent::__construct();
		
		if ( ! $this->session->userdata('user_id'))
		{
			redirect('', 'refresh');
		}
		
		// if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
		// 	show_404();
		// 	return;
		// }

		$this->load->model('purchase_model','purchase');		
		$this->load->model('sale_model','sale');		
	}
	
	public function index()
	{
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'findbill';
		
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/findbill/search', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/findbill/search';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function search(){
		$rules = array();
		$rules[] = array( 
				'field' => 'bill_type',
               	'label' => 'Bill Type',
               	'rules' => 'required' 
           	);
		$rules[] = array( 
				'field' => 'bill_no',
               	'label' => 'Bill Number',
               	'rules' => 'required' 
           	);

		$this->form_validation->set_rules($rules);
		
		if ($this->form_validation->run()==FALSE) {
			echo json_encode(array("error"=>$this->form_validation->error_String()));
		} else {
			switch ($this->input->post('bill_type')) {
				case 'sale':
					$bill = $this->sale->findbill($this->input->post('bill_no'));
					if (empty($bill)) {
						echo json_encode(array('success'=>'false','error'=>'Bill Not Found')); exit;
					} else {
						$content = $this->load->view($this->config->item('admin_theme').'/findbill/result', array('bill'=>$bill, 'bill_type'=>'sale'), true);

						echo json_encode(array('success'=>'true','bill_id'=>$bill->id, 'bill_type'=>'sale', 'html'=>$content)); exit;
					}
					break;
						

				case 'purchase':
					$bill = $this->purchase->findbill($this->input->post('bill_no'));
					if (empty($bill)) {
						echo json_encode(array('success'=>'false','error'=>'Bill Not Found')); exit;
					} else {
						$content = $this->load->view($this->config->item('admin_theme').'/findbill/result', array('bill'=>$bill, 'bill_type'=>'purchase'), true);

						echo json_encode(array('success'=>'true','bill_id'=>$bill->id, 'bill_type'=>'purchase', 'html'=>$content)); exit;
					}
					break;
			}
		}		
	}
	

	public function result ($bill_id, $bill_type){
		$data['title'] = $this->config->item('company_name');
		$data['bill_id'] = $bill_id;
		$data['bill_type'] = $bill_type;
		$data['menu'] = 'findbill';
		
		$bill = array();

		switch ($bill_type) {
			case 'sale':
				$bill = $this->sale->get_one($bill_id);
				break;
			case 'purchase':
				$bill = $this->purchase->get_one($bill_id);
				break;
		}

		if (empty($bill)) {
			show_404();
		} 

		$data['bill'] = $bill;

		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/findbill/result', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/sale/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
	}
}