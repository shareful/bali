<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Advancegiven extends My_Controller {

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

		$this->load->model('advancegiven_model','advancegiven');		
		$this->load->model('item_model','item');		
		$this->load->model('supplier_model','supplier');		
		$this->load->model('project_model','project');		
		$this->load->model('expense_model','expense');		
	}

	public function index()
	{
		
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['records'] = $this->advancegiven->get_list_all();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/advancegiven/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/advancegiven/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function save($id=null){
		// ONly Super admin can create new Advance
		// if (!in_array($this->session->userdata('user_type'), array('sadmin','admin'))) {
		// 	show_404();
		// 	return;
		// }

		if($this->input->post())
		{
			$this->form_validation->set_rules($this->advancegiven->validate);

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				 
				
				$record = $this->advancegiven->get_by(array('code'=>$this->input->post('code')));
				if (count($record) > 0)
				{
					echo json_encode(array('success'=>'false','error'=>'Record already exists with the code '.$this->input->post('code').', Please try with another Code.')); exit;
				}

				$this->db->trans_start();

				$this->assignPostData($this->advancegiven);
				$this->advancegiven->set_value('company_id', $this->session->userdata('company_id'));
				$new_record_id = $this->advancegiven->insert();

				if ($new_record_id) {
					$amount = $this->input->post('amount');

					// Entry Expense Voucher
					$voucher_code = $this->expense->get_new_code();
					$this->expense->set_value('code', $voucher_code);
					$this->expense->set_value('ref_id', $new_record_id);
					$this->expense->set_value('ref_code', $this->input->post('code'));
					$this->expense->set_value('project_id', $this->input->post('project_id'));
					$this->expense->set_value('amount', $amount);
					$this->expense->set_value('exp_type', 'advance');
					$this->expense->set_value('notes', $this->input->post('notes'));
					$this->expense->set_value('trans_date', custom_standard_date(date_human_to_unix($this->input->post('trans_date')), 'MYSQL') );
					$this->expense->set_value('company_id', $this->session->userdata('company_id'));
					$this->expense->insert();					
				}

				$this->db->trans_complete();

				if($this->db->trans_status() === TRUE AND $new_record_id){
					echo json_encode(array('success'=>'true','msg'=>"Advance Payment has been created.", 'id'=>$new_record_id)); exit;
				} else {
					echo json_encode(array('success'=>'false','error'=>"Payment can't be created. Try again or contact with administrator.")); exit;	
				}
			}	
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'record';
			$data['projects'] = $this->project->get_list_all();
			$data['suppliers'] = $this->supplier->get_list_all();
			$data['items'] = $this->item->get_list_all();
			
			$data['record']=$this->advancegiven->get($id);
			
			$data['code'] = $this->advancegiven->get_new_code();
            		
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/advancegiven/save', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/advancegiven/save';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
			
	}
}