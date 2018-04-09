<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Income extends My_Controller {

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

		$this->load->model('income_model','income');		
		$this->load->model('project_model','project');		
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['incomes'] = $this->income->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/income/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/income/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new income
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->method(TRUE)=='POST')
		{
			$rules = $this->income->validate;

			if ($this->input->post('income_type') == 'other') {
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
				 
				
				$record = $this->income->get_by(array('code'=>$this->input->post('code')));
				if (count($record) > 0)
				{
					echo json_encode(array('success'=>'false','error'=>'Record already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
				}

				$this->db->trans_start();

				$this->assignPostData($this->income);
				$this->income->set_value('company_id', $this->session->userdata('company_id'));
				$new_record_id = $this->income->insert();

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_record_id){
					echo json_encode(array('success'=>'true','msg'=>"Income has been saved.", 'id'=>$new_record_id)); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Income can't be saved. Try again or contact with administrator.")); exit;	
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'income';
			$data['income']=$this->income->get($id);
			$data['projects'] = $this->project->get_list_all();
			$data['income_type_list'] = array(
				'invest'=>'Invest',
				'other'=>'Other'
			);

			$data['code'] = $this->income->get_new_code();
            
					
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/income/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/income/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete(){
		// ONly Super admin can dele income
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->income->delete($id)){		
					echo json_encode(array('success'=>'true','error'=>"Income Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Income.")); exit;
				}
			} 

		}
		// $this->income->delete_by_id($id);
		$data['incomes']=$this->income->get_list_all();
		$this->load->view($this->config->item('admin_theme').'/income/list_all',$data);
	}
	

	public function check_income_type($income_type){
		if (in_array($income_type, array('sale','advance','security','invest','other'))) {
			return TRUE;
		} else {
			$this->form_validation->set_message('check_income_type', "Income Type is not valid.");
			return FALSE;
		}
	}

}