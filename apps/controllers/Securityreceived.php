<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Securityreceived extends My_Controller {

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

		$this->load->model('securitytaken_model','securitytaken');		
		$this->load->model('sale_model','sale');		
		$this->load->model('salepayment_model','salepayment');		
		$this->load->model('securityadjust_model','securityadjust');		
		$this->load->model('item_model','item');		
		$this->load->model('customer_model','customer');		
		$this->load->model('project_model','project');		
		$this->load->model('income_model','income');		
	}

	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['records'] = $this->securitytaken->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/securitytaken/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/securitytaken/list';
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
			$this->form_validation->set_rules($this->securitytaken->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				
				$record = $this->securitytaken->get_by(array('code'=>$this->input->post('code')));
				if (count($record) > 0)
				{
					echo json_encode(array('success'=>'false','error'=>'Record already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
				}

				$this->db->trans_start();

				$this->assignPostData($this->securitytaken);
				$this->securitytaken->set_value('company_id', $this->session->userdata('company_id'));
				$new_record_id = $this->securitytaken->insert();

				if ($new_record_id) {
					$amount = $this->input->post('amount');

					// Entry Expense Voucher
					$voucher_code = $this->income->get_new_code();
					$this->income->set_value('code', $voucher_code);
					$this->income->set_value('ref_id', $new_record_id);
					$this->income->set_value('ref_code', $this->input->post('code'));
					$this->income->set_value('project_id', $this->input->post('project_id'));
					$this->income->set_value('amount', $amount);
					$this->income->set_value('income_type', 'security');
					$this->income->set_value('notes', $this->input->post('notes'));
					$this->income->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('trans_date')), 'MYSQL') );
					$this->income->set_value('company_id', $this->session->userdata('company_id'));
					$this->income->insert();					
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
			$data['customers'] = $this->customer->get_list_all();
			$data['items'] = $this->item->get_list_all();
			
			$data['record']=$this->securitytaken->get($id);
			
			$data['code'] = $this->securitytaken->get_new_code();
            		
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/securitytaken/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/securitytaken/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}


	public function adjust($id){
		if($this->input->method(TRUE)=='POST'){
			$security = $this->securitytaken->get($id);
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

				$bill = $this->sale->get_one($bill_id);
				if (empty($bill)) {
					echo json_encode(array('success'=>'false','error'=>'Bill Not found .')); exit;
				}

				$null_var = null;
				$bill = sale_bill_cal_info($bill, $null_var, $pending_to_adjust_left);

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
				$bill = $this->sale->get_one($bill_id);

				// Update Bill Payment
				$update_data = array();
				$update_data['received_amount'] = $bill->received_amount + $adjust_amount;
				$this->sale->update($bill_id, $update_data, true );

				// Insert Payment history
				$this->salepayment->set_value('bill_id', $bill_id);
				$this->salepayment->set_value('amount', $adjust_amount);
				$this->salepayment->set_value('src_type', 'security');
				$this->salepayment->set_value('notes', ' Adjusted from security payment, Security Received Code # '.$security->code);
				$this->salepayment->set_value('trans_date', date('Y-m-d', now()) );
				$this->salepayment->insert();

				// insert to bill adjustment history table
				$this->securityadjust->set_value('security_id', $id);
				$this->securityadjust->set_value('bill_id', $bill_id);
				$this->securityadjust->set_value('trans_date', date('Y-m-d', now()));
				$this->securityadjust->set_value('amount', $adjust_amount);
				$this->securityadjust->set_value('trans_type', 'taken');
				$this->securityadjust->insert();				
			}
			
			// Update security record to set new adjusted amount
			$this->securitytaken->update_adjust_amount($id, $total_adjust_now);

			$this->db->trans_complete();

			if($this->db->trans_status() === TRUE){
				echo json_encode(array('success'=>'true','msg'=>"Security Adjustment to selected Bills are processed.")); exit;
			} else {
				echo json_encode(array('success'=>'false','error'=>"Security Adjustment can't be processed. Try again or contact with administrator.")); exit;	
			}

		} else {
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'record';
			$data['security'] = $this->securitytaken->with('project')->with('customer')->with('item')->get($id);
			// print_r($data['security']);
			if (empty($data['security'])) {
				exit('Security record not found');
			}

			// exit();

			$data['bills'] = $this->sale->get_list_all($data['security']->project_id, $data['security']->item_id, $data['security']->customer_id, array('received_amount < total_amount', 'security_amount > 0', 'received_amount >= receivable_amount'), 'code', 'asc');

			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/securitytaken/adjust', $data);
	            return;
	        }

	        $data['content'] = $this->config->item('admin_theme').'/securitytaken/adjust';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	public function ledger($id){
		$data['security'] = $this->securitytaken->get($id);
		if (!empty($data['security'])) {
			$records = $this->securityadjust->get_list_all('taken', $id);
			foreach ($records as $key => $record) {
				$bill = $this->sale->get_one($record->bill_id);
				$record->bill = $bill;
				$records[$key] = $record;
			}

			$data['records'] = $records;
		}
		$this->load->view($this->config->item('admin_theme').'/securitytaken/adjust_ledger', $data);
	}

	public function delete(){
		// ONly Super admin can dele customer
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				$sec_payment = $this->securitytaken->get($id);
				if (empty($sec_payment)) {
					echo json_encode(array('success'=>'false','error'=>"Security Received Payment not found.")); exit;
				}
				
				if ($sec_payment->amount_adjusted > 0) {
					echo json_encode(array('success'=>'false','error'=>"You can't delete this Security Received Payment. Because {$sec_payment->amount_adjusted} tk already adjusted to bill.")); exit;
				}

				if($this->securitytaken->delete_payment($id)){		
					echo json_encode(array('success'=>'true','msg'=>"Security Received has been Deleted.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Security Received.")); exit;
				}
			} 

		}
		
	}	
}