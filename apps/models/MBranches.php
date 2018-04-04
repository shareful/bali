<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class MBranches extends CI_Model
{
	public function get_all(){

		$this->db->select('*');
		$this->db->where('branches.company_id', $this->session->userdata('user_company'));
		$this->db->where('deleted',0);
		$query=$this->db->get("branches");
		return $query->result_array();
	}

    public function get_by_id($id)
    {
        $data = array();
        $this->db->where('id', $id);
        $q = $this->db->get('branches');
        if ($q->num_rows() > 0)
        {
            foreach ($q->result_array() as $row)
            {
                $data = $row;
            }
        }

        $q->free_result();
        return $data;
    }

	public function insert(){
		$this->load->helper('url');
		$data=array(
			'company_id' => $this->session->userdata('user_company'),
			'code'=>$this->input->post('code'),
			'branch_name'=>$this->input->post('name'),
			'branch_address'=>$this->input->post('address'),
			'branch_city'=>$this->input->post('city'),
			'branch_zip'=>$this->input->post('zip'),
			'contact_person'=>$this->input->post('person'),
			'branch_mobile'=>$this->input->post('mobile'),
			'deleted'=>0

			);
		return $this->db->insert('branches',$data);
	}

	public function update(){
		$data=array(
			'company_id' => $this->session->userdata('user_company'),
			'code'=>$this->input->post('code'),
			'branch_name'=>$this->input->post('name'),
			'branch_address'=>$this->input->post('address'),
			'branch_city'=>$this->input->post('city'),
			'branch_zip'=>$this->input->post('zip'),
			'contact_person'=>$this->input->post('person'),
			'branch_mobile'=>$this->input->post('mobile')

			);

		$this->db->where('id',$this->input->post('id'));
		$this->db->update('branches',$data);
		return true;
	}

	public function get_branch_wihtout_session(){
		$branch_id= $this->session->userdata('user_branch');
		$this->db->where('id<',$branch_id);
		$this->db->or_where('id>',$branch_id);
		$query=$this->db->get("branches");
		return $query->result_array();
	}


}
  ?>