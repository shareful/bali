<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Securitygiven extends My_Controller {

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

		$this->load->model('securityadjust_model','securityadjust');		
		$this->load->model('securitygiven_model','securitygiven');		
		$this->load->model('purchase_model','purchase');		
		$this->load->model('purchasepayment_model','purchasepayment');		
		$this->load->model('item_model','item');		
		$this->load->model('supplier_model','supplier');		
		$this->load->model('project_model','project');		
		$this->load->model('expense_model','expense');		
	}

	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['records'] = $this->securitygiven->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/securitygiven/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/securitygiven/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new Security
		// if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
		// 	show_404();
		// 	return;
		// }

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->securitygiven->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				
				$record = $this->securitygiven->get_by(array('code'=>$this->input->post('code')));
				if (count($record) > 0)
				{
					echo json_encode(array('success'=>'false','error'=>'Record already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
				}

				$this->db->trans_start();

				$this->assignPostData($this->securitygiven);
				$this->securitygiven->set_value('company_id', $this->session->userdata('company_id'));
				$new_record_id = $this->securitygiven->insert();

				if ($new_record_id) {
					$amount = $this->input->post('amount');

					// Entry Expense Voucher
					$voucher_code = $this->expense->get_new_code();
					$this->expense->set_value('code', $voucher_code);
					$this->expense->set_value('ref_id', $new_record_id);
					$this->expense->set_value('ref_code', $this->input->post('code'));
					$this->expense->set_value('project_id', $this->input->post('project_id'));
					$this->expense->set_value('amount', $amount);
					$this->expense->set_value('exp_type', 'security');
					$this->expense->set_value('notes', $this->input->post('notes'));
					$this->expense->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('trans_date')), 'MYSQL') );
					$this->expense->set_value('company_id', $this->session->userdata('company_id'));
					$this->expense->insert();					
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_record_id){
					echo json_encode(array('success'=>'true','msg'=>"Security Payment has been created.", 'id'=>$new_record_id)); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be created. Try again or contact with administrator.")); exit;	
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'record';
			$data['projects'] = $this->project->get_list_all();
			$data['suppliers'] = $this->supplier->get_list_all();
			$data['items'] = $this->item->get_list_all();
			
			$data['record']=$this->securitygiven->get($id);
			
			$data['code'] = $this->securitygiven->get_new_code();
            		
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/securitygiven/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/securitygiven/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}

	public function adjust($id){
		if($this->input->method(TRUE)=='POST'){
			$security = $this->securitygiven->get($id);
			if (empty($security)) {
				echo json_encode(array('success'=>'false','error'=>'Security Record not found.')); exit;
			}

			$bills = $this->input->post('bill_id');
			$adjust_amounts = $this->input->post('adjust_amount');

			// Validation check
			$pending_to_adjust = $security->amount-$security->amount_adjusted;
			$pending_to_adjust_left = $pending_to_adjust;
			$total_adjust_now = 0;
			foreach ($bills as $key => $bill_id) {
				$adjust_amount = (float)$adjust_amounts[$key];

				if (!$adjust_amount) {
					continue;
				}

				if ($adjust_amount < 0) {
					continue;
				}

				$bill = $this->purchase->get_one($bill_id);
				if (empty($bill)) {
					echo json_encode(array('success'=>'false','error'=>'Bill Not found .')); exit;
				}

				$null_var = null;
				$bill = purchase_bill_cal_info($bill, $null_var, $pending_to_adjust_left);

				if ($adjust_amount > $bill->security_due_amt) {
					echo json_encode(array('success'=>'false','error'=>'Validation Error! You assigned greater amount then bill security due amount on bill #'.$bill->invoice_no.' .')); exit;
				}
				$total_adjust_now += $adjust_amount;
			}

			if ($total_adjust_now <= 0) {
				echo json_encode(array('success'=>'false','error'=>'Enter amount to adjust .')); exit;
			}

			if ($total_adjust_now > $pending_to_adjust ) {
				$pending_to_adjust = number_format($pending_to_adjust, 2, '.', '');
				$total_adjust_now = number_format($total_adjust_now, 2, '.', '');
				echo json_encode(array('success'=>'false','error'=>'Validation Error! You can\'t Adjust of '.$total_adjust_now.' tk then pending adjust amount of '.$pending_to_adjust.' tk')); exit;
			}

			// Validation Passed. Now Process Adjustment
			$this->db->trans_start();

			foreach ($bills as $key => $bill_id) {
				$adjust_amount = (float)$adjust_amounts[$key];

				if (!$adjust_amount) {
					continue;
				}

				if ($adjust_amount < 0) {
					continue;
				}

				// Get Bill Info
				$bill = $this->purchase->get_one($bill_id);

				// Update Bill Payment
				$update_data = array();
				$update_data['paid_amount'] = $bill->paid_amount + $adjust_amount;
				$this->purchase->update($bill_id, $update_data, true );

				// Insert Payment history
				$this->purchasepayment->set_value('bill_id', $bill_id);
				$this->purchasepayment->set_value('amount', $adjust_amount);
				$this->purchasepayment->set_value('src_type', 'security');
				$this->purchasepayment->set_value('notes', ' Adjusted from security payment, Security Given Code # '.$security->code);
				$this->purchasepayment->set_value('trans_date', date('Y-m-d', now()) );
				$this->purchasepayment->insert();

				// insert to bill adjustment history table
				$this->securityadjust->set_value('security_id', $id);
				$this->securityadjust->set_value('bill_id', $bill_id);
				$this->securityadjust->set_value('trans_date', date('Y-m-d', now()));
				$this->securityadjust->set_value('amount', $adjust_amount);
				$this->securityadjust->set_value('trans_type', 'given');
				$this->securityadjust->insert();				
			}
			
			// Update security record to set new adjusted amount
			$this->securitygiven->update_adjust_amount($id, $total_adjust_now);

			$this->db->trans_complete();

			if($this->db->trans_status() === TRUE){
				echo json_encode(array('success'=>'true','msg'=>"Security Adjustment to selected Bills are processed.")); exit;
			} else {
				echo json_encode(array('success'=>'false','error'=>"Security Adjustment can't be processed. Try again or contact with administrator.")); exit;	
			}

		} else {
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'record';
			$data['security'] = $this->securitygiven->with('project')->with('supplier')->with('item')->get($id);
			// print_r($data['security']);
			if (empty($data['security'])) {
				exit('Security record not found');
			}

			// exit();

			$data['bills'] = $this->purchase->get_list_all($data['security']->project_id, $data['security']->item_id, $data['security']->supplier_id, array('paid_amount < total_amount', 'security_amount > 0', 'paid_amount >= payable_amount'), 'code', 'asc');

			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/securitygiven/adjust', $data);
	            return;
	        }

	        $data['content'] = $this->config->item('admin_theme').'/securitygiven/adjust';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	public function ledger($id){
		$data['security'] = $this->securitygiven->get($id);
		if (!empty($data['security'])) {
			$records = $this->securityadjust->get_list_all('given', $id);
			foreach ($records as $key => $record) {
				$bill = $this->purchase->get_one($record->bill_id);
				$record->bill = $bill;
				$records[$key] = $record;
			}

			$data['records'] = $records;
		}
		$this->load->view($this->config->item('admin_theme').'/securitygiven/adjust_ledger', $data);
	}

	public function delete(){
		// ONly Super admin can dele customer
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				$sec_payment = $this->securitygiven->get($id);
				if (empty($sec_payment)) {
					echo json_encode(array('success'=>'false','error'=>"Security Given Payment not found.")); exit;
				}
				
				if ($sec_payment->amount_adjusted > 0) {
					echo json_encode(array('success'=>'false','error'=>"You can't delete this Security Given Payment. Because {$sec_payment->amount_adjusted} tk already adjusted to bill.")); exit;
				}

				if($this->securitygiven->delete_payment($id)){		
					echo json_encode(array('success'=>'true','msg'=>"Security Given has been Deleted.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Security Given.")); exit;
				}
			} 

		}		
	}
}