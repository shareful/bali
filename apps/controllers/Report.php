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
		$this->load->model('advancetaken_model','advancetaken');	
		$this->load->model('securitytaken_model','securitytaken');		
		$this->load->model('advancegiven_model','advancegiven');	
		$this->load->model('securitygiven_model','securitygiven');		
		$this->load->model('account_model','account');		
		$this->load->model('subaccount_model','subaccount');		
	}
	
	public function summary(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'report';
		$data['projects'] = $this->project->get_list_all();

		$params = array();

		if ($this->input->post_get('project_id')) {
			$params['project_id'] = $this->input->post_get('project_id');
		}

		/*if ($this->input->post_get('item_id')) {
			$params['item_id'] = $this->input->post_get('item_id');
		}*/

		if ($this->input->post_get('from_date')) {
			$params['from_date'] = $this->input->post_get('from_date');
		}

		if ($this->input->post_get('to_date')) {
			if (isset($params['from_date'])) {
				$from_date = custom_standard_date(date_human_to_unix($params['from_date']), 'MYSQL');
				$to_date = custom_standard_date(date_human_to_unix($this->input->post_get('to_date')), 'MYSQL');
				if (strtotime($to_date) > strtotime($from_date)) {
					$params['to_date'] = $this->input->post_get('to_date');
				}
			} else {
				$params['to_date'] = $this->input->post_get('to_date');
			}
		}
				

		// Expenses
		$common_where = array();
		if (isset($params['project_id'])) {
			$common_where['project_id'] = $params['project_id'];
		}
		
		/*if (isset($params['item_id'])) {
			$common_where['item_id'] = $params['item_id'];
		}*/

		if (isset($params['from_date'])) {
			$from_date = custom_standard_date(date_human_to_unix($params['from_date']), 'MYSQL');
			$common_where['trans_date >='] = $from_date;
		}

		if (isset($params['to_date'])) {
			$to_date = custom_standard_date(date_human_to_unix($params['to_date']), 'MYSQL');
			$common_where['trans_date <='] = $to_date.' 23:59:59';
		}

		// other expense
		$where = $common_where;
		$where['exp_type'] = 'other';
		$data['other_expense'] = $this->expense->get_custom_total($where);		
		// Bill Paid
		$where = $common_where;
		$where['exp_type'] = 'purchase';
		$data['bill_expense'] = $this->expense->get_custom_total($where);		
		// Advance Paid
		$where = $common_where;
		$where['exp_type'] = 'advance';
		$data['advance_expense'] = $this->expense->get_custom_total($where);		
		// Security Paid
		$where = $common_where;
		$where['exp_type'] = 'security';
		$data['security_expense'] = $this->expense->get_custom_total($where);		
		// Total Expnese
		$data['total_expense'] = $data['other_expense'] + $data['bill_expense'] + $data['advance_expense'] + $data['security_expense'];
		

		// Bill Payment Received
		$where = $common_where;
		$where['income_type'] = 'sale';
		$data['bill_income'] = $this->income->get_custom_total($where);
		// Advance Payment Received
		$where = $common_where;
		$where['income_type'] = 'advance';
		$data['advance_income'] = $this->income->get_custom_total($where);
		// Security Payment Received
		$where = $common_where;
		$where['income_type'] = 'security';
		$data['security_income'] = $this->income->get_custom_total($where);
		// Investment Received
		$where = $common_where;
		$where['income_type'] = 'invest';
		$data['invest_income'] = $this->income->get_custom_total($where);
		// Other Income Received
		$where = $common_where;
		$where['income_type'] = 'other';
		$data['other_income'] = $this->income->get_custom_total($where);
		// Total Income
		$data['total_income'] = $data['bill_income'] + $data['advance_income'] + $data['security_income'] + $data['invest_income'] + $data['other_income'];


		// Bill Payable
		$where = array();
		if (isset($params['project_id'])) {
			$where['project_id'] = $params['project_id'];
		}
		
		/*if (isset($params['item_id'])) {
			$where['item_id'] = $params['item_id'];
		}*/

		if (isset($params['from_date'])) {
			$from_date = custom_standard_date(date_human_to_unix($params['from_date']), 'MYSQL');
			$where['bill_date >='] = $from_date;
		}

		if (isset($params['to_date'])) {
			$to_date = custom_standard_date(date_human_to_unix($params['to_date']), 'MYSQL');
			$where['bill_date <='] = $to_date.' 23:59:59';
		}
		$data['bill_payable'] = $this->purchase->get_bill_payable($where);
		$data['total_payable'] = $this->purchase->get_payable($where);
		$data['security_payable'] = $data['total_payable'] - $data['bill_payable'];

		// Bill Receivable
		$data['bill_receivable'] = $this->sale->get_bill_receivable($where);
		$data['total_receivable'] = $this->sale->get_receivable($where);
		$data['security_receivable'] = $data['total_receivable'] - $data['bill_receivable'];




		// Accounts Balance
		$data['accounts'] = $this->account->get_list_all();
		$where = array();
		$opening_balance_where = array();
		
		
		/*if (isset($params['item_id'])) {
			$where['item_id'] = $params['item_id'];
			$opening_balance_where['item_id'] = $params['item_id'];
		}	*/	

		if (isset($params['from_date'])) {
			$from_date = custom_standard_date(date_human_to_unix($params['from_date']), 'MYSQL');
			$where[] = "trans_date >= '".$from_date."'";
			$opening_balance_where[] = "trans_date < '{$from_date}'";
		}

		if (isset($params['project_id'])) {
			$where[] = "project_id = '".$params['project_id']."'";
			if (!empty($opening_balance_where)) {
				$opening_balance_where[] = "project_id = '".$params['project_id']."'";
			}
		}

		if (isset($params['to_date'])) {
			$to_date = custom_standard_date(date_human_to_unix($params['to_date']), 'MYSQL');
			$where[] = "trans_date <= '".$to_date." 23:59:59'";
		}

		if (!empty($opening_balance_where)) {
			$data['opening_balance'] = $this->account->get_custom_balance($opening_balance_where);
		}

		foreach ($data['accounts'] as $key => $account) {
			$account->balance = $this->account->get_custom_balance(array_merge($where, array("acc_id = '".$account->acc_id."'")));
			if ($account->have_sub=='Yes') {
				$account->subaccounts = $this->subaccount->get_list_all($account->acc_id);
				foreach ($account->subaccounts as $skey => $subaccount) {
					$subaccount->balance = $this->account->get_custom_balance(array_merge($where, array("sub_acc_id = '".$subaccount->sub_acc_id."'")));
					$account->subaccounts[$skey] = $subaccount;
				}
			}
			$data['accounts'][$key] = $account;
		}

		$where = array();
		if (isset($params['project_id'])) {
			$where[] = "project_id = '".$params['project_id']."'";
		}
		if (isset($params['to_date'])) {
			$to_date = custom_standard_date(date_human_to_unix($params['to_date']), 'MYSQL');
			$where[] = "trans_date <= '".$to_date." 23:59:59'";
		}
		$data['net_closing_balance'] = $this->account->get_custom_balance($where);




		$data['params'] = $params;
		if(is_ajax()){
            $this->load->view($this->config->item('admin_theme').'/report/summary', $data);
            return;
        }
        $data['content'] = $this->config->item('admin_theme').'/report/summary';
        $this->load->view($this->config->item('admin_theme').'/template', $data);
	
	}

	public function profit()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		// $data['items'] = $this->item->get_list_all();

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
		// $data['items'] = $this->item->get_list_all();

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
		// $data['items'] = $this->item->get_list_all();

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
		// $data['items'] = $this->item->get_list_all();

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
		// $data['items'] = $this->item->get_list_all();

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

	function customer_balance(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['customers'] = $this->customer->get_list_all();
		$data['projects'] = $this->project->get_list_all();
		// $data['items'] = $this->item->get_list_all();
		
		if($this->input->post('customer_id')){
			$data['customer_id'] = $this->input->post('customer_id');

			// Total Receivable
			$bill_amount = array();
			$bill_amount['total'] = $this->sale->get_total_bill($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('customer_id'));
			$bill_amount['receivable'] = $this->sale->get_total_receivable($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('customer_id'));
			$bill_amount['security'] = $this->sale->get_total_security_receivable($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('customer_id'));

			// Amount Received
			$received_amount = array();
			$received_amount['sale'] = $this->income->get_sale_total($this->input->post('customer_id'));
			$received_amount['advance'] = $this->advancetaken->get_total_taken($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('customer_id'));
			$received_amount['security'] = $this->securitytaken->get_total_taken($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('customer_id'));

			// Total Amount Received
			$received_amount['total'] = $received_amount['sale']+$received_amount['advance']+$received_amount['security'];

			$data['bill_amount'] = $bill_amount;
			$data['received_amount'] = $received_amount;

			// balance
			$data['balance'] = $bill_amount['total'] - $received_amount['total'];

			// Output
			$html = $this->load->view($this->config->item('admin_theme').'/report/customer_balance_data', $data, true);
			echo json_encode(array('success'=>'true','html'=>$html)); exit;
		} 

		if(is_ajax()){							
			$this->load->view($this->config->item('admin_theme').'/report/customer_balance', $data);
			return;		
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/report/customer_balance';
		$this->load->view($this->config->item('admin_theme').'/template', $data);		
	}

	function supplier_balance(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['suppliers'] = $this->supplier->get_list_all();
		$data['projects'] = $this->project->get_list_all();
		// $data['items'] = $this->item->get_list_all();
		
		if($this->input->post('supplier_id')){
			$data['supplier_id'] = $this->input->post('supplier_id');

			// Total Receivable
			$bill_amount = array();
			$bill_amount['total'] = $this->purchase->get_total_bill($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('supplier_id'));
			$bill_amount['payable'] = $this->purchase->get_total_payable($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('supplier_id'));
			$bill_amount['security'] = $this->purchase->get_total_security_payable($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('supplier_id'));

			// Amount Received
			$paid_amount = array();
			$paid_amount['purchase'] = $this->expense->get_purchase_total($this->input->post('supplier_id'));
			$paid_amount['advance'] = $this->advancegiven->get_total_given($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('supplier_id'));
			$paid_amount['security'] = $this->securitygiven->get_total_given($this->input->post('project_id'), $this->input->post('item_id'), $this->input->post('supplier_id'));

			// Total Amount Received
			$paid_amount['total'] = $paid_amount['purchase']+$paid_amount['advance']+$paid_amount['security'];

			$data['bill_amount'] = $bill_amount;
			$data['paid_amount'] = $paid_amount;

			// balance
			$data['balance'] = $bill_amount['total'] - $paid_amount['total'];

			// Output
			$html = $this->load->view($this->config->item('admin_theme').'/report/supplier_balance_data', $data, true);
			echo json_encode(array('success'=>'true','html'=>$html)); exit;
		} 

		if(is_ajax()){							
			$this->load->view($this->config->item('admin_theme').'/report/supplier_balance', $data);
			return;		
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/report/supplier_balance';
		$this->load->view($this->config->item('admin_theme').'/template', $data);		
	}

	public function account_statement(){
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'report';

		if($this->input->post_get('acc_id')!=''){
			$data['account'] = $this->account->get($this->input->post_get('acc_id'));
			
			$where = array();
			$opening_balance_where = array();

			if ($this->input->post_get('from_date')) {
				$from_date = custom_standard_date(date_human_to_unix($this->input->post_get('from_date')), 'MYSQL');
				$where[] = "trans_date >= '{$from_date}'";	
				$opening_balance_where[] = "trans_date < '{$from_date}'";
				$data['from_date'] = $from_date;
			}

			if ($this->input->post_get('to_date')) {
				$to_date = custom_standard_date(date_human_to_unix($this->input->post_get('to_date')), 'MYSQL');
				$where[] = "trans_date <= '{$to_date} 23:59:59'";		
				$data['to_date'] = $to_date;	
			}

			$where[] = "acc_id = '".$this->input->post_get('acc_id')."'";
			if (!empty($opening_balance_where)) {
				$opening_balance_where[] = "acc_id = '".$this->input->post_get('acc_id')."'";
			}

			if ($this->input->post_get('sub_acc_id')!='') {
				$data['subaccount'] = $this->subaccount->get($this->input->post_get('sub_acc_id'));
				$where[] = "sub_acc_id = '".$this->input->post_get('sub_acc_id')."'";

				if (!empty($opening_balance_where)) {
					$opening_balance_where[] = "sub_acc_id = '".$this->input->post_get('sub_acc_id')."'";
				}
			}

			$data['rows'] = $this->account->get_statement($where);
			if (!empty($opening_balance_where)) {
				$data['opening_balance'] = $this->account->get_custom_balance($opening_balance_where);
			}
			// echo $this->db->last_query();exit();
			// echo "<pre>"; echo count($data['rows']); exit();

			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/report/account_statement_data', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/report/account_statement_data';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		} else {
			$data['accounts'] = $this->account->get_list_all();
				
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/report/account_statement', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/report/account_statement';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}
}