<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends CI_Controller
{
	public function __construct(){
		parent::__construct();

		if ( ! $this->session->userdata('user_id'))
		{
			redirect('', 'refresh');
		}

		$this->eur_to_bdt = $this->MDailyRates->latest_eur_to_bdt();
	}


	public function save_deposit($reseller_id=null){
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}
		
		if ($this->input->post()) {
			$this->form_validation->set_rules('reseller_id', 'reseller ','required');
			$this->form_validation->set_rules('amount','amount ','required');
			$this->form_validation->set_rules('notes','notes ','required');
			$this->form_validation->set_rules('date','date ','required');
			// $this->form_validation->set_rules('status','status ','required');
			if ($this->form_validation->run()==FALSE) {
					echo json_encode(array("error"=>$this->form_validation->error_String()));
			} else {
				$reseller = $this->MReseller->get_by_id($this->input->post('reseller_id'));
				if (count($reseller)>0) {
					$this->db->trans_start();

					$this->MAccount->transection_ledger(array(
						'reseller_id' => $this->input->post('reseller_id'),
						'amount' => $this->input->post('amount'),
						'amount_bdt' => $this->input->post('amount_bdt'),
						'comment' => $this->input->post('notes'),
						'trasncation_date' => $this->input->post('date').' '.date("H:i:s"),
						'ref_id' => $this->input->post('ref_id'),
						'transaction_type' => 'deposit',
					));
					$this->MAccount->create(array(
						'reseller_id' => $this->input->post('reseller_id'),
						'amount' => $this->input->post('amount'),
						'amount_bdt' => $this->input->post('amount_bdt'),
						'comment' => $this->input->post('notes'),
						'date' => $this->input->post('date').' '.date("H:i:s"),
						'status' => 'success',
					));

					$this->MReseller->update_balance($this->input->post('reseller_id'), $this->input->post('amount'));

					$this->db->trans_complete();

					if($this->db->trans_status() === TRUE){
                		echo json_encode(array('success'=>'true','msg'=> 'Amount of '.$this->input->post('amount')." has been deposited successfully.")); exit;
	            	} else {
	                	echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
	            	}
				} else {
                	echo json_encode(array('success'=>'false','error'=>"Reseller not found.")); exit;
              	}
			}	

			
		}
		else{
			$data['despositors']=null;
			$data['reseller_id']=$reseller_id;
			$data['resellers']=$this->MReseller->get_all();
			$data['eur_to_bdt'] = $this->eur_to_bdt;
			$this->load->view($this->config->item('admin_theme').'/deposit/save',$data);

		}
	}


	public function list_all(){
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}

		// $data['resellers']=$this->MReseller->get_all();
		$data['despositors']=$this->MAccount->get_all();
		foreach ($data['despositors'] as $key => $dep) {
			if (!$dep['amount_bdt']) {
				$rate = $this->MDailyRates->latest_eur_to_bdt($dep['date']);
				$dep['amount_bdt'] = $dep['amount'] * $rate['inverse_value'];
				$data['despositors'][$key] = $dep;
			}
		}
		$data['eur_to_bdt'] = $this->eur_to_bdt;
		$this->load->view($this->config->item('admin_theme').'/deposit/list',$data);
	}


	public  function edit_depositor($id=null){
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}

		$data['resellers']=$this->MReseller->get_all();
		$data['despositors']=$this->MAccount->get_by_id($id);
		$this->load->view($this->config->item('admin_theme').'/deposit/save',$data);
	}


	public function depositor_ledger($id=null){
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}

		$data['all_ledger']=$this->MAccount->get_all_depositor_ledger_by_id($id);
		foreach ($data['all_ledger'] as $key => $tran) {
			if (!$tran['amount_bdt']) {
				$rate = $this->MDailyRates->latest_eur_to_bdt($tran['trasncation_date']);
				$tran['amount_bdt'] = $tran['amount'] * $rate['inverse_value'];
				$data['all_ledger'][$key] = $tran;
			}
		}
		$data['eur_to_bdt'] = $this->eur_to_bdt;

		$data['reseller']=$this->MReseller->get_by_id($id);
		$this->load->view($this->config->item('admin_theme').'/reseller/ledger',$data);
	}

	public function ledger(){
		if (!in_array($this->session->userdata('user_type'), array('Admin','User'))) {
			show_404();
			return;
		}
		$data['all_ledger']=$this->MAccount->get_all_depositor_ledger_by_id($this->session->userdata('reseller_id'));
		
		foreach ($data['all_ledger'] as $key => $tran) {
			$rate = $this->MDailyRates->latest_eur_to_bdt($tran['trasncation_date']);
			$tran['amount_bdt'] = $tran['amount'] * $rate['inverse_value'];
			$data['all_ledger'][$key] = $tran;
		}

		$data['balance']=$this->MReseller->get_balance($this->session->userdata('reseller_id'));
		$data['eur_to_bdt'] = $this->eur_to_bdt;

		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/reseller/my_ledger', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/reseller/my_ledger';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
	}
}
?>