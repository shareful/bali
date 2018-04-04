<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MUsers extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function verify($username, $pw)
	{
		// $pw = '123';
		$this->db->where('username', $username);
		$this->db->where('password', $pw);
		$this->db->where('status', 'active');
		$this->db->limit(1);
		$q = $this->db->get('users');
		if ($q->num_rows() > 0)
		{
			$row = $q->row_array();
			$data['user_id'] = $row['user_id'];
			$data['username'] = $row['username'];
			$data['user_name'] = $row['name'];
			$data['user_email'] = $row['email'];
			$data['user_type'] = $row['type'];
			$data['user_company'] = $row['company_id'];
			$data['company_id'] = $row['company_id'];
			if ($data['user_company']) {
				$company = $this->MCompany->get_by_id($row['company_id']);
				$data['company_name'] = $company['company_name'];
			}
			// if ($company['logo'] != '')
			// {
			// 	$data['company_logo'] = 'uploads/companies/' . $company['logo'];
			// }
			// else
			// {
			// 	$data['company_logo'] = 'assets/'.$this->config->item('theme').'/img/logo.png';
			// }
			// $data['currency_symbol_position'] = $company['currency_symbol_position'];
			// $currency = $this->MCurrencies->get_by_id($company['currency_id']);
			// $data['currency_name'] = $currency['shortname'];
			// $data['currency_symbol'] = $currency['symbol'];

			$this->session->set_userdata($data);
		}
	}

	public function get_by_id($id=null){

		$this->db->where('user_id',$id);
		$query=$this->db->get('users');
		return $query->row_array();
	}

	public function get_by_email($email)
	{
		$data = array();
		$this->db->where('email', $email);
		$this->db->limit(1);
		$q = $this->db->get('users');
		if ($q->num_rows() > 0)
		{
			$data = $q->row_array();
		}

		$q->free_result();
		return $data;
	}

	public function get_by_username($username)
	{
		$data = array();
		$this->db->where('username', $username);
		$this->db->limit(1);
		$q = $this->db->get('users');
		if ($q->num_rows() > 0)
		{
			$data = $q->row_array();
		}

		$q->free_result();
		return $data;
	}

	public function get_where($where=array(), $limit=null)
	{
		$data = array();
		if (!empty($where)) {
			$this->db->where($where);
		}
		if (!is_null($limit)) {
			$this->db->limit($limit);
		}
		$q = $this->db->get('users');
		if ($q->num_rows() > 0)
		{
			if ($limit==1) {
				$data = $q->row_array();
			} else {
				$data = $q->result_array();				
			}
		}

		$q->free_result();
		return $data;
	}

	public function get_by_email_update($email){
		$data = array();
		$id=$this->input->post('id');
		//$where=('email'=$email and 'id'<$id or 'email'=$email and 'id'>$id);
		//$this->db->where('email'=$email and 'id'<$id or 'email'=$email and 'id'>$id);
		$this->db->where('email=',$email);
		$this->db->where('user_id<',$id);
		$this->db->or_where('email=',$email);
		$this->db->where('user_id>',$id);
		$this->db->limit(1);
		$q = $this->db->get('users');
		if ($q->num_rows() > 0)
		{
			$data = $q->row_array();
		}

		$q->free_result();
		return $data;
	}

	public function get_by_username_update($username){
		$data = array();
		$id=$this->input->post('id');
		//$where=('username'=$username and 'id'<$id or 'username'=$username and 'id'>$id);
		//$this->db->where('username'=$username and 'id'<$id or 'username'=$username and 'id'>$id);
		$this->db->where('username=',$username);
		$this->db->where('user_id<',$id);
		$this->db->or_where('username=',$username);
		$this->db->where('user_id>',$id);
		$this->db->limit(1);
		$q = $this->db->get('users');
		if ($q->num_rows() > 0)
		{
			$data = $q->row_array();
		}

		$q->free_result();
		return $data;
	}

	public function get_by_code($code)
	{
		$data = array();
		$this->db->where('code', $code);
		$this->db->limit(1);
		$q = $this->db->get('users');
		if ($q->num_rows() > 0)
		{
			$data = $q->row_array();
		}

		$q->free_result();
		return $data;
	}

	// public function get_all()
	// {
	// 	$data = array();
	// 	$this->db->select('u.id, u.email, u.name,u.branch_id, u.type, u.status, u.created, c.name as c_name');
	// 	$this->db->where('deleted',0);
	// 	$this->db->from('users as u');
	// 	$this->db->join('companies as c', 'u.company_id = c.id', 'left');
	// 	if ($this->session->userdata('user_type') != 'Admin')
	// 	{
	// 		$this->db->where('u.company_id', $this->session->userdata('user_company'));
	// 	}
	// 	if ($this->session->userdata('user_type') == 'Power User')
	// 	{
	// 		$this->db->where('u.type !=', 'Admin');
	// 	}
	// 	if ($this->session->userdata('user_type') == 'User')
	// 	{
	// 		$this->db->where('u.type =', 'User');
	// 	}
	// 	$q = $this->db->get();
	// 	if ($q->num_rows() > 0)
	// 	{
	// 		foreach ($q->result_array() as $row)
	// 		{
	// 			$data[] = $row;
	// 		}
	// 	}

	// 	$q->free_result();
	// 	return $data;
	// }

	public function create($name, $type, $status = 'Inactive')
	{	
		$data = array(
			'name' => $name,
			'company_id' => $this->session->userdata('company_id'),
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'password' => substr(do_hash($this->input->post('password')), 0, 16),
			// 'password_confirmation' => substr(do_hash($this->input->post('password_confirmation')), 0, 16),
			'type' => $type,
			'status' => $status,
			// 'code' => substr(do_hash($this->input->post('email')), 0, 32),
			'created' => date('Y-m-d H:i:s', time()),
			'created_by' => $this->session->userdata('user_id'),
			'deleted' => 0
			);

		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function active_user($id)
	{
		$data = array(
			'status' => 'Active',
			'code' => ''
			);

		$this->db->where('user_id', $id);
		$this->db->update('users', $data);
	}

	public function update($id)
	{
		$data = array(
			'name' => $this->input->post('name'),
			'username' => $this->input->post('username'),
			'email' => $this->input->post('email'),
			'status' => $this->input->post('status'),
			'phone' => $this->input->post('phone'),
			'modified' => date('Y-m-d H:i:s', time()),
			'modified_by' => $this->session->userdata('user_id')
			);
		
		if ($this->input->post('password'))
		{
			$data['password'] = substr(do_hash($this->input->post('password')), 0, 16);
		}
		/*if ($this->input->post('type'))
		{
			$data['type'] = $this->input->post('type');
		}*/

		$this->db->where('user_id', $id);
		$this->db->update('users', $data);
		return true;
	}

	public function update_password($id = NULL)
	{
		$data = array(
			'password' => substr(do_hash($this->input->post('password')), 0, 16),
			'modified' => date('Y-m-d H:i:s', time()),
			'modified_by' => $this->session->userdata('user_id')
			);

		$this->db->where('user_id', $id);
		$this->db->update('users', $data);
	}

	public function update_code($id, $code)
	{
		$data = array(
			'code' => $code,
			'modified' => date('Y-m-d H:i:s', time())
			);

		$this->db->where('user_id', $id);
		$this->db->update('users', $data);
	}

	public function delete($id)
	{
		$data=array('deleted'=>1);
		$this->db->where('user_id', $id);
		$this->db->update('users',$data);
		return true;
	}

	public function delete_by_cmp($cmp_id)
	{
		$data=array('deleted'=>1);
		$this->db->where('company_id', $cmp_id);
		$this->db->update('users',$data);		
	}

}
