<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends My_Controller {

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

		$this->load->model('customer_model','customer');		
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['customers'] = $this->customer->get_list_all(true);
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/customer/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/customer/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new customer
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->customer->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('customer_id')) {
					$customer = $this->customer->get($this->input->post('customer_id'));
					
					if ($customer->company_id != $this->session->userdata('company_id')) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}

					if ($customer->code != $this->input->post('code')) {
						$tmp = $this->customer->get_by(array('code'=>$this->input->post('code')));
						if (count($tmp) > 0)
						{
							echo json_encode(array('success'=>'false','error'=>'Customer already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
						}
					}

					$this->assignPostData($this->customer);
					$this->customer->set_value('company_id', $this->session->userdata('company_id'));
					$result = $this->customer->update($this->input->post('customer_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"Customer Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"Customer Information can't updated."));
	        			exit;
					}
				}
				else{
					$customer = $this->customer->get_by(array('code'=>$this->input->post('code')));
					if (count($customer) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'Customer already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
					}

					
					$this->assignPostData($this->customer);
					$this->customer->set_value('company_id', $this->session->userdata('company_id'));
					$new_customer_id = $this->customer->insert();

					if ($new_customer_id) {       
						echo json_encode(array('success'=>'true','error'=>"Customer has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'customer';
			$data['customer']=$this->customer->get($id);
			
			$customer = $this->customer->get_latest();
            if (count($customer) > 0)
            {
                $data['code'] = (int)$customer->code + 1;
            }
            else
            {
                $data['code'] = 2001;
            }

					
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/customer/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/customer/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete(){
		// ONly Super admin can dele customer
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->customer->delete($id)){		
					echo json_encode(array('success'=>'true','error'=>"Customer Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Customer.")); exit;
				}
			} 

		}
		// $this->customer->delete_by_id($id);
		$data['customers']=$this->customer->get_list_all();
		$this->load->view($this->config->item('admin_theme').'/customer/list_all',$data);
	}
	

}