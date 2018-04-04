<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class MAccount extends CI_Model
{
	public function __construct(){
		parent::__construct();
	}


	public function create($param){

		$data=array(
			'reseller_id' => $param['reseller_id'],
			'amount' => $param['amount'],
			'amount_bdt' => $param['amount_bdt'],
			'comment' => $param['comment'],
			'date' => $param['date'],
			'status' => $param['status']
			);

		$this->db->insert('acount_deposit',$data);
		return true;
	}


	public function get_all(){
		$this->db->select('acount_deposit.*, resellers.company_name, resellers.balance');
		$this->db->join('resellers','resellers.reseller_id=acount_deposit.reseller_id','left');
		$this->db->order_by('deposit_id','desc');
		$query=$this->db->get('acount_deposit');
		return $query->result_array();
	}


	public function get_by_id($id=null){

		$this->db->where('deposit_id',$id);
		$query=$this->db->get('acount_deposit');
		return $query->row_array();

	}

	/*public function get_by_reseller_id($id){
		$data = array();
		$this->db->where('reseller_id', $id);
		$this->db->limit(1);
		$q = $this->db->get('acount_deposit');
		if ($q->num_rows() > 0)
		{
			$data = $q->row_array();
		}

		$q->free_result();
		return $data;

	}*/

	/*public function update_depositor($param){

		$data=array(
			'reseller_id'=> $param['reseller_id'],
			'amount'=> $param['amount'],
			'comment'=> $param['comment'],
			'date'=> isset($param['date']) ? $param['date'] : now(),
			'status'=> $param['status']
			);

		$this->db->where('deposit_id',$this->input->post('id'));
		$this->db->update('acount_deposit',$data);
	}*/


	public function transection_ledger($param){

		$data=array(
			'reseller_id' => $param['reseller_id'],
			'amount' => $param['amount'],
			'amount_bdt' => $param['amount_bdt'],
			'comment' => $param['comment'],
			'trasncation_date' => isset($param['trasncation_date']) ? $param['trasncation_date'].' '.date("H:i:s") : date("Y-m-d H:i:s"),
			'ref_id' => $param['ref_id'],
			'transaction_type' => $param['transaction_type'],
			);

		$this->db->insert('transaction',$data);
	}

	public function get_all_depositor_ledger_by_id($id=null){
		$this->db->where('reseller_id',$id);
		$this->db->order_by('transaction_id', 'desc');
		$query=$this->db->get('transaction');
		return $query->result_array();
	}

}

 ?>