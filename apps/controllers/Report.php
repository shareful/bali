<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends My_Controller {

	function __construct()
	{
		parent::__construct();
		
		if ( ! $this->session->userdata('user_id'))
		{
			redirect('', 'refresh');
		}
		
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		$this->load->model('income_model','income');		
		$this->load->model('expense_model','expense');		
		$this->load->model('project_model','project');		
		$this->load->model('item_model','item');		
		$this->load->model('customer_model','customer');		
		$this->load->model('supplier_model','supplier');		
		$this->load->model('purchase_model','purchase');		
		$this->load->model('sale_model','sale');		
	}
	
	public function profit()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		$data['items'] = $this->item->get_list_all();

		$data['income_amount'] = $this->income->get_total();
		$data['expense_amount'] = $this->expense->get_total();
		$data['profit_amount'] = number_format(($data['income_amount'] - $data['expense_amount']), 2, '.', '');

		if(is_ajax()){
			if($this->input->method(TRUE)=='POST'){
				$html = $this->load->view($this->config->item('admin_theme').'/report/profit_data', $data, true);
				echo json_encode(array('success'=>'true','html'=>$html)); exit;
			} else {				
				$this->load->view($this->config->item('admin_theme').'/report/profit', $data);
				return;
			}
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/report/profit';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}	

	public function security_payable(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		$data['suppliers'] = $this->supplier->get_list_all();
		$data['items'] = $this->item->get_list_all();

		$data['bills'] = $this->purchase->get_list_all($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('supplier_id'), array('paid_amount < total_amount', 'security_amount > 0'), 'code', 'asc');

		// echo $this->db->last_query(); exit();

		$null_var = null;
		$null_var2 = null;
		$total_amount = 0;
		foreach ($data['bills'] as $key => $bill) {
			$bill = purchase_bill_cal_info($bill, $null_var, $null_var2);
			$total_amount += $bill->security_due_amt;
			$data['bills'][$key] = $bill;
		}

		$data['total_amount'] = $total_amount;

		if(is_ajax()){
			if($this->input->method(TRUE)=='POST'){
				$html = $this->load->view($this->config->item('admin_theme').'/report/security_payable_data', $data, true);
				echo json_encode(array('success'=>'true','html'=>$html)); exit;
			} else {				
				$this->load->view($this->config->item('admin_theme').'/report/security_payable', $data);
				return;
			}
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/report/security_payable';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
	}

	public function security_receivable(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		$data['customers'] = $this->customer->get_list_all();
		$data['items'] = $this->item->get_list_all();

		$data['bills'] = $this->sale->get_list_all($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('customer_id'), array('received_amount < total_amount', 'security_amount > 0'), 'code', 'asc');

		// echo $this->db->last_query(); exit();

		$null_var = null;
		$null_var2 = null;
		$total_amount = 0;
		foreach ($data['bills'] as $key => $bill) {
			$bill = sale_bill_cal_info($bill, $null_var, $null_var2);
			$total_amount += $bill->security_due_amt;
			$data['bills'][$key] = $bill;
		}

		$data['total_amount'] = $total_amount;

		if(is_ajax()){
			if($this->input->method(TRUE)=='POST'){
				$html = $this->load->view($this->config->item('admin_theme').'/report/security_receivable_data', $data, true);
				echo json_encode(array('success'=>'true','html'=>$html)); exit;
			} else {				
				$this->load->view($this->config->item('admin_theme').'/report/security_receivable', $data);
				return;
			}
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/report/security_receivable';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
	}

	public function payment_payable(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		$data['suppliers'] = $this->supplier->get_list_all();
		$data['items'] = $this->item->get_list_all();

		$data['bills'] = $this->purchase->get_list_all($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('supplier_id'), array('paid_amount < payable_amount'), 'code', 'asc');

		// echo $this->db->last_query(); exit();

		$null_var = null;
		$null_var2 = null;
		$total_amount = 0;
		foreach ($data['bills'] as $key => $bill) {
			$bill = purchase_bill_cal_info($bill, $null_var, $null_var2);
			$total_amount += $bill->payable_due_amt;
			$data['bills'][$key] = $bill;
		}

		$data['total_amount'] = $total_amount;

		if(is_ajax()){
			if($this->input->method(TRUE)=='POST'){
				$html = $this->load->view($this->config->item('admin_theme').'/report/payment_payable_data', $data, true);
				echo json_encode(array('success'=>'true','html'=>$html)); exit;
			} else {				
				$this->load->view($this->config->item('admin_theme').'/report/payment_payable', $data);
				return;
			}
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/report/payment_payable';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
	}

	public function payment_receivable(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		$data['customers'] = $this->customer->get_list_all();
		$data['items'] = $this->item->get_list_all();

		$data['bills'] = $this->sale->get_list_all($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('customer_id'), array('received_amount < receivable_amount'), 'code', 'asc');

		// echo $this->db->last_query(); exit();

		$null_var = null;
		$null_var2 = null;
		$total_amount = 0;
		foreach ($data['bills'] as $key => $bill) {
			$bill = sale_bill_cal_info($bill, $null_var, $null_var2);
			$total_amount += $bill->receivable_due_amt;
			$data['bills'][$key] = $bill;
		}

		$data['total_amount'] = $total_amount;

		if(is_ajax()){
			if($this->input->method(TRUE)=='POST'){
				$html = $this->load->view($this->config->item('admin_theme').'/report/payment_receivable_data', $data, true);
				echo json_encode(array('success'=>'true','html'=>$html)); exit;
			} else {				
				$this->load->view($this->config->item('admin_theme').'/report/payment_receivable', $data);
				return;
			}
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/report/payment_receivable';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
	}

}