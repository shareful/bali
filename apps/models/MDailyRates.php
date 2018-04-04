<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* 
*/
class MDailyRates extends CI_Model
{
	public function get_todays_rate(){
		$this->db->select('*');
		$this->db->where('effecttive_date', date("Y-m-d"));
		$query=$this->db->get("daily_rate");
		return $query->result_array();
	}

    public function get_eur_to_bdt($date=null)
    {
    	if (is_null($date)) {
    		$date = date("Y-m-d");
    	}
        $data = array();
        if ($date) {
        	$this->db->where('currency_from', 'EUR');
	        $this->db->where('currency_to', 'BDT');
			$this->db->where('effecttive_date', $date);
	        $q = $this->db->get('daily_rate');
	        $data = $q->row_array();
	        $q->free_result();
        }
        if (isset($data['inverse_value'])) {
        	$data['inverse_value'] = round($data['inverse_value'], 2);
        }
        return $data;
    }

    public function latest_eur_to_bdt($date=null)
    {
    	$this->db->select('MAX(effecttive_date) as max_date');
    	if (!is_null($date)) {
    		$this->db->where('effecttive_date <=', $date);
    	}
	    $query = $this->db->get('daily_rate');
	    $max_date = $query->row()->max_date;
	    return $this->get_eur_to_bdt($max_date);	    
    }

	public function insert(){
		$current_date=date("Y-m-d");

	    $data_eur_to_bdt=array(
	        'currency_from'=>'EUR',
	        'currency_to'=>'BDT',
	        'value'=>1,
	        'inverse_value'=>$this->input->post('value_bdt'),
	        'effecttive_date'=>$current_date
	        );

	    $this->db->insert('daily_rate',$data_eur_to_bdt);
	    return true;
	}

	public function update(){
		$current_date=date("Y-m-d");

		$data_eur_to_bdt=array(
	        'value'=>$this->input->post('value_eur'),
	        'inverse_value'=>$this->input->post('value_bdt'),
	        );

		$this->db->where('effecttive_date', $current_date);
	    $this->db->where('currency_from', 'EUR');
	    $this->db->where('currency_to', 'BDT');
	    $this->db->update('daily_rate', $data_eur_to_bdt);
		return true;
	}

}
  ?>