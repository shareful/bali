<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends My_Controller {

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

		$this->load->model('project_model','project');		
		$this->load->model('projectitem_model','projectitem');		
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['projects'] = $this->project->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/project/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/project/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new project
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->project->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('project_id')) {
					$project = $this->project->get($this->input->post('project_id'));
					
					if ($project->company_id != $this->session->userdata('company_id')) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}

					if ($project->code != $this->input->post('code')) {
						$tmp = $this->project->get_by(array('code'=>$this->input->post('code')));
						if (count($tmp) > 0)
						{
							echo json_encode(array('success'=>'false','error'=>'Project already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
						}
					}

					$this->assignPostData($this->project);
					$this->project->set_value('company_id', $this->session->userdata('company_id'));
					$result = $this->project->update($this->input->post('project_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"Project Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"Project Information can't updated."));
	        			exit;
					}
				}
				else{
					$project = $this->project->get_by(array('code'=>$this->input->post('code')));
					if (count($project) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'Project already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
					}

					
					$this->assignPostData($this->project);
					$this->project->set_value('company_id', $this->session->userdata('company_id'));
					$new_project_id = $this->project->insert();

					if ($new_project_id) {       
						echo json_encode(array('success'=>'true','error'=>"Project has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'project';
			$data['project']=$this->project->get($id);
			
			$project = $this->project->get_latest();
            if (count($project) > 0)
            {
                $data['code'] = (int)$project->code + 1;
            }
            else
            {
                $data['code'] = 101;
            }

					
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/project/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/project/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete(){
		// ONly Super admin can dele project
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->project->delete($id)){		
					echo json_encode(array('success'=>'true','error'=>"Project Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Project.")); exit;
				}
			} 

		}
		// $this->project->delete_by_id($id);
		$data['projects']=$this->project->get_list_all();
		$this->load->view($this->config->item('admin_theme').'/project/list_all',$data);
	}

	public function add_item($id=null){
		// ONly Super admin can create new project
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post()){
			$this->form_validation->set_rules($this->projectitem->validate);
			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				if($this->projectitem->is_item_exist($id, $this->input->post('item_id'))){
					echo json_encode(array('success'=>'false','error'=>'Item already added to this project.')); exit;
				} else {
					$this->assignPostData($this->projectitem);
					$new_id = $this->projectitem->insert();

					if ($new_id) {       
						echo json_encode(array('success'=>'true','error'=>"Item has been added to the project.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}
		} else {

			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'project';
			$data['items'] = $this->projectitem->get_items_not_added($id);
			$data['project']=$this->project->get($id);
			
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/project/add_item', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/project/add_item';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}
	

}