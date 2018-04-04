<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends My_Controller {

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
		$this->load->model('item_model','item');		
		$this->load->model('itemstock_model','itemstock');		
		$this->load->model('supplier_model','supplier');		
		$this->load->model('project_model','project');		
		$this->load->model('expense_model','expense');		
	}
	
	public function index($project_id, $item_id)
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['bills'] = $this->purchase->get_list_all($project_id, $item_id);
		
		$data['project_id'] = $project_id;
		// $data['supplier_id'] = $supplier_id;
		$data['item_id'] = $item_id;
		$data['item'] = $this->item->get($item_id);
		$data['project'] = $this->project->get($project_id);

		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/purchase/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/purchase/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function new_bill($project_id, $item_id){
		if($this->input->method(TRUE)=='POST'){
			$this->form_validation->set_rules($this->purchase->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				$price = $this->input->post('price');
				$quantity = $this->input->post('quantity');
				$security_perc = $this->input->post('security_perc');
				$paid_amount = $this->input->post('paid_amount');
				$code = $this->input->post('code');
				$item_id = $this->input->post('item_id');
				$project_id = $this->input->post('project_id');
				$supplier_id = $this->input->post('supplier_id');

				$total_amount = round($quantity*$price, 2);
				$security_amount = round($total_amount*$security_perc/100, 2);
				$payable_amount = $total_amount - $security_amount;

				$fullcode = $this->purchase->get_new_code($project_id, $supplier_id, $item_id, true);
				$code = $this->purchase->get_new_code($project_id, $supplier_id, $item_id);
				
		        $this->assignPostData($this->purchase);
				$this->purchase->set_value('company_id', $this->session->userdata('company_id'));
				$this->purchase->set_value('code', $code);
				$this->purchase->set_value('received', 1);
				$this->purchase->set_value('security_amount', $security_amount);
				$this->purchase->set_value('total_amount', $total_amount);
				$this->purchase->set_value('payable_amount', $payable_amount);
				$this->purchase->set_value('paid_amount', $paid_amount);

				$this->db->trans_start();
				$new_purchase_id = $this->purchase->insert();

				if ($new_purchase_id) {  
					// Update stock to the project for the item
					$this->itemstock->updateStock($item_id, $project_id, $quantity);     
					
					if ($paid_amount > 0) {
						// Update Expense
						$voucher_code = $this->expense->get_new_code();
						$this->expense->set_value('code', $voucher_code);
						$this->expense->set_value('ref_id', $new_purchase_id);
						$this->expense->set_value('ref_code', $fullcode);
						$this->expense->set_value('project_id', $project_id);
						$this->expense->set_value('amount', $paid_amount);
						$this->expense->set_value('exp_type', 'purchase');
						$this->expense->set_value('trans_date', $this->input->post('bill_date'));
						$this->expense->insert();
					}
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_purchase_id){
					echo json_encode(array('success'=>'true','error'=>"Purchase Bill has been created.")); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Bill can't be created. Try again or contact with administrator.")); exit;	
				}

			}
		} else {
			$data['items'] = $this->item->get_list_all();
			$data['suppliers'] = $this->supplier->get_list_all();
			$data['projects'] = $this->project->get_list_all();
			$data['item'] = $this->itemstock->get_item($item_id, $project_id);

			$data['project_id'] = $project_id;
			$data['supplier_id'] = '';
			$data['item_id'] = $item_id;

			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/purchase/new_bill', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/purchase/new_bill';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	/**
	 * Verify paid amount form validation callback
	 * @access private
	 * @param double
	 * @return bolean
	 */
	public function check_paid_amount($paid_amount){
		$price = $this->input->post('price');
		$quantity = $this->input->post('quantity');

		$total = round($quantity*$price, 2);
		if ($paid_amount < $total) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_paid_amount', "Paid amount can't be greater then total amount.");
			return FALSE;
		}
	}
	
	public function get_bill_no($item_id, $project_id, $supplier_id){
		$code = $this->purchase->get_new_code($project_id, $supplier_id, $item_id, true);
		/*$bill = $this->purchase->get_latest($project_id, $supplier_id, $item_id);
		$code = '';
        if (count($bill) > 0)
        {
            $code = $bill->project->code.'-'.$bill->supplier->code.'-'.$bill->item->code.'-'.((int)$bill->code + 1);
        }
        else
        {
        	$item = $this->item->get($item_id);
        	$project = $this->project->get($project_id);
        	$supplier = $this->supplier->get($supplier_id);
            $code = $project->code.'-'.$supplier->code.'-'.$item->code.'-'.'0001';
        }*/

        echo json_encode(array('success'=>'true','code'=>$code)); exit;
	}
}