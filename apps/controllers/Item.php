<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends My_Controller {

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

		$this->load->model('item_model','item');		
	}
	
	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['items'] = $this->item->get_list_all(true);
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/item/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/item/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new item
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->item->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				if ($this->input->post('item_id')) {
					$item = $this->item->get($this->input->post('item_id'));
					
					if ($item->company_id != $this->session->userdata('company_id')) {
						echo json_encode(array('success'=>'false','error'=>"Permission denied."));
	        			exit;
					}

					if ($item->code != $this->input->post('code')) {
						$tmp = $this->item->get_by(array('code'=>$this->input->post('code')));
						if (count($tmp) > 0)
						{
							echo json_encode(array('success'=>'false','error'=>'Item already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
						}
					}

					$this->assignPostData($this->item);
					$this->item->set_value('company_id', $this->session->userdata('company_id'));
					$result = $this->item->update($this->input->post('item_id')); 
					if ($result) {
						echo json_encode(array('success'=>'true','msg'=>"Item Information has been updated."));
            			exit;
						
					} else {						
						echo json_encode(array('success'=>'false','error'=>"Item Information can't updated."));
	        			exit;
					}
				}
				else{
					$item = $this->item->get_by(array('code'=>$this->input->post('code')));
					if (count($item) > 0)
					{
						echo json_encode(array('success'=>'false','error'=>'Item already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
					}

					
					$this->assignPostData($this->item);
					$this->item->set_value('company_id', $this->session->userdata('company_id'));
					$new_item_id = $this->item->insert();

					if ($new_item_id) {       
						echo json_encode(array('success'=>'true','error'=>"Item has been created.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'item';
			$data['item']=$this->item->get($id);
			
			$item = $this->item->get_latest();
            if (count($item) > 0)
            {
                $data['code'] = (int)$item->code + 1;
            }
            else
            {
                $data['code'] = 3001;
            }

					
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/item/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/item/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
	public function delete(){
		// ONly Super admin can dele item
		if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
			show_404();
			return;
		}

		if ($this->input->post()) {
			if ($id=$this->input->post('id')){
				if($this->item->delete($id)){		
					echo json_encode(array('success'=>'true','error'=>"Item Delete.")); exit;
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"Can't delete this Item.")); exit;
				}
			} 

		}
		// $this->item->delete_by_id($id);
		$data['items']=$this->item->get_list_all();
		$this->load->view($this->config->item('admin_theme').'/item/list_all',$data);
	}
	

}