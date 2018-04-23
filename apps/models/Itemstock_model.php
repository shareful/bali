<?php

/**
 * This is Itemstock Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Itemstock_model extends My_Model {
	protected $_table = 'item_stock';
	protected $primary_key = 'id';
	protected $protected_atributes = array('id');
	
	
	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'item_id', 
               'label' => 'Item Id',
               'rules' => 'required|numeric' ),
        array( 'field' => 'project_id',
               'label' => 'Project Id',
               'rules' => 'required|numeric' ),
        array( 'field' => 'stock',
               'label' => 'Stock',
               'rules' => 'required|numeric' ),
    );
	
	/**
	 * Contains Table fields
	 */
	private $field;
	
	public function __construct() {
		parent::__construct();
		// Initializing table fields with null
		$this->field = new stdClass;
		$this->field->item_id = null;
		$this->field->project_id = null;
		$this->field->stock = 0;
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
	 * Get all list of item_stock which are in same company and not deleted (soft deleted) 
	 * @access public
	 * @return array
	 */
	public function get_list_all($project_id=null){
		$where = array();
		$where['items.company_id'] = $this->session->userdata('company_id');
		$where['items.deleted'] = 0;
				
		if ($project_id) {
			$this->db->select('items.*, '.$this->_table.'.stock, projects.name as project_name, projects.project_id');
			$this->db->join('project_items', 'project_items.project_id='.$project_id.' AND project_items.item_id=items.item_id');
			$this->db->join($this->_table, $this->_table.'.item_id=project_items.item_id AND '.$this->_table.'.project_id='.$project_id, 'left');
			$this->db->join('projects', 'projects.project_id='.$project_id, 'left');
		} else {
			$this->db->select('items.*, SUM('.$this->_table.'.stock) as stock, projects.name as project_name, projects.project_id');
			$this->db->join($this->_table, $this->_table.'.item_id=items.item_id', 'left');
			$this->db->join('projects', 'projects.project_id='.$this->_table.'.project_id', 'left');
		}

		$this->db->where($where);
		$this->db->group_by('items.item_id');
		$this->db->order_by('items.name', 'asc');

		$result = $this->db->get('items')->result();
		// echo $this->db->last_query(); exit();
		return $result;
	}	

	/**
	 * Get an item info with stock details 
	 * @access public
	 * @return array
	 */
	public function get_item($item_id, $project_id){
		$where = array();
		$where['items.company_id'] = $this->session->userdata('company_id');
		$where['items.deleted'] = 0;
		$where['items.item_id'] = $item_id;
				
		$this->db->select('items.*, COALESCE('.$this->_table.'.stock , 0) AS stock, projects.name as project_name, projects.project_id');
		$this->db->join($this->_table, $this->_table.'.item_id=items.item_id AND '.$this->_table.'.project_id='.$project_id, 'left');
		$this->db->join('projects', 'projects.project_id='.$project_id, 'left');
	

		$this->db->where($where);
		return $this->db->get('items')->row();
	}

	public function updateStock($item_id, $project_id, $quantity){
		$row = parent::get_by(array('item_id'=> $item_id, 'project_id'=>$project_id));
		if (empty($row)) {
			$this->insert(array('item_id'=> $item_id, 'project_id'=>$project_id, 'stock'=>$quantity));
		} else {
			$this->db->set('stock', 'stock+'.$quantity, FALSE);
			$this->db->where(array('item_id'=> $item_id, 'project_id'=>$project_id));
			$this->db->update($this->_table);
		}
	}
}