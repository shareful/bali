<?php

/**
 * This is Supplier Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Supplier_model extends My_Model {
	protected $_table = 'suppliers';
	protected $primary_key = 'supplier_id';
	protected $protected_atributes = array('supplier_id');
	protected $soft_delete = true;
	protected $soft_delete_key = 'deleted';
	
	
	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'code', 
               'label' => 'Supplier Code',
               'rules' => 'required' ),
        array( 'field' => 'name',
               'label' => 'Supplier Name',
               'rules' => 'required' ),
        array( 'field' => 'address',
               'label' => 'Address',
               'rules' => 'required' ),
        array( 'field' => 'phone',
               'label' => 'Phone',
               'rules' => 'required' ),
        array( 'field' => 'contact_person',
               'label' => 'Contact Person',
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
		$this->field->company_id = $this->session->userdata('company_id');
		$this->field->supplier_id = null;
		$this->field->code = null;
		$this->field->name = null;
		$this->field->address = null;
		$this->field->city = null;
		$this->field->zip = null;
		$this->field->country = null;
		$this->field->contact_person = null;
		$this->field->phone = null;
		$this->field->email = null;
		$this->field->web = null;
		$this->field->notes = null;
		$this->field->status = null;
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
		
		$this->db->order_by('name','asc');			
		$query = $this->db->get($this->_table);
		return $query->result2($this->primary_key, 'name');	
	}	

	/**
	 * Get all list of suppliers which are in same company and not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all(){
		$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		$where['deleted'] = 0;
		$result = parent::order_by('name', 'asc')->get_many_by($where);
		return $result;
	}

	/**
	 * Get last created supplier to get the code.
	 * @access public
	 * @return array
	 */
	public function get_latest()
    {
    	$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		$data = parent::order_by('code', 'DESC')->get_by($where);		
		return $data;        
    }
}