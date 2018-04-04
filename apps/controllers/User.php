<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends My_Controller {

	function __construct()
	{
		parent::__construct();
		
		if ( ! $this->session->userdata('user_id'))
		{
			redirect('', 'refresh');
		}
		
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		$this->load->model('user_model','user');		
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['users'] = $this->user->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/user/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/user/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new user
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$rules = $this->user->validate;
			if (!$this->input->post('user_id') OR $this->input->post('password') != "" OR $this->input->post('password_confirmation')) {
				$rules[] =  array( 'field' => 'password',
			               'label' => 'User Password',
			               'rules' => 'required|min_length[6]' );
		        $rules[] = array( 'field' => 'password_confirmation',
			               'label' => 'Confirm Password',
			               'rules' => 'required|matches[password]' );
			}
			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('user_id')) {
					$user = $this->user->get($this->input->post('user_id'));
					
					if ($user->company_id != $this->session->userdata('company_id')) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}

					if ($user->username != $this->input->post('username')) {
						$tmp = $this->user->get_by(array('username'=>$this->input->post('username')));
						if (count($tmp) > 0)
						{
							echo json_encode(array('success'=>'false','error'=>'User already exists with the username '.$this->input->post('username').', Please try with another.')); exit;
						}
					}

					$this->assignPostData($this->user);
					$this->user->set_value('company_id', $this->session->userdata('company_id'));
					
					if ($this->input->post('password') == $this->input->post('password_confirmation')) {
						$this->user->set_value('password', $this->input->post('password'));
					}
					
					$result = $this->user->update($this->input->post('user_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"User Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"User Information can't updated."));
	        			exit;
					}
				}
				else{
					$user = $this->user->get_by(array('username'=>$this->input->post('username')));
					if (count($user) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'User already exists with the username '.$this->input->post('username').', Please try with another.')); exit;
					}

					
					$this->assignPostData($this->user);
					$this->user->set_value('company_id', $this->session->userdata('company_id'));
					$new_user_id = $this->user->insert();

					if ($new_user_id) {       
						echo json_encode(array('success'=>'true','error'=>"User has been created.")); exit;
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
			$data['user']=$this->user->get($id);
								
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/user/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/user/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete(){
		// ONly Super admin can dele user
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->user->delete($id)){		
					echo json_encode(array('success'=>'true','error'=>"User Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this User.")); exit;
				}
			} 

		}
		// $this->user->delete_by_id($id);
		$data['users']=$this->user->get_list_all();
		$this->load->view($this->config->item('admin_theme').'/user/list_all',$data);
	}
	

	/**
	 * Verify User type form validation callback
	 * @access public
	 * @param string
	 * @param bolean
	 */
	public function verify_user_type($user_type) {
		if (!in_array($user_type, array('sadmin','admin','user'))) {
			$this->form_validation->set_message('verify_user_type', "The user type is not valid.");
			return FALSE;
		} else {
			return TRUE;
		}
	}
}