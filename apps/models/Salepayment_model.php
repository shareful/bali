<?php

/**
 * This is Salepayment Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Salepayment_model extends My_Model {
	protected $_table = 'sale_payments';
	protected $primary_key = 'id';
	protected $protected_atributes = array('id');
	protected $soft_delete = true;
	protected $soft_delete_key = 'deleted';
	
	protected $belongs_to = array(
			'sale'=>array(
				'model'=>'sale_model',
				'primary_key'=>'bill_id',
			),			
		);
	
	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'bill_id', 
               'label' => 'Bill',
               'rules' => 'required' ),
        array( 'field' => 'trans_date', 
               'label' => 'Payment Date',
               'rules' => 'required' ),
        array( 'field' => 'amount', 
               'label' => 'Amount',
               'rules' => 'required|numeric|greater_than[0]|callback_check_received_amount' ),
        array( 'field' => 'src_type', 
               'label' => 'Payment source',
               'rules' => 'required|in_list[bill,advance,security,other]' ),
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
		$this->field->bill_id = null;
		$this->field->amount = null;
		$this->field->src_type = null;
		$this->field->ref_id = null;
		$this->field->notes = null;
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
	 * Get all list of sale payments which are not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all($bill_id=null, $advance_id= null, $security_id=null){
		$where = array();
		$where['deleted'] = 0;
		if ($bill_id) {
			$where['bill_id'] = $bill_id;
		}
		if ($advance_id) {
			$where['src_type'] = 'advance';
			$where['ref_id'] = $advance_id;
		}
		if ($security_id) {
			$where['src_type'] = 'security';
			$where['ref_id'] = $security_id;
		}

		$result = parent::with('sale')->order_by('id', 'desc')->get_many_by($where);
		return $result;
	}

}