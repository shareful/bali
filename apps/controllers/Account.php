<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends My_Controller {

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

		$this->load->model('account_model','account');		
		$this->load->model('subaccount_model','subaccount');		
	}
	
	public function index()
	{
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['accounts'] = $this->account->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/account/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/account/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new account
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->account->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('acc_id')) {
					$account = $this->account->get($this->input->post('acc_id'));
					
					if ($account->company_id != $this->session->userdata('company_id')) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}

					$this->assignPostData($this->account);
					$this->account->set_value('company_id', $this->session->userdata('company_id'));
					$this->account->set_value('code', $account->code);
					$result = $this->account->update($this->input->post('acc_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"Account Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"Account Information can't updated."));
	        			exit;
					}
				}
				else{
					$account = $this->account->get_by(array('company_id'=>$this->session->userdata('company_id'), 'code'=>$this->input->post('code')));
					if (count($account) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'Account already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
					}

					
					$this->assignPostData($this->account);
					$this->account->set_value('company_id', $this->session->userdata('company_id'));
					$new_acc_id = $this->account->insert();

					if ($new_acc_id) {       
						echo json_encode(array('success'=>'true','error'=>"Account has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'account';
			$data['account']=$this->account->get($id);
			
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/account/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/account/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	public function subacc($acc_id)
	{
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['account'] = $this->account->get($acc_id);
		if (empty($data['account'])) {
			show_404();
			return;
		}

		$data['accounts'] = $this->subaccount->get_list_all($acc_id);
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/account/sub_list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/account/sub_list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function sub_save($acc_id, $id=null){
		// ONly Super admin can create new account
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		$data['account'] = $this->account->get($acc_id);
		if (empty($data['account'])) {
			show_404();
			return;
		}

		if ($data['account']->company_id != $this->session->userdata('company_id')) {
			show_404();
			return;
		}
		

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->subaccount->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('sub_acc_id')) {
					$sub_account = $this->subaccount->get($this->input->post('sub_acc_id'));
					
					if ($sub_account->acc_id != $data['account']->acc_id) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}
					if ($sub_account->code != $this->input->post('code')) {
						$tmp = $this->subaccount->get_by(array('acc_id'=>$data['account']->acc_id, 'code'=>$this->input->post('code')));
						if (!empty($tmp)) {
							echo json_encode(array('success'=>'false','error'=>'Sub Account already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
						}
					}

					$this->assignPostData($this->subaccount);
					// $this->subaccount->set_value('code', $sub_account->code);
					$result = $this->subaccount->update($this->input->post('sub_acc_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"Sub Account Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"Sub Account Information can't updated."));
	        			exit;
					}
				}
				else{
					$sub_account = $this->subaccount->get_by(array('acc_id'=>$data['account']->acc_id, 'code'=>$this->input->post('code')));
					if (count($sub_account) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'Sub Account already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
					}

					
					$this->assignPostData($this->subaccount);
					$this->subaccount->set_value('acc_id', $data['account']->acc_id);
					$new_acc_id = $this->subaccount->insert();

					if ($new_acc_id) {       
						echo json_encode(array('success'=>'true','error'=>"Sub Account has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'account';
			$data['sub_account']=$this->subaccount->get($id);
			
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/account/sub_save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/account/sub_save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	public function subacc_options($acc_id){
		$account = $this->account->get($acc_id);
		$sub_accounts = array();
		$html = '';
		
		if (!empty($account) and $account->have_sub == 'Yes') {
			$sub_accounts = $this->subaccount->get_list_all($acc_id);
			if (count($sub_accounts) > 0) {
				$html = '<option value="" selected="selected"> Select One </option>';
				
				foreach ($sub_accounts as $key => $sub) {
					$html .= '<option value="'.$sub->sub_acc_id.'">'.$sub->name.'</option>';
				}
			}
		}


		echo json_encode(array('success'=>'true','html'=>$html)); exit;
	}
	
}