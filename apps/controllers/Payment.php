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
		$this->load->model('item_model','item');		
		$this->load->model('itembilled_model','itembilled');		
		$this->load->model('itemstock_model','itemstock');		
		$this->load->model('customer_model','customer');		
		$this->load->model('project_model','project');		
		$this->load->model('income_model','income');		
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

}