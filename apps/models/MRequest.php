<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MRequest extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_all($reseller_id=null){
		$this->db->select('request.*, resellers.company_name, resellers.balance, request_types.name as request_type');
		$this->db->join('resellers','resellers.reseller_id=request.reseller_id','left');
		$this->db->join('request_types','request_types.request_type_id=request.request_type_id','left');
		if (!is_null($reseller_id)) {
			$this->db->where('request.reseller_id', $reseller_id);
		}
		$this->db->order_by('request_id','desc');
		$query=$this->db->get('request');
		return $query->result_array();

	}

	public function get_by_id($request_id){
		$this->db->select('request.*, resellers.company_name, resellers.balance, request_types.name as request_type');
		$this->db->join('resellers','resellers.reseller_id=request.reseller_id','left');
		$this->db->join('request_types','request_types.request_type_id=request.request_type_id','left');
		
		$this->db->where('request.request_id', $request_id);
		$query=$this->db->get('request');
		return $query->row_array();

	}

	public function get(){
		// $this->db->where('request_type_id',$id);
		$query=$this->db->get('request_types');
		return $query->result_array();

	}

	public function create_send()
	{	
		$this->db->trans_start();
			
		$data = array(
			'request_type_id' => $this->input->post('name'),
			'reseller_id' => $this->session->userdata('reseller_id'),
			'request_date_time' => ($this->input->post('request_date_time') !="" ? $this->input->post('request_date_time').' '.date("H:i:s") : date("Y-m-d H:i:s") ),
			'amount' => $this->input->post('amount'),
			'amount_bdt' => $this->input->post('amount_bdt'),
			'send_to' => $this->input->post('send_to'),
			'comment' => $this->input->post('comment')
		);

		$this->db->insert('request', $data);
		$request_id = $this->db->insert_id();

		$this->db->set('balance','balance-'.$this->input->post('amount'),FALSE);
        $this->db->where('reseller_id',$this->session->userdata('reseller_id'));         
        $this->db->update('resellers');

		$data = array(			
			'reseller_id' => $this->session->userdata('reseller_id'),
			'amount' => (-1.00)*$this->input->post('amount'),
			'amount_bdt' => (-1.00)*$this->input->post('amount_bdt'),
			'comment' => $this->input->post('comment'),
			'trasncation_date' => ($this->input->post('request_date_time') !="" ? $this->input->post('request_date_time').' '.date("H:i:s") : date("Y-m-d H:i:s") ),
			'ref_id' => $request_id,
			'transaction_type' => 'request'
		);

		$this->db->insert('transaction', $data);
		
		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE)
			return TRUE;
		else
			return FALSE;
	}

	public function update($request_id, $data){
		$this->db->where('request_id',$request_id);
		$this->db->update('request',$data);		
		return true;
	}

	public function failed_and_refund($request_id){

		$request = $this->get_by_id($request_id);
		if (empty($request_id)) {
			return FALSE;
		}

		$this->db->trans_start();

		$this->db->where('request_id',$request_id);
		$this->db->update('request', array('status' => 'failed', 'refunded' => 1));	
		// if it is already not refunded yet. then proceed on to refund
		if ($request['refunded'] != 1) {
			$data = array(			
				'reseller_id' => $request['reseller_id'],
				'amount' => $request['amount'],
				'comment' => 'Refund because of failed.',
				'trasncation_date' =>  date("Y-m-d H:i:s") ,
				'ref_id' => $request_id,
				'transaction_type' => 'refund'
			);

			$this->db->insert('transaction', $data);

			$this->db->set('balance', 'balance+'.$request['amount'],FALSE);
	        $this->db->where('reseller_id', $request['reseller_id']);         
	        $this->db->update('resellers');
		}

		$this->db->trans_complete();
		if($this->db->trans_status() === TRUE)
			return TRUE;
		else
			return FALSE;
	}


}
