<?php

/**
 * This is Income Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Income_model extends My_Model {
	protected $_table = 'income';
	protected $primary_key = 'id';
	protected $protected_atributes = array('id');
	
	protected $belongs_to = array(
			'compnay'=>array(
				'model'=>'company_model',
				'primary_key'=>'company_id',
			),
			'project'=>array(
				'model'=>'project_model',
				'primary_key'=>'project_id',
			)
		);

	
	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'code', 
               'label' => 'Voucher #',
               'rules' => 'required' ),
        array( 'field' => 'project_id',
               'label' => 'Project',
               'rules' => 'required' ),
        array( 'field' => 'amount',
               'label' => 'Amount',
               'rules' => 'required|numeric|greater_than[0]' ),
        array( 'field' => 'income_type',
               'label' => 'Income Type',
               'rules' => 'required|in_list[sale,advance,other]|callback_check_income_type' ),
        array( 'field' => 'trans_date',
               'label' => 'Trasaction Date',
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
		$this->field->id = null;
		$this->field->code = null;
		$this->field->project_id = null;
		$this->field->amount = null;
		$this->field->income_type = null;
		$this->field->ref_id = null;
		$this->field->ref_code = null;
		$this->field->trans_date = null;
		$this->field->notes = null;
		$this->field->created = date('Y-m-d H:i:s', time());
		$this->field->created_by = $this->session->userdata('user_id');		
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
		return parent::update($id, $data, $skip_validation);		
	}

	/**
	 * Get all list of income which are in same company and not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all(){
		$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		// $where['deleted'] = 0;
		$result = parent::order_by('code', 'desc')->get_many_by($where);
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
            $voucher_code = str_pad($voucher_code, 6, '0', STR_PAD_LEFT);
        }
        else
        {
        	$voucher_code = '100001';
        }

        return $voucher_code;
    }
}