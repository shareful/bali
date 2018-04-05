<?php

/**
 * This is Advancetaken Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Advancetaken_model extends My_Model {
	protected $_table = 'advance_taken';
	protected $primary_key = 'id';
	protected $protected_atributes = array('id');
	protected $soft_delete = true;
	protected $soft_delete_key = 'deleted';
	
	protected $belongs_to = array(
			'company'=>array(
				'model'=>'company_model',
				'primary_key'=>'company_id',
			),
			'customer'=>array(
				'model'=>'customer_model',
				'primary_key'=>'customer_id',
			),
			'project'=>array(
				'model'=>'project_model',
				'primary_key'=>'project_id',
			),
			'item'=>array(
				'model'=>'item_model',
				'primary_key'=>'item_id',
			),

		);
	
	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'project_id', 
               'label' => 'Project',
               'rules' => 'required' ),
        array( 'field' => 'customer_id', 
               'label' => 'Customer',
               'rules' => 'required' ),
        array( 'field' => 'code', 
               'label' => 'Code',
               'rules' => 'required' ),
        array( 'field' => 'trans_date', 
               'label' => 'Transaction Date',
               'rules' => 'required' ),
        array( 'field' => 'amount', 
               'label' => 'Amount',
               'rules' => 'required|numeric|greater_than[0]' ),
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
		$this->field->company_id = $this->session->userdata('company_id');
		$this->field->project_id = null;
		$this->field->item_id = null;
		$this->field->customer_id = null;
		$this->field->code = null;
		$this->field->ref_no = null;
		$this->field->amount = null;
		$this->field->amount_adjusted = 0;
		$this->field->notes = 0;
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
	 * Get all list of project payments which are not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all($project_id=null, $item_id= null, $customer_id=null){
		$where = array();
		$where['deleted'] = 0;
		$where['company_id'] = $this->session->userdata('company_id');
		if ($project_id) {
			$where['project_id'] = $project_id;
		}
		if ($item_id) {			
			$where['item_id'] = $item_id;
		}
		if ($customer_id) {
			$where['customer_id'] = $customer_id;
		}

		$result = parent::with('project')->with('customer')->with('item')->order_by('id', 'desc')->get_many_by($where);
		return $result;
	}

	/**
	 * Get last created project to get the code.
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

    /**
	 * Get voucher code for new entry.
	 * @access public
	 * @return array
	 */
    public function get_new_code(){
    	$voucher = $this->get_latest();
		$voucher_code = '';
        if (count($voucher) > 0)
        {
        	// remove leading zero
        	$voucher_code = ltrim($voucher->code, '0');
        	// increment by 1
            $voucher_code = $voucher_code + 1;
            // add leading zero
            $voucher_code = str_pad($voucher_code, 5, '0', STR_PAD_LEFT);
        }
        else
        {
        	$voucher_code = '10001';
        }

        return $voucher_code;
    }

}