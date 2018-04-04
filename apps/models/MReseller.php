<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MCompany extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_all(){
		$this->db->where('deleted',0);
		$query=$this->db->get('companies');
		return $query->result_array();

	}

	public function get_by_id($id=null){

		$this->db->where('company_id',$id);
		$query=$this->db->get('companies');
		return $query->row_array();

	}

	public function get_by_name($company_name)
	{
		$data = array();
		$this->db->where('company_name', $company_name);
		$this->db->limit(1);
		$q = $this->db->get('companies');
		if ($q->num_rows() > 0)
		{
			$data = $q->row_array();
		}

		$q->free_result();
		return $data;
	}

	public function create()
	{	
		$this->db->trans_start();
		$data = array(
			
			'company_name' => $this->input->post('company_name'),
			'contact_name' => $this->input->post('contact_name'),
			'address' => $this->input->post('address'),
			'phone' => $this->input->post('phone'),
			'email' => $this->input->post('email'),
			'created' => $this->input->post('created'),
			'status' => $this->input->post('status'),
			'deleted'=>0
		);

		$this->db->insert('companies', $data);
		$company_id = $this->db->insert_id();		

		$this->db->trans_complete();
		
		if($this->db->trans_status() === TRUE)
			return TRUE;
		else
			return FALSE;
	}

	public function update(){

		$data=array(

			'company_name' => $this->input->post('company_name'),
			'contact_name' => $this->input->post('contact_name'),
			'address' => $this->input->post('address'),
			'phone' => $this->input->post('phone'),
			'email' => $this->input->post('email'),
			'created' => $this->input->post('created'),
			'status' => $this->input->post('status')
			);

		$this->db->where('company_id',$this->input->post('id'));
		$this->db->update('companies',$data);		
		return true;
	}
	
	public function delete_by_id($id){
		$data=array('deleted'=>1);
		$this->db->where('company_id',$id);
		$this->db->update('companies', $data);
		return true ;				
	}

}
