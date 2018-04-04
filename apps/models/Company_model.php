<?php

/**
 * This is Company Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Company_model extends My_Model {
	protected $_table = 'companies';
	protected $primary_key = 'company_id';
	protected $protected_atributes = array('company_id');
	
	protected $has_many = array(
			'user'=>array(
				'model'=>'user_model',
				'primary_key'=>'user_id',
			)
		);	

	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'company_name', 
               'label' => 'Company Name',
               'rules' => 'required|is_unique[companies.company_name]' ),
        array( 'field' => 'address',
               'label' => 'Address',
               'rules' => 'required' ),
        array( 'field' => 'phone',
               'label' => 'Phone',
               'rules' => 'required' ),
        array( 'field' => 'status',
               'label' => 'Status',
               'rules' => 'required' ),
    );
	
	/**
	 * Contains Table fields
	 */
	private $field;
	
	public function __construct() {
		parent::__construct();
		// Initializing table fields with null
		$this->field = new stdClass;
		$this->field->company_id = null;
		$this->field->code = null;
		$this->field->company_name = null;
		$this->field->address = null;
		$this->field->area = null;
		$this->field->city = null;
		$this->field->zip = null;
		$this->field->country = null;
		$this->field->phone = null;
		$this->field->email = null;
		$this->field->url = null;
		$this->field->contact_name = null;
		$this->field->mobile_no = null;
		$this->field->currency_id = null;
		$this->field->currency_symbol_position = null;
		$this->field->logo = null;
		$this->field->status = null;
		$this->field->is_sync = 0;
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

	
}