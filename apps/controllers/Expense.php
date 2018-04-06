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
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['expenses'] = $this->expense->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/expense/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/expense/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new expense
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->expense->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('expense_id')) {
					$expense = $this->expense->get($this->input->post('expense_id'));
					
					if ($expense->company_id != $this->session->userdata('company_id')) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}

					if ($expense->code != $this->input->post('code')) {
						$tmp = $this->expense->get_by(array('code'=>$this->input->post('code')));
						if (count($tmp) > 0)
						{
							echo json_encode(array('success'=>'false','error'=>'Expense already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
						}
					}

					$this->assignPostData($this->expense);
					$this->expense->set_value('company_id', $this->session->userdata('company_id'));
					$result = $this->expense->update($this->input->post('expense_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"Expense Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"Expense Information can't updated."));
	        			exit;
					}
				}
				else{
					$expense = $this->expense->get_by(array('code'=>$this->input->post('code')));
					if (count($expense) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'Expense already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
					}

					
					$this->assignPostData($this->expense);
					$this->expense->set_value('company_id', $this->session->userdata('company_id'));
					$new_expense_id = $this->expense->insert();

					if ($new_expense_id) {       
						echo json_encode(array('success'=>'true','error'=>"Expense has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'expense';
			$data['expense']=$this->expense->get($id);
			
			$expense = $this->expense->get_latest();
            if (count($expense) > 0)
            {
                $data['code'] = (int)$expense->code + 1;
            }
            else
            {
                $data['code'] = 101;
            }

					
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
	

}