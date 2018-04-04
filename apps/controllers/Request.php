<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Request extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		
		if ( ! $this->session->userdata('user_id'))
		{
			redirect('', 'refresh');
		}
		
		$this->eur_to_bdt = $this->MDailyRates->latest_eur_to_bdt();
	}

	public function index()
	{
		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'request';
		
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
		$data['requests'] = $this->MRequest->get_all();
		foreach ($data['requests'] as $key => $req) {
			if (!$req['amount_bdt']) {
				$rate = $this->MDailyRates->latest_eur_to_bdt($req['request_date_time']);
				$req['amount_bdt'] = $req['amount'] * $rate['inverse_value'];
				$data['requests'][$key] = $req;
			}
		}

		$data['eur_to_bdt'] = $this->eur_to_bdt;
		// $data['retypes'] = $this->MRequest->get();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/request/list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/request/list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}
	
	public function my_list_all()
	{
		if (!in_array($this->session->userdata('user_type'), array('Admin','User'))) {
			show_404();
			return;
		}

		$data['title'] = $this->config->item('company_name');
		$data['menu'] = 'list';
		$data['requests'] = $this->MRequest->get_all($this->session->userdata('reseller_id'));
		foreach ($data['requests'] as $key => $req) {
			if (!$req['amount_bdt']) {
				$rate = $this->MDailyRates->latest_eur_to_bdt($req['request_date_time']);
				$req['amount_bdt'] = $req['amount'] * $rate['inverse_value'];
				$data['requests'][$key] = $req;
			}
		}

		$data['eur_to_bdt'] = $this->eur_to_bdt;
		// $data['retypes'] = $this->MRequest->get();
		if(is_ajax()){
			$this->load->view($this->config->item('admin_theme').'/request/my_list', $data);
			return;
		}

		// $data['privileges'] = $this->privileges;
		$data['content'] = $this->config->item('admin_theme').'/request/my_list';
		$this->load->view($this->config->item('admin_theme').'/template', $data);
		
	}

	public function send (){
		if (!in_array($this->session->userdata('user_type'), array('Admin','User'))) {
			show_404();
			return;
		}

		if($this->input->post())
		{	
			$this->form_validation->set_rules('name', 'Request Type','required');			
			$this->form_validation->set_rules('send_to', 'Send To Number','required');			
			$this->form_validation->set_rules('amount', 'Amount','required');			
			$this->form_validation->set_error_delimiters('', '<br/>');

			if ($this->form_validation->run()==FALSE) {
				echo json_encode(array("error"=>$this->form_validation->error_String()));
			}
			else
			{
				$currentbalance = $this->MReseller->get_balance($this->session->userdata('reseller_id'));	
				if($this->input->post('amount') <= $currentbalance){
					if ($this->MRequest->create_send()) {       
						echo json_encode(array('success'=>'true','msg'=>"Request has been sent. It is in the pending list now. When it is processed status will be chnaged to 'success'.")); exit;
					}
					else{
						echo json_encode(array('success'=>'false','error'=>"Data didn't save.")); exit;
					}
				}
				else{
					echo json_encode(array('success'=>'false','error'=>"You havn't got enough balance.")); exit;
				
				}
			}
			
				
		}		
		else{
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'request';
			$data['retypes'] = $this->MRequest->get();	

			$data['currentbalance'] = $this->MReseller->get_balance($this->session->userdata('reseller_id'));	
			
			$data['eur_to_bdt'] = $this->eur_to_bdt;
				
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/request/send', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/request/send';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
	    }    
	} 

	public function change_status($request_id){
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}

		if($this->input->post('status_to'))
		{				
			switch ($this->input->post('status_to')) {
				case 'success':
					if (!$this->input->post('transaction_id')) {
						echo json_encode(array('success'=>'false','error'=>"Please enter transaction number.")); exit;
					}
					if($this->MRequest->update($request_id, array('status' => 'success', 'transaction_id' => $this->input->post('transaction_id')))){
						$data['request'] = $this->MRequest->get_by_id($request_id);	
						if (!$data['request']['amount_bdt']) {
							$rate = $this->MDailyRates->latest_eur_to_bdt($data['request']['request_date_time']);
							$data['request']['amount_bdt'] = $data['request']['amount'] * $rate['inverse_value'];							
						}

						$content = $this->load->view($this->config->item('admin_theme').'/request/request', $data, true);
						
						echo json_encode(array('success'=>'true','msg'=>"Request status has been set to success.",'content' => $content)); exit;
					} else {
						echo json_encode(array('success'=>'false','error'=>"Status can't be changed!.")); exit;
					}
					break;

				case 'failed':
					if ($this->input->post('do_refund')==1) {
						if ($this->MRequest->failed_and_refund($request_id)) {
							$data['request'] = $this->MRequest->get_by_id($request_id);	
							if (!$data['request']['amount_bdt']) {
								$rate = $this->MDailyRates->latest_eur_to_bdt($data['request']['request_date_time']);
								$data['request']['amount_bdt'] = $data['request']['amount'] * $rate['inverse_value'];							
							}

							$content = $this->load->view($this->config->item('admin_theme').'/request/request', $data, true);

							echo json_encode(array('success'=>'true','msg'=>"Request status has been set to as failed and refunded the amount.",'content' => $content)); exit;
						} else {
							echo json_encode(array('success'=>'false','error'=>"Status can't be changed!.")); exit;
						}
					} else {						
						if($this->MRequest->update($request_id, array('status' => 'failed'))){
							$data['request'] = $this->MRequest->get_by_id($request_id);	
							if (!$data['request']['amount_bdt']) {
								$rate = $this->MDailyRates->latest_eur_to_bdt($data['request']['request_date_time']);
								$data['request']['amount_bdt'] = $data['request']['amount'] * $rate['inverse_value'];							
							}
							$content = $this->load->view($this->config->item('admin_theme').'/request/request', $data, true);

							echo json_encode(array('success'=>'true','msg'=>"Request status has been set to as failed.",'content' => $content)); exit;
						} else {
							echo json_encode(array('success'=>'false','error'=>"Status can't be changed!.")); exit;
						}
					}
					break;

				case 'pending':
					if($this->MRequest->update($request_id, array('status' => 'pending'))){
							$data['request'] = $this->MRequest->get_by_id($request_id);	
							if (!$data['request']['amount_bdt']) {
								$rate = $this->MDailyRates->latest_eur_to_bdt($data['request']['request_date_time']);
								$data['request']['amount_bdt'] = $data['request']['amount'] * $rate['inverse_value'];							
							}
							$content = $this->load->view($this->config->item('admin_theme').'/request/request', $data, true);

							echo json_encode(array('success'=>'true','msg'=>"Request status has been set to as pending.",'content' => $content)); exit;
						} else {
							echo json_encode(array('success'=>'false','error'=>"Status can't be changed!.")); exit;
						}
					break;				
			}
		} else {
			$data['title'] = $this->config->item('company_name');
			$data['menu'] = 'request';
			$data['retypes'] = $this->MRequest->get();	

			$data['request'] = $this->MRequest->get_by_id($request_id);	
			$rate = $this->MDailyRates->latest_eur_to_bdt($data['request']['request_date_time']);
			$data['request']['amount_bdt'] = $data['request']['amount'] * $rate['inverse_value'];

			$data['status_list'] = array('success','pending','failed');	
				
			if(is_ajax()){
	            $this->load->view($this->config->item('admin_theme').'/request/status_change', $data);
	            return;
	        }
	        $data['content'] = $this->config->item('admin_theme').'/request/status_change';
	        $this->load->view($this->config->item('admin_theme').'/template', $data);
		}
	}

	public function do_refund(){
		if (!in_array($this->session->userdata('user_type'), array('Super Admin','Super User'))) {
			show_404();
			return;
		}
		
		if ($this->input->post('do_refund')==1 AND $this->input->post('request_id')) {
			if ($this->MRequest->failed_and_refund($this->input->post('request_id'))) {
				$data['request'] = $this->MRequest->get_by_id($this->input->post('request_id'));	
				if (!$data['request']['amount_bdt']) {
					$rate = $this->MDailyRates->latest_eur_to_bdt($data['request']['request_date_time']);
					$data['request']['amount_bdt'] = $data['request']['amount'] * $rate['inverse_value'];							
				}
				$content = $this->load->view($this->config->item('admin_theme').'/request/request', $data, true);

				echo json_encode(array('success'=>'true','msg'=>"Request status has been set to as failed and refunded the amount.",'content' => $content)); exit;
			} else {
				echo json_encode(array('success'=>'false','error'=>"Status can't be changed!.")); exit;
			}
		}
	}

}