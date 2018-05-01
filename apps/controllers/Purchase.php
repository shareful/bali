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
		$this->load->model('purchasepayment_model','purchasepayment');		
		$this->load->model('account_model','account');		
		$this->load->model('subaccount_model','subaccount');	
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
			$rules = $this->purchase->validate;

			if ($this->input->post('paid_amount') > 0) {
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
			}

			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				// balance check
				$sub_acc_balance = 0;
				$acc_balance = 0;
				if ($this->input->post('sub_acc_id')) {
					$sub_acc_balance = $this->subaccount->get_balance($this->input->post('sub_acc_id'));
					if ($this->input->post('paid_amount') > $sub_acc_balance) {
						echo json_encode(array('success'=>'false','error'=>'You don\'t have the sufficient balance to expense from the selected sub account.')); exit;
					}
				} else if($this->input->post('acc_id')) {
					$acc_balance = $this->account->get_balance($this->input->post('acc_id'));
					if ($this->input->post('paid_amount') > $acc_balance) {
						echo json_encode(array('success'=>'false','error'=>'You don\'t have the sufficient balance to expense from the selected account.')); exit;
					}
				}

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
						$this->expense->set_value('acc_id', $this->input->post('acc_id'));
						$this->expense->set_value('sub_acc_id', $this->input->post('sub_acc_id'));
						$this->expense->set_value('check_trans_no', $this->input->post('check_trans_no'));
						$this->expense->set_value('notes', $this->input->post('notes'));
						$this->expense->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('bill_date')), 'MYSQL') );
						$this->expense->insert();

						// Insert Payment history
						$this->purchasepayment->set_value('bill_id', $new_purchase_id);
						$this->purchasepayment->set_value('amount', $paid_amount);
						$this->purchasepayment->set_value('src_type', 'bill');
						$this->purchasepayment->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('bill_date')), 'MYSQL') );
						$this->purchasepayment->insert();
					}
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_purchase_id){
					echo json_encode(array('success'=>'true','msg'=>"Purchase Bill has been created.", 'id'=>$new_purchase_id)); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Bill can't be created. Try again or contact with administrator.")); exit;	
				}

			}
		} else {
			// $data['items'] = $this->item->get_list_all();
			$data['items'] = $this->itemstock->get_list_all($project_id);;
			$data['suppliers'] = $this->supplier->get_list_all();
			$data['projects'] = $this->project->get_list_all();
			$data['itemstock'] = $this->itemstock->get_item($item_id, $project_id);
			$data['accounts'] = $this->account->get_list_all();

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
	 * Print Purchase Bill
	 * @access public
	 * @param integer
	 */
	public function bill_print($id){
		$data['bill'] = $this->purchase->get_one($id);

        $data['content'] = $this->config->item('admin_theme').'/purchase/bill_print';
        $this->load->view($this->config->item('admin_theme').'/rprint_template', $data);
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
		if ($paid_amount <= $total) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_paid_amount', "Paid amount can't be greater then total amount.");
			return FALSE;
		}
	}
	
	public function get_bill_no($item_id, $project_id, $supplier_id){
		$code = $this->purchase->get_new_code($project_id, $supplier_id, $item_id, true);

        echo json_encode(array('success'=>'true','code'=>$code)); exit;
	}

	public function delete(){
		// ONly Super admin can delete bill
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				$bill = $this->purchase->get_by(array('id'=>$id,'company_id'=>$this->session->userdata('company_id')));
				if (empty($bill)) {
					echo json_encode(array('success'=>'false','error'=>"Bill not found.")); exit;
				}
				
				if ($bill->paid_amount > 0) {
					echo json_encode(array('success'=>'false','error'=>"You can't delete this bill. Because {$bill->paid_amount} tk already paid against this bill.")); exit;
				}

				if($this->purchase->delete($id)){		
					echo json_encode(array('success'=>'true','msg'=>"Bill has been Deleted.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Bill.")); exit;
				}
			} 

		}		
	}
}