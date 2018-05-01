<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Expense extends My_Controller {

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

		$this->load->model('expense_model','expense');		
		$this->load->model('project_model','project');		
		$this->load->model('itemstock_model','itemstock');		
		$this->load->model('account_model','account');		
		$this->load->model('subaccount_model','subaccount');		
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		$data['accounts'] = $this->account->get_list_all();

		$params = array();

		if ($this->input->post_get('project_id')) {
			$params['project_id'] = $this->input->post_get('project_id');
		}

		if ($this->input->post_get('from_date')) {
			$params['from_date'] = $this->input->post_get('from_date');
		}
		
		if ($this->input->post_get('to_date')) {
			$params['to_date'] = $this->input->post_get('to_date');
		}

		if ($this->input->post_get('item_id')) {
			$params['item_id'] = $this->input->post_get('item_id');
		}

		if ($this->input->post_get('acc_id')) {
			$params['acc_id'] = $this->input->post_get('acc_id');
		}

		if ($this->input->post_get('sub_acc_id')) {
			$params['sub_acc_id'] = $this->input->post_get('sub_acc_id');
		}


		$where = array();
		if (isset($params['project_id'])) {
			$where['project_id'] = $params['project_id'];
			$data['items'] = $this->itemstock->get_list_all($params['project_id']);
		}
		
		if (isset($params['item_id'])) {
			$where['item_id'] = $params['item_id'];
		}

		if (isset($params['acc_id'])) {
			$where['acc_id'] = $params['acc_id'];
			$data['subaccounts'] = $this->subaccount->get_list_all($params['acc_id']);
		}

		if (isset($params['sub_acc_id'])) {
			$where['sub_acc_id'] = $params['sub_acc_id'];
		}

		if (isset($params['from_date'])) {
			$from_date = custom_standard_date(date_human_to_unix($params['from_date']), 'MYSQL');
			$where['trans_date >='] = $from_date;
		}

		if (isset($params['to_date'])) {
			$to_date = custom_standard_date(date_human_to_unix($params['to_date']), 'MYSQL');
			$where['trans_date <='] = $to_date.' 23:59:59';
		}


		$data['expenses'] = $this->expense->get_list_all($where);

		$data['params'] = $params;

		if(is_ajax()){
			/*if($this->input->method(TRUE)=='POST'){
				$html = $this->load->view($this->config->item('admin_theme').'/expense/list_data', $data, true);
				echo json_encode(array('success'=>'true','html'=>$html)); exit;
			} else {				
			}*/
			$this->load->view($this->config->item('admin_theme').'/expense/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/expense/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new expense
		// if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
		// 	show_404();
		// 	return;
		// }

		if($this->input->method(TRUE)=='POST')
		{
			$rules = $this->expense->validate;

			if ($this->input->post('exp_type') == 'other') {
				$rules[] = array( 'field' => 'notes',
	               'label' => 'Notes',
	               'rules' => 'required' );
			}

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
				 
				
				$record = $this->expense->get_by(array('code'=>$this->input->post('code')));
				if (count($record) > 0)
				{
					echo json_encode(array('success'=>'false','error'=>'Record already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
				}

				// balance check
				$sub_acc_balance = 0;
				$acc_balance = 0;
				if ($this->input->post('sub_acc_id')) {
					$sub_acc_balance = $this->subaccount->get_balance($this->input->post('sub_acc_id'));
					if ($this->input->post('amount') > $sub_acc_balance) {
						echo json_encode(array('success'=>'false','error'=>'You don\'t have the sufficient balance to expense from the selected sub account.')); exit;
					}
				} else {
					$acc_balance = $this->account->get_balance($this->input->post('acc_id'));
					if ($this->input->post('amount') > $acc_balance) {
						echo json_encode(array('success'=>'false','error'=>'You don\'t have the sufficient balance to expense from the selected account.')); exit;
					}
				}


					
				$this->db->trans_start();

				$this->assignPostData($this->expense);
				$this->expense->set_value('company_id', $this->session->userdata('company_id'));
				$new_record_id = $this->expense->insert();

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_record_id){
					echo json_encode(array('success'=>'true','msg'=>"Expense has been saved.", 'id'=>$new_record_id)); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Expense can't be saved. Try again or contact with administrator.")); exit;	
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'expense';
			$data['expense']=$this->expense->get($id);
			$data['projects'] = $this->project->get_list_all();
			$data['accounts'] = $this->account->get_list_all();
			$data['code'] = $this->expense->get_new_code();
            
					
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/expense/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/expense/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete(){
		// ONly Super admin can dele expense
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->expense->delete($id)){		
					echo json_encode(array('success'=>'true','error'=>"Expense Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Expense.")); exit;
				}
			} 

		}
		// $this->expense->delete_by_id($id);
		$data['expenses']=$this->expense->get_list_all();
		$this->load->view($this->config->item('admin_theme').'/expense/list_all',$data);
	}
	

	public function check_exp_type($exp_type){
		if (in_array($exp_type, array('purchase','advance','security','other'))) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_exp_type', "Expense Type is not valid.");
			return FALSE;
		}
	}

}