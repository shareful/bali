<?php

/**
 * This is Purchase Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Purchase_model extends My_Model {
	protected $_table = 'purchase_master';
	protected $primary_key = 'id';
	protected $protected_atributes = array('id');
	protected $soft_delete = true;
	protected $soft_delete_key = 'deleted';
	
	protected $belongs_to = array(
			'company'=>array(
				'model'=>'company_model',
				'primary_key'=>'company_id',
			),
			'supplier'=>array(
				'model'=>'supplier_model',
				'primary_key'=>'supplier_id',
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
        array( 'field' => 'supplier_id', 
               'label' => 'Supplier',
               'rules' => 'required' ),
        array( 'field' => 'project_id', 
               'label' => 'Project',
               'rules' => 'required' ),
        array( 'field' => 'item_id', 
               'label' => 'Item',
               'rules' => 'required' ),
        // array( 'field' => 'code', 
        //        'label' => 'Bill No',
        //        'rules' => 'required|numeric' ),
        array( 'field' => 'quantity', 
               'label' => 'Quantity',
               'rules' => 'required|numeric|greater_than[0]' ),
        array( 'field' => 'price', 
               'label' => 'Price',
               'rules' => 'required|numeric|greater_than[0]' ),
        array( 'field' => 'bill_date',
               'label' => 'Bill Date',
               'rules' => 'required' ),
        array( 'field' => 'security_perc',
               'label' => 'Security Percentage',
               'rules' => 'required|numeric|greater_than_equal_to[0]' ),
        // array( 'field' => 'security_amount',
        //        'label' => 'Security Amount',
        //        'rules' => 'required|numeric' ),
        // array( 'field' => 'total_amount',
        //        'label' => 'Total Amount',
        //        'rules' => 'required|numeric' ),
        // array( 'field' => 'payable_amount',
        //        'label' => 'Amount to Pay (without security)',
        //        'rules' => 'required|numeric' ),
        array( 'field' => 'paid_amount',
               'label' => 'Amount paid',
               'rules' => 'required|numeric|greater_than_equal_to[0]|callback_check_paid_amount' ),        
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
		$this->field->supplier_id = null;
		$this->field->project_id = null;
		$this->field->item_id = null;
		$this->field->code = null;
		$this->field->ref_no = null;
		$this->field->bill_date = date('Y-m-d H:i:s', time());
		$this->field->quantity = null;
		$this->field->price = null;
		$this->field->received = 1;
		$this->field->notes = null;
		$this->field->security_perc = 0;
		$this->field->security_amount = 0;
		$this->field->total_amount = 0;
		$this->field->payable_amount = 0;
		$this->field->paid_amount = 0;
		$this->field->advance_id = 0;
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
	 * Get all list of purchase which are in same company and not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all($project_id=null, $item_id=null){
		$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		$where['deleted'] = 0;
		if ($project_id) {
			$where['project_id'] = $project_id;
		}
		if ($item_id) {
			$where['item_id'] = $item_id;
		}

		$result = parent::with('project')->with('supplier')->with('item')->order_by('code', 'desc')->get_many_by($where);
		return $result;
	}

	/**
	 * Get last created item to get the code.
	 * @access public
	 * @return array
	 */
	public function get_latest($project_id, $supplier_id, $item_id)
    {
    	$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		$where['project_id'] = $project_id;
		$where['supplier_id'] = $supplier_id;
		$where['item_id'] = $item_id;
		$data = parent::with('project')->with('supplier')->with('item')->order_by('code', 'DESC')->get_by($where);		
		return $data;        
    }

    /**
	 * Get last created item to get the code.
	 * @access public
	 * @return array
	 */
	public function get_one($bill_id)
    {
    	$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		$where['id'] = $bill_id;
		$data = parent::with('project')->with('supplier')->with('item')->get_by($where);
		if (!empty($data)) {
			$data->invoice_no = $data->project->code.'-'.$data->supplier->code.'-'.$data->item->code.'-'.$data->code;
			$data->due_amount = number_format(($data->total_amount - $data->paid_amount), 2, '.', '');
		}
		return $data;        
    }

    /**
	 * Get voucher code for new entry.
	 * @access public
	 * @return array
	 */
    public function get_new_code($project_id, $supplier_id, $item_id, $fullcode = false){
    	$bill = $this->get_latest($project_id, $supplier_id, $item_id);
		$code = '';
        if (count($bill) > 0)
        {
        	// remove leading zero
        	$code = ltrim($bill->code, '0');
        	// increment by 1
            $code = $code + 1;
            // add leading zero
            $code = str_pad($code, 4, '0', STR_PAD_LEFT);
            if ($fullcode) {
            	$code = $bill->project->code.'-'.$bill->supplier->code.'-'.$bill->item->code.'-'.$code;
            }
        }
        else
        {        	
        	$code = '0001';
        	if ($fullcode) {
        		$CI =& get_instance();
        		$CI->load->model('item_model', 'item');
        		$CI->load->model('project_model', 'project');
        		$CI->load->model('supplier_model', 'supplier');
        		$item = $CI->item->get($item_id);
        		$project = $CI->project->get($project_id);
        		$supplier = $CI->supplier->get($supplier_id);

        		$code = $project->code.'-'.$supplier->code.'-'.$item->code.'-'.$code;
        	}
        }
        return $code;
    }
}