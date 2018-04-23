<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scriptprojectitem extends My_Controller {

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

		$this->load->model('purchase_model','purchase');		
		$this->load->model('sale_model','sale');		
		$this->load->model('item_model','item');		
		$this->load->model('projectitem_model','projectitem');		
		$this->load->model('project_model','project');				
	}
	
	public function index()
	{
		$projects = $this->project->get_list_all();
		foreach ($projects as $project) {
			echo 'project: <b>'.$project->name.'</b><br>';
			$bills = $this->purchase->get_list_all($project->project_id);
			foreach ($bills as $bill) {
				if (!$this->projectitem->is_item_exist($project->project_id, $bill->item_id)) {
					$this->projectitem->set_value('project_id', $project->project_id);
					$this->projectitem->set_value('item_id', $bill->item_id);
					$this->projectitem->set_value('id', null);
					$this->projectitem->insert();
					echo 'inserted item '.$bill->item_id.' for purchase bill<br>';
				}
			}

			$bills = $this->sale->get_list_all($project->project_id);
			foreach ($bills as $bill) {
				if (!$this->projectitem->is_item_exist($project->project_id, $bill->item_id)) {
					$this->projectitem->set_value('project_id', $project->project_id);
					$this->projectitem->set_value('item_id', $bill->item_id);
					$this->projectitem->set_value('id', null);
					$this->projectitem->insert();
					echo 'inserted item '.$bill->item_id.' for sale bill<br>';
				}
			}
		}

		echo 'FINISHED';
		
	}
}