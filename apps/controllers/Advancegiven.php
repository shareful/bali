<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Advancegiven extends My_Controller {

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

		$this->load->model('advancegiven_model','advancegiven');		
		$this->load->model('advanceadjust_model','advanceadjust');		
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
		$data['records'] = $this->advancegiven->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/advancegiven/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/advancegiven/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new Advance
		// if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
		// 	show_404();
		// 	return;
		// }

		if($this->input->method(TRUE)=='POST')
		{
			$this->form_validation->set_rules($this->advancegiven->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				
				$record = $this->advancegiven->get_by(array('code'=>$this->input->post('code')));
				if (count($record) > 0)
				{
					echo json_encode(array('success'=>'false','error'=>'Record already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
				}

				$this->db->trans_start();

				$this->assignPostData($this->advancegiven);
				$this->advancegiven->set_value('company_id', $this->session->userdata('company_id'));
				$new_record_id = $this->advancegiven->insert();

				if ($new_record_id) {
					$amount = $this->input->post('amount');

					// Entry Expense Voucher
					$voucher_code = $this->expense->get_new_code();
					$this->expense->set_value('code', $voucher_code);
					$this->expense->set_value('ref_id', $new_record_id);
					$this->expense->set_value('ref_code', $this->input->post('code'));
					$this->expense->set_value('project_id', $this->input->post('project_id'));
					$this->expense->set_value('amount', $amount);
					$this->expense->set_value('exp_type', 'advance');
					$this->expense->set_value('notes', $this->input->post('notes'));
					$this->expense->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('trans_date')), 'MYSQL') );
					$this->expense->set_value('company_id', $this->session->userdata('company_id'));
					$this->expense->insert();					
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_record_id){
					echo json_encode(array('success'=>'true','msg'=>"Advance Payment has been created.", 'id'=>$new_record_id)); exit;
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
			
			$data['record']=$this->advancegiven->get($id);
			
			$data['code'] = $this->advancegiven->get_new_code();
            		
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/advancegiven/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/advancegiven/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}

	public function adjust($id){
		if($this->input->method(TRUE)=='POST'){
			$advance = $this->advancegiven->get($id);
			if (empty($advance)) {
				echo json_encode(array('success'=>'false','error'=>'Advance Record not found.')); exit;
			}

			$bills = $this->input->post('bill_id');
			$adjust_amounts = $this->input->post('adjust_amount');

			// Validation check
			$pending_to_adjust = $advance->amount-$advance->amount_adjusted;
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

				$bill = purchase_bill_cal_info($bill, $pending_to_adjust_left);

				if ($adjust_amount > $bill->payable_due_amt) {
					echo json_encode(array('success'=>'false','error'=>'Validation Error! You assigned greater amount then bill payable due amount on bill #'.$bill->invoice_no.' .')); exit;
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
				$this->purchasepayment->set_value('src_type', 'advance');
				$this->purchasepayment->set_value('notes', ' Adjusted from advance payment, Advance Given Code # '.$advance->code);
				$this->purchasepayment->set_value('trans_date', date('Y-m-d', now()) );
				$this->purchasepayment->insert();

				// insert to bill adjustment history table
				$this->advanceadjust->set_value('advance_id', $id);
				$this->advanceadjust->set_value('bill_id', $bill_id);
				$this->advanceadjust->set_value('trans_date', date('Y-m-d', now()));
				$this->advanceadjust->set_value('amount', $adjust_amount);
				$this->advanceadjust->set_value('trans_type', 'given');
				$this->advanceadjust->insert();				
			}
			
			// Update advance record to set new adjusted amount
			$this->advancegiven->update_adjust_amount($id, $total_adjust_now);

			$this->db->trans_complete();

			if($this->db->trans_status() === TRUE){
				echo json_encode(array('success'=>'true','msg'=>"Advance Adjustment to selected Bills are processed.")); exit;
			} else {
				echo json_encode(array('success'=>'false','error'=>"Advance Adjustment can't be processed. Try again or contact with administrator.")); exit;	
			}

		} else {
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'record';
			$data['advance'] = $this->advancegiven->with('project')->with('supplier')->with('item')->get($id);
			
			if (empty($data['advance'])) {
				exit('Advance record not found');
			}

			$data['bills'] = $this->purchase->get_list_all($data['advance']->project_id, $data['advance']->item_id, $data['advance']->supplier_id, array('paid_amount < payable_amount'), 'code', 'asc');

			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/advancegiven/adjust', $data);
	            return;
	        }

	        $data['content'] = $this->config->item('admin_theme').'/advancegiven/adjust';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	public function ledger($id){
		$data['advance'] = $this->advancegiven->get($id);
		if (!empty($data['advance'])) {
			$records = $this->advanceadjust->get_list_all('given', $id);
			foreach ($records as $key => $record) {
				$bill = $this->purchase->get_one($record->bill_id);
				$record->bill = $bill;
				$records[$key] = $record;
			}

			$data['records'] = $records;
		}
		$this->load->view($this->config->item('admin_theme').'/advancegiven/adjust_ledger', $data);
	}
}