<?php

/**
 * This is Securityadjust Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Securityadjust_model extends My_Model {
	protected $_table = 'security_adjustments';
	protected $primary_key = 'id';
	protected $protected_atributes = array('id');
	protected $soft_delete = true;
	protected $soft_delete_key = 'deleted';
	
	
	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'security_id', 
               'label' => 'Security Code #',
               'rules' => 'required' ),
        array( 'field' => 'bill_id', 
               'label' => 'Bill #',
               'rules' => 'required' ),
        array( 'field' => 'trans_date', 
               'label' => 'Transaction Date',
               'rules' => 'required' ),
        array( 'field' => 'amount', 
               'label' => 'Amount',
               'rules' => 'required|numeric|greater_than[0]' ),
        array( 'field' => 'trans_type', 
               'label' => 'Payment source',
               'rules' => 'required|in_list[given,taken]' ),
    );
	
	/**
	 * Contains Table fields
	 */
	private $field;
	
	public function __construct() {
		parent::__construct();
		// Initializing table fields with null
		$this->field = new stdClass;
		$this->field->id = null;
		$this->field->trans_type = null;
		$this->field->security_id = null;
		$this->field->bill_id = null;
		$this->field->amount = null;
		$this->field->trans_date = null;
		$this->field->deleted = 0;
		$this->field->created = date('Y-m-d H:i:s', time());
		$this->field->created_by = $this->session->userdata('user_id');
		$this->field->modified = date('Y-m-d H:i:s', time());
		$this->field->modified_by = $this->session->userdata('user_id');
	}	

	/**
	 * Set Table field/column with value
	 * @access public
	 * @param String
	 * @param String
	 * @return Bolean
	 */
	public function set_value($key, $val) {
		if (array_key_exists($key, get_object_vars($this->field))) {
			// echo "{$key}={$val}<br>";
			$this->field->$key = $val;
			return true;
		}else{
			// echo "{$key}=Not Available<br>";
			return false;
		}
	}


	/**
	 * Get Table field/column value
	 * @access public
	 * @param String
	 * @return mixed
	 */
	public function get_value($key) {
		if (array_key_exists($key, get_object_vars($this->field))) {
			// echo "{$key}={$val}<br>";
			return $this->field->$key;
		}else{
			return false;
		}
	}
	

	/**
	 * parent::insert() method Overriding 
	 * @access public
	 * @param array
	 * @param bolean
	 * @return int|bolean
	 */
	public function insert($data=null, $skip_validation = false) {
		if (is_null($data)) {
			$data = (array) $this->field;
			// force to skip validation
			$skip_validation = true;
		}			
		$data['created'] = date('Y-m-d H:i:s', time());
		$data['created_by'] = $this->session->userdata('user_id');
		return parent::insert($data,$skip_validation);		
	}
	
	/**
	 * parent::update() method Overriding 
	 * @access public
	 * @param array
	 * @param bolean
	 * @return int|bolean
	 */
	public function update($id, $data=null, $skip_validation = false) {
		if (is_null($data)) {
			$data = (array) $this->field;
			// force to skip validation
			$skip_validation = true;
		}	
		$data['modified'] = date('Y-m-d H:i:s', time());
		$data['modified_by'] = $this->session->userdata('user_id');
		return parent::update($id, $data, $skip_validation);		
	}

	public function get_option_list($where = array()){
		if (!empty($where)) {
			$this->db->where($where);
		}
		
		$this->db->order_by('id','asc');			
		$query = $this->db->get($this->_table);
		return $query->result2($this->primary_key, 'amount');	
	}	

	/**
	 * Get all list of project payments which are not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all($trans_type, $security_id=null, $bill_id=null){
		$where = array();
		$where['deleted'] = 0;
		$where['trans_type'] = $trans_type;
		
		if ($security_id) {
			$where['security_id'] = $security_id;
		}

		if ($bill_id) {
			$where['bill_id'] = $bill_id;
		}
		
		$result = parent::order_by('id', 'desc')->get_many_by($where);
		return $result;
	}

}