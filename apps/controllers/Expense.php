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
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['expenses'] = $this->expense->get_list_all();
		if(is_ajax()){
			if($this->input->method(TRUE)=='POST'){
				$html = $this->load->view($this->config->item('admin_theme').'/expense/list_data', $data, true);
				echo json_encode(array('success'=>'true','html'=>$html)); exit;
			} else {				
				$this->load->view($this->config->item('admin_theme').'/expense/list', $data);
				return;
			}
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

		if($this->input->method(TRUE)=='POST')
		{
			$rules = $this->expense->validate;

			if ($this->input->post('exp_type') == 'other') {
				$rules[] = array( 'field' => 'notes',
	               'label' => 'Notes',
	               'rules' => 'required' );
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