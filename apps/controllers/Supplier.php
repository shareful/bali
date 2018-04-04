<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Supplier extends My_Controller {

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

		$this->load->model('supplier_model','supplier');		
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['suppliers'] = $this->supplier->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/supplier/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/supplier/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new supplier
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->supplier->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('supplier_id')) {
					$supplier = $this->supplier->get($this->input->post('supplier_id'));
					
					if ($supplier->company_id != $this->session->userdata('company_id')) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}

					if ($supplier->code != $this->input->post('code')) {
						$tmp = $this->supplier->get_by(array('code'=>$this->input->post('code')));
						if (count($tmp) > 0)
						{
							echo json_encode(array('success'=>'false','error'=>'Supplier already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
						}
					}

					$this->assignPostData($this->supplier);
					$this->supplier->set_value('company_id', $this->session->userdata('company_id'));
					$result = $this->supplier->update($this->input->post('supplier_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"Supplier Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"Supplier Information can't updated."));
	        			exit;
					}
				}
				else{
					$supplier = $this->supplier->get_by(array('code'=>$this->input->post('code')));
					if (count($supplier) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'Supplier already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
					}

					
					$this->assignPostData($this->supplier);
					$this->supplier->set_value('company_id', $this->session->userdata('company_id'));
					$new_supplier_id = $this->supplier->insert();

					if ($new_supplier_id) {       
						echo json_encode(array('success'=>'true','error'=>"Supplier has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'supplier';
			$data['supplier']=$this->supplier->get($id);
			
			$supplier = $this->supplier->get_latest();
            if (count($supplier) > 0)
            {
                $data['code'] = (int)$supplier->code + 1;
            }
            else
            {
                $data['code'] = 1001;
            }

					
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/supplier/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/supplier/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete(){
		// ONly Super admin can dele supplier
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->supplier->delete($id)){		
					echo json_encode(array('success'=>'true','error'=>"Supplier Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Supplier.")); exit;
				}
			} 

		}
		// $this->supplier->delete_by_id($id);
		$data['suppliers']=$this->supplier->get_list_all();
		$this->load->view($this->config->item('admin_theme').'/supplier/list_all',$data);
	}
	

}