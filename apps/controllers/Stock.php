<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends My_Controller {

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
		$this->load->model('item_model','item');		
		$this->load->model('itemstock_model','itemstock');		
		$this->load->model('itembilled_model','itembilled');		
	}
	
	public function index($project_id=null)
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['items'] = $this->itemstock->get_list_all($project_id);
		$data['project_list'] = $this->project->get_option_list();
		$data['project_id'] = $project_id;

		foreach ($data['items'] as $key => $item) {
			$billed = $this->itembilled->get_item($item->item_id, $project_id);
			if(!empty($billed)){
				$item->billed = $billed->billed;
			} else {
				$item->billed = 0;
			}
			$data['items'][$key] = $item;
		}
		
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/stock/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/stock/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function by_project($project_id=null){		
		$data['project_id'] = $project_id;		
		$data['items']=$this->itemstock->get_list_all($project_id);

		foreach ($data['items'] as $key => $item) {
			$billed = $this->itembilled->get_item($item->item_id, $project_id);
			if(!empty($billed)){
				$item->billed = $billed->billed;
			} else {
				$item->billed = 0;
			}
			$data['items'][$key] = $item;
		}

		$this->load->view($this->config->item('admin_theme').'/stock/list_only',$data);
	}

	public function get_item_info($item_id, $project_id, $get_billed_too=0){
		$item = $this->itemstock->get_item($item_id, $project_id);
		if (!empty($item)) {
			if ($get_billed_too) {
				$billed = $this->itembilled->get_item($item_id, $project_id);
				if(!empty($billed)){
					$item->billed = $billed->billed;
				} else {
					$item->billed = 0;
				}
			}
			echo json_encode(array('success'=>'true','info'=>$item));
			exit;
		} else {
			echo json_encode(array('success'=>'false','error'=>'Item not found.'));
			exit;
		}
	}
	

}