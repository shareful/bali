<?php

/**
 * This is Account Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Account_model extends My_Model {
	protected $_table = 'accounts';
	protected $primary_key = 'acc_id';
	protected $protected_atributes = array('acc_id');
	

	protected $has_many = array(
			'subaccount'=>array(
				'model'=>'subaccount_model',
				'primary_key'=>'sub_acc_id',
			)
		);	
	
	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'code', 
               'label' => 'Account Code',
               'rules' => 'required' ),
        array( 'field' => 'name',
               'label' => 'Account Name',
               'rules' => 'required' ),
        array( 'field' => 'have_sub',
               'label' => 'Have Sub Account',
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
		$this->field->acc_id = null;
		$this->field->code = null;
		$this->field->name = null;
		$this->field->have_sub = 0;
		$this->field->notes = 0;
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
		$where['company_id'] = $this->session->userdata('company_id');
		
		if (!empty($where)) {
			$this->db->where($where);
		}
		
		$this->db->order_by('name','asc');			
		$query = $this->db->get($this->_table);
		return $query->result2($this->primary_key, 'name');	
	}	

	/**
	 * Get all list of accounts which are in same company and not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all(){
		$where = array();
		$where['company_id'] = $this->session->userdata('company_id');
		$result = parent::order_by('name', 'asc')->get_many_by($where);
		return $result;
	}

	/**
	 * Get balance of an account
	 * @access public
	 * @param integer $acc_id
	 * @return double|integer
	 */
	public function get_balance($acc_id, $project_id=null){

		// get Income total
		$this->db->select('SUM(amount) as total');

		if ($project_id){
			$this->db->where('project_id', $project_id);
		}

		$this->db->where('company_id', $this->session->userdata('company_id'));
		$this->db->where('acc_id', $acc_id);
		$income = $this->db->get('income')->row()->total;

		// get Expense total
		$this->db->select('SUM(amount) as total');
		
		if ($project_id){
			$this->db->where('project_id', $project_id);
		}

		$this->db->where('company_id', $this->session->userdata('company_id'));
		$this->db->where('acc_id', $acc_id);
		$expense = $this->db->get('expense')->row()->total;

		return $income - $expense;
	}

	/**
	 * Get Statement of an account, subaccount
	 * @access public
	 * @param array $where
	 * @return array
	 */
	public function get_statement($where=array()){
		$where_sql = "";
		if (!empty($where)) {
			$where_sql .= " WHERE ". implode(" AND ", $where);
		}

		$sql = "SELECT project_id, item_id, code, (-1 * amount) as amount, exp_type as trans_type, ref_id, ref_code, trans_date, notes, acc_id, sub_acc_id, check_trans_no, created, created_by FROM expense ".$where_sql."
			UNION 
			SELECT project_id, NULL as item_id, code, amount, income_type as trans_type, ref_id, ref_code, trans_date, notes, acc_id, sub_acc_id, check_trans_no, created, created_by FROM income ".$where_sql;


		$sql .= " ORDER BY trans_date ASC;";
		// echo $sql; exit();
		return $this->db->query($sql)->result();
	}

	public function get_custom_balance($where=array()){
		$where_sql = "";
		if (!empty($where)) {
			$where_sql .= " WHERE ". implode(" AND ", $where);
		}

		$sql = "SELECT project_id, item_id, code, SUM((-1 * amount)) as amount , exp_type as trans_type, ref_id, ref_code, trans_date, notes, acc_id, sub_acc_id, check_trans_no, created, created_by FROM expense ".$where_sql."
			UNION 
			SELECT project_id, NULL as item_id, code, SUM(amount) as amount, income_type as trans_type, ref_id, ref_code, trans_date, notes, acc_id, sub_acc_id, check_trans_no, created, created_by FROM income ".$where_sql;

		$sql .= " ORDER BY trans_date ASC;";
		$rows = $this->db->query($sql)->result();

		$total = 0;
		foreach ($rows as $row) {
			$total += $row->amount;
		}

		$total = number_format($total, 2, ".", "");
		return $total;
	}
}