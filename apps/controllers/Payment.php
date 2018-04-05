<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends My_Controller {

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
		$this->load->model('purchasepayment_model','purchasepayment');		
		$this->load->model('sale_model','sale');		
		$this->load->model('salepayment_model','salepayment');		
		$this->load->model('item_model','item');		
		$this->load->model('itembilled_model','itembilled');		
		$this->load->model('itemstock_model','itemstock');		
		$this->load->model('customer_model','customer');		
		$this->load->model('project_model','project');		
		$this->load->model('income_model','income');		
		$this->load->model('expense_model','expense');		
	}
	
	public function index($project_id, $item_id)
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['bills'] = $this->payment->get_list_all($project_id, $item_id);
		
		$data['project_id'] = $project_id;
		// $data['customer_id'] = $customer_id;
		$data['item_id'] = $item_id;
		$data['item'] = $this->item->get($item_id);
		$data['project'] = $this->project->get($project_id);

		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/payment/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/payment/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);		
	}

	/**
	 * Make Payment of a purchase bill
	 * @access public
	 * @param integer	 
	 */
	public function make($bill_id){
		if($this->input->method(TRUE)=='POST'){
			$this->form_validation->set_rules($this->purchasepayment->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				if ($bill_id != $this->input->post('bill_id')) {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be created. Bill Not Found.")); exit;
				}

				$bill = $this->purchase->get_one($bill_id);
				if (empty($bill)) {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be created. Bill Not Found.")); exit;
				}

				$this->db->trans_start();
				
				$this->assignPostData($this->purchasepayment);
				$payment_id = $this->purchasepayment->insert();
				
				if ($payment_id) {
					$amount = $this->input->post('amount');

					// Entry Expense Voucher
					$voucher_code = $this->expense->get_new_code();
					$this->expense->set_value('code', $voucher_code);
					$this->expense->set_value('ref_id', $bill_id);
					$this->expense->set_value('ref_code', $bill->invoice_no);
					$this->expense->set_value('project_id', $bill->project_id);
					$this->expense->set_value('amount', $amount);
					$this->expense->set_value('exp_type', 'purchase');
					$this->expense->set_value('notes', $this->input->post('notes'));
					$this->expense->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('trans_date')), 'MYSQL') );
					$this->expense->insert();

					// update purchase bill
					$update_data = array();
					$update_data['paid_amount'] = $bill->paid_amount + $amount;
					$this->purchase->update($bill_id, $update_data, true );
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $payment_id){
					echo json_encode(array('success'=>'true','msg'=>"Payment has been created.", 'id'=>$payment_id)); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be created. Try again or contact with administrator.")); exit;	
				}
			}
		} else {
			$data['bill'] = $this->purchase->get_one($bill_id);
			
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/payment/make_payment', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/payment/make_payment';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	/**
	 * Verify paid amount form validation callback
	 * @access public
	 * @param double
	 * @return bolean
	 */
	public function check_paid_amount($paid_amount){
		$bill_id = $this->input->post('bill_id');
		$bill = $this->purchase->get_one($bill_id);
		
		if ($paid_amount < ($bill->total_amount - $bill->paid_amount)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_paid_amount', "Paid amount can't be greater then Due Amount.");
			return FALSE;
		}
	}

	/**
	 * Show payments ledger/history of a purchase bill in a modal
	 * @access public
	 * @param integer
	 * @return html
	 */
	public function p_ledger($bill_id){
		$data['bill'] = $this->purchase->get_one($bill_id);
		if (!empty($data['bill'])) {
			$data['payments'] = $this->purchasepayment->get_list_all($bill_id);
		}
		$this->load->view($this->config->item('admin_theme').'/payment/purchase_payment_ledger', $data);
	}

	/**
	 * Show payments ledger/history of a sale bill in a modal
	 * @access public
	 * @param integer
	 * @return html
	 */
	public function s_ledger($bill_id){
		$data['bill'] = $this->sale->get_one($bill_id);
		if (!empty($data['bill'])) {
			$data['payments'] = $this->salepayment->get_list_all($bill_id);
		}
		$this->load->view($this->config->item('admin_theme').'/payment/sale_payment_ledger', $data);
	}

	/**
	 * Receive Payment of a sale bill
	 * @access public
	 * @param integer	 
	 */
	public function receive($bill_id){
		if($this->input->method(TRUE)=='POST'){
			$this->form_validation->set_rules($this->salepayment->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				if ($bill_id != $this->input->post('bill_id')) {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be created. Bill Not Found.")); exit;
				}

				$bill = $this->sale->get_one($bill_id);
				if (empty($bill)) {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be received. Bill Not Found.")); exit;
				}

				$this->db->trans_start();
				
				$this->assignPostData($this->salepayment);
				$payment_id = $this->salepayment->insert();
				
				if ($payment_id) {
					$amount = $this->input->post('amount');

					// Entry Income Voucher
					$voucher_code = $this->income->get_new_code();
					$this->income->set_value('code', $voucher_code);
					$this->income->set_value('ref_id', $bill_id);
					$this->income->set_value('ref_code', $bill->invoice_no);
					$this->income->set_value('project_id', $bill->project_id);
					$this->income->set_value('amount', $amount);
					$this->income->set_value('income_type', 'sale');
					$this->income->set_value('notes', $this->input->post('notes'));
					$this->income->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('trans_date')), 'MYSQL') );
					$this->income->insert();

					// Update Sale Bill
					$update_data = array();
					$update_data['received_amount'] = $bill->received_amount + $amount;
					$this->sale->update($bill_id, $update_data, true );
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $payment_id){
					echo json_encode(array('success'=>'true','msg'=>"Payment has been received.", 'id'=>$payment_id)); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be received. Try again or contact with administrator.")); exit;	
				}
			}
		} else {
			$data['bill'] = $this->sale->get_one($bill_id);
			
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/payment/receive_payment', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/payment/receive_payment';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	/**
	 * Verify paid amount form validation callback
	 * @access public
	 * @param double
	 * @return bolean
	 */
	public function check_received_amount($received_amount){
		$bill_id = $this->input->post('bill_id');
		$bill = $this->sale->get_one($bill_id);
		
		if ($received_amount < ($bill->total_amount - $bill->received_amount)) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_received_amount', "Paid amount can't be greater then Due Amount.");
			return FALSE;
		}
	}

}