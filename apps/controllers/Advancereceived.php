<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Advancereceived extends My_Controller {

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

		$this->load->model('advancetaken_model','advancetaken');	
		$this->load->model('advanceadjust_model','advanceadjust');		
		$this->load->model('sale_model','sale');			
		$this->load->model('salepayment_model','salepayment');		
		$this->load->model('item_model','item');		
		$this->load->model('customer_model','customer');		
		$this->load->model('project_model','project');		
		$this->load->model('income_model','income');		
		$this->load->model('account_model','account');		
		$this->load->model('subaccount_model','subaccount');	
	}

	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['records'] = $this->advancetaken->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/advancetaken/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/advancetaken/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new Advance
		// if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
		// 	show_404();
		// 	return;
		// }

		if($this->input->post())
		{
			$rules = $this->advancetaken->validate;
			$rules[] = array( 
				'field' => 'acc_id',
               	'label' => 'Account',
               	'rules' => 'required' 
           	);

           	if ($this->input->post('acc_id')) {
           		$account = $this->account->get($this->input->post('acc_id'));
           		if ($account->have_sub == 'Yes') {
           			$rules[] = array( 
						'field' => 'sub_acc_id',
		               	'label' => 'Sub Account',
		               	'rules' => 'required' 
		           	);
           		}
           	}

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				
				$record = $this->advancetaken->get_by(array('code'=>$this->input->post('code')));
				if (count($record) > 0)
				{
					echo json_encode(array('success'=>'false','error'=>'Record already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
				}

				$this->db->trans_start();

				$this->assignPostData($this->advancetaken);
				$this->advancetaken->set_value('company_id', $this->session->userdata('company_id'));
				$new_record_id = $this->advancetaken->insert();

				if ($new_record_id) {
					$amount = $this->input->post('amount');

					// Entry Expense Voucher
					$voucher_code = $this->income->get_new_code();
					$this->income->set_value('code', $voucher_code);
					$this->income->set_value('ref_id', $new_record_id);
					$this->income->set_value('ref_code', $this->input->post('code'));
					$this->income->set_value('project_id', $this->input->post('project_id'));
					$this->income->set_value('amount', $amount);
					$this->income->set_value('income_type', 'advance');
					$this->income->set_value('acc_id', $this->input->post('acc_id'));
					$this->income->set_value('sub_acc_id', $this->input->post('sub_acc_id'));
					$this->income->set_value('check_trans_no', $this->input->post('check_trans_no'));
					$this->income->set_value('notes', $this->input->post('notes'));
					$this->income->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('trans_date')), 'MYSQL') );
					$this->income->set_value('company_id', $this->session->userdata('company_id'));
					$this->income->insert();					
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
			$data['customers'] = $this->customer->get_list_all();
			// $data['items'] = $this->item->get_list_all();
			$data['accounts'] = $this->account->get_list_all();
			
			$data['record']=$this->advancetaken->get($id);
			
			$data['code'] = $this->advancetaken->get_new_code();
            		
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/advancetaken/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/advancetaken/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}

	public function adjust($id){
		if($this->input->method(TRUE)=='POST'){
			$advance = $this->advancetaken->get($id);
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

				$bill = $this->sale->get_one($bill_id);
				if (empty($bill)) {
					echo json_encode(array('success'=>'false','error'=>'Bill Not found .')); exit;
				}

				$bill = sale_bill_cal_info($bill, $pending_to_adjust_left);

				if ($adjust_amount > $bill->receivable_due_amt) {
					echo json_encode(array('success'=>'false','error'=>'Validation Error! You assigned greater amount then bill receivable due amount on bill #'.$bill->invoice_no.' .')); exit;
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
				$this->salepayment->set_value('src_type', 'advance');
				$this->salepayment->set_value('notes', ' Adjusted from advance payment, Advance Received Code # '.$advance->code);
				$this->salepayment->set_value('trans_date', date('Y-m-d', now()) );
				$this->salepayment->insert();

				// insert to bill adjustment history table
				$this->advanceadjust->set_value('advance_id', $id);
				$this->advanceadjust->set_value('bill_id', $bill_id);
				$this->advanceadjust->set_value('trans_date', date('Y-m-d', now()));
				$this->advanceadjust->set_value('amount', $adjust_amount);
				$this->advanceadjust->set_value('trans_type', 'taken');
				$this->advanceadjust->insert();				
			}
			
			// Update advance record to set new adjusted amount
			$this->advancetaken->update_adjust_amount($id, $total_adjust_now);

			$this->db->trans_complete();

			if($this->db->trans_status() === TRUE){
				echo json_encode(array('success'=>'true','msg'=>"Advance Adjustment to selected Bills are processed.")); exit;
			} else {
				echo json_encode(array('success'=>'false','error'=>"Advance Adjustment can't be processed. Try again or contact with administrator.")); exit;	
			}

		} else {
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'record';
			$data['advance'] = $this->advancetaken->with('project')->with('customer')->with('item')->get($id);
			
			if (empty($data['advance'])) {
				exit('Advance record not found');
			}

			$data['bills'] = $this->sale->get_list_all($data['advance']->project_id, $data['advance']->item_id, $data['advance']->customer_id, array('received_amount < receivable_amount'), 'code', 'asc');

			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/advancetaken/adjust', $data);
	            return;
	        }

	        $data['content'] = $this->config->item('admin_theme').'/advancetaken/adjust';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	public function ledger($id){
		$data['advance'] = $this->advancetaken->get($id);
		if (!empty($data['advance'])) {
			$records = $this->advanceadjust->get_list_all('taken', $id);
			foreach ($records as $key => $record) {
				$bill = $this->sale->get_one($record->bill_id);
				$record->bill = $bill;
				$records[$key] = $record;
			}

			$data['records'] = $records;
		}
		$this->load->view($this->config->item('admin_theme').'/advancetaken/adjust_ledger', $data);
	}

	public function delete(){
		// ONly Super admin can dele customer
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				$advance_payment = $this->advancetaken->get($id);
				if (empty($advance_payment)) {
					echo json_encode(array('success'=>'false','error'=>"Advance Received Payment not found.")); exit;
				}
				
				if ($advance_payment->amount_adjusted > 0) {
					echo json_encode(array('success'=>'false','error'=>"You can't delete this Advance Received Payment. Because {$advance_payment->amount_adjusted} tk already adjusted to bill.")); exit;
				}

				if($this->advancetaken->delete_payment($id)){		
					echo json_encode(array('success'=>'true','msg'=>"Advance Received has been Deleted.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Advance Received.")); exit;
				}
			} 

		}		
	}
}