<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reseller extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if ( ! $this->session->userdata('user_id'))
		{
			redirect('', 'refresh');
		}
		
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}

		$this->eur_to_bdt = $this->MDailyRates->latest_eur_to_bdt();
	}

	public function index()
	{
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'reseller';
			
		if(is_ajax()){
            $this->load->view($this->config->item('admin_theme').'/reseller/save', $data);
            return;
        }

        $data['content'] = $this->config->item('admin_theme').'/reseller/save';
        // $data['privileges'] = $this->privileges;
        $this->load->view($this->config->item('admin_theme').'/template', $data);
	}

	public function list_all()
	{
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}

		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['resellers'] = $this->MReseller->get_all();
		$data['eur_to_bdt'] = $this->eur_to_bdt;
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/reseller/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/reseller/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new reseller
		if (!in_array($this->session->userdata('user_type'), array('Super Admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$this->form_validation->set_rules('company_name', 'Company name','required');			
			$this->form_validation->set_rules('contact_name', 'Contact name','required');			
			$this->form_validation->set_rules('mobile','Mobile number','required');
			$this->form_validation->set_rules('balance','Balance','required');
			$this->form_validation->set_rules('username','User name','required');
			if (!$this->input->post('id')) {
				$this->form_validation->set_rules('password','Password','required');
			}
			$this->form_validation->set_error_delimiters('', '<br/>');

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('id')) {
					$this->MReseller->update_reseller();
					if ($this->input->post('password')) {
						$data['resellers']=$this->MReseller->get_by_id($this->input->post('id'));
						if (!empty($data['resellers'])) {
							$data['users']=$this->MUsers->get_where(array('reseller_id' => $data['resellers']['reseller_id'], 'type' => 'Admin'), 1);
							if (!empty($data['users'])) {
								$this->MUsers->update_password($data['users']['user_id']);
								echo json_encode(array('success'=>'true','error'=>"Reseller Information And Password has been updated."));
		            			exit;
							}
						}
					}
					echo json_encode(array('success'=>'true','error'=>"Reseller Information has been updated."));
        			exit;
				}
				else{
					$company = $this->MReseller->get_by_name($this->input->post('company_name'));
					if (count($company) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'User Id already exists, Please try with another User Id.')); exit;
					}
					
					$user = $this->MUsers->get_by_username($this->input->post('username'));
					if (count($user) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'User Id already exists, Please try with another User Id.')); exit;
					}

					if ($this->MReseller->create()) {       
						echo json_encode(array('success'=>'true','error'=>"Reseller has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'user';
			// $data['resellers']=$this->MReseller->get_all();
			$data['resellers']=$this->MReseller->get_by_id($id);
			if (!empty($data['resellers'])) {
				$data['users']=$this->MUsers->get_where(array('reseller_id' => $data['resellers']['reseller_id'], 'type' => 'Admin'), 1);
				// echo $this->db->last_query();exit();
			} else {
				$data['users'] = array();
			}

			$data['eur_to_bdt'] = $this->eur_to_bdt;
					
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/reseller/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/reseller/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete($id=null){
		if (!in_array($this->session->userdata('user_type'), array('Super Admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->MReseller->delete_by_id($id)){		
					echo json_encode(array('success'=>'true','error'=>"Reseller Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Reseller.")); exit;
				}
			} 

		}
		// $this->MReseller->delete_by_id($id);
		$data['resellers']=$this->MReseller->get_all();
		$this->load->view($this->config->item('admin_theme').'/reseller/list_all',$data);
	}
	

}