<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends My_Controller {

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

		$this->load->model('sale_model','sale');		
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
		$data['bills'] = $this->sale->get_list_all($project_id, $item_id);
		
		$data['project_id'] = $project_id;
		// $data['customer_id'] = $customer_id;
		$data['item_id'] = $item_id;
		$data['item'] = $this->item->get($item_id);
		$data['project'] = $this->project->get($project_id);

		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/sale/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/sale/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function new_bill($project_id, $item_id){
		if($this->input->method(TRUE)=='POST'){
			$this->form_validation->set_rules($this->sale->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				$price = $this->input->post('price');
				$quantity = $this->input->post('quantity');
				$security_perc = $this->input->post('security_perc');
				$received_amount = $this->input->post('received_amount');
				$code = $this->input->post('code');
				$item_id = $this->input->post('item_id');
				$project_id = $this->input->post('project_id');
				$customer_id = $this->input->post('customer_id');

				$total_amount = round($quantity*$price, 2);
				$security_amount = round($total_amount*$security_perc/100, 2);
				$receivable_amount = $total_amount - $security_amount;

				$fullcode = $this->sale->get_new_code($project_id, $customer_id, $item_id, true);
				$code = $this->sale->get_new_code($project_id, $customer_id, $item_id);
				
		        $this->assignPostData($this->sale);
				$this->sale->set_value('company_id', $this->session->userdata('company_id'));
				$this->sale->set_value('code', $code);
				$this->sale->set_value('delivered', 1);
				$this->sale->set_value('security_amount', $security_amount);
				$this->sale->set_value('total_amount', $total_amount);
				$this->sale->set_value('receivable_amount', $receivable_amount);
				$this->sale->set_value('received_amount', $received_amount);

				$this->db->trans_start();
				$new_sale_id = $this->sale->insert();

				if ($new_sale_id) {  
					// Update stock to the project for the item
					$this->itembilled->updateBilled($item_id, $project_id, $quantity);     
					
					if ($received_amount > 0) {
						// Update Expense
						$voucher_code = $this->income->get_new_code();
						$this->income->set_value('code', $voucher_code);
						$this->income->set_value('ref_id', $new_sale_id);
						$this->income->set_value('ref_code', $fullcode);
						$this->income->set_value('project_id', $project_id);
						$this->income->set_value('amount', $received_amount);
						$this->income->set_value('income_type', 'sale');
						$this->income->set_value('trans_date', $this->input->post('bill_date'));
						$this->income->insert();
					}
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_sale_id){
					echo json_encode(array('success'=>'true','error'=>"Sale Bill has been created.")); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Bill can't be created. Try again or contact with administrator.")); exit;	
				}

			}
		} else {
			$data['items'] = $this->item->get_list_all();
			$data['customers'] = $this->customer->get_list_all();
			$data['projects'] = $this->project->get_list_all();
			$data['itembilled'] = $this->itembilled->get_item($item_id, $project_id);
			$data['itemstock'] = $this->itemstock->get_item($item_id, $project_id);
			$data['itembilled'] = $this->itembilled->get_item($item_id, $project_id);

			$data['project_id'] = $project_id;
			$data['customer_id'] = '';
			$data['item_id'] = $item_id;

			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/sale/new_bill', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/sale/new_bill';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	/**
	 * Verify received amount form validation callback
	 * @access private
	 * @param double
	 * @return bolean
	 */
	public function check_received_amount($received_amount){
		$price = $this->input->post('price');
		$quantity = $this->input->post('quantity');

		$total = round($quantity*$price, 2);
		if ($received_amount < $total) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_received_amount', "Received amount can't be greater then total amount.");
			return FALSE;
		}
	}
	
	public function get_bill_no($item_id, $project_id, $customer_id){
		$code = $this->sale->get_new_code($project_id, $customer_id, $item_id, true);
		/*$bill = $this->sale->get_latest($project_id, $customer_id, $item_id);
		$code = '';
        if (count($bill) > 0)
        {
            $code = $bill->project->code.'-'.$bill->customer->code.'-'.$bill->item->code.'-'.((int)$bill->code + 1);
        }
        else
        {
        	$item = $this->item->get($item_id);
        	$project = $this->project->get($project_id);
        	$customer = $this->customer->get($customer_id);
            $code = $project->code.'-'.$customer->code.'-'.$item->code.'-'.'0001';
        }*/

        echo json_encode(array('success'=>'true','code'=>$code)); exit;
	}
}