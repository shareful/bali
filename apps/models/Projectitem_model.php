<?php

/**
 * This is Project Items Model
 * 
 * 
 * @package         CodeIgniter
 * @subpackage      CAMPE CEWG
 * @category        Model
 * @author          Shareful Islam<km.shareful@gmail.com>
 * @license         Commercial
 */

class Projectitem_model extends My_Model {
	protected $_table = 'project_items';
	protected $primary_key = 'id';
	protected $protected_atributes = array('id');
	protected $belongs_to = array(
			'project'=>array(
				'model'=>'project_model',
				'primary_key'=>'project_id',
			),
			'item'=>array(
				'model'=>'item_model',
				'primary_key'=>'item_id',
			)
		);

	/**
	 * User Table form validation rules
	 */
	public $validate = array(
        array( 'field' => 'project_id', 
               'label' => 'Project',
               'rules' => 'required' ),
        array( 'field' => 'item_id', 
               'label' => 'Item',
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
		$this->field->id = null;
		$this->field->project_id = null;
		$this->field->item_id = null;
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
	public function get_items_not_added($project_id){
		$where = array();
		$where['items.company_id'] = $this->session->userdata('company_id');
		$where['items.deleted'] = 0;
		$this->db->where($where);
		$this->db->where('items.item_id NOT IN(select project_items.item_id FROM project_items WHERE project_items.project_id='.$project_id.')');
		$this->db->order_by('items.name', 'asc');

		return $this->db->get('items')->result();		
	}	

	/**
	 * Get already added to project or not
	 * @access public
	 * @return boolean
	 */
	public function is_item_exist($project_id, $item_id){
		$this->db->where('project_id', $project_id);
		$this->db->where('item_id', $item_id);
		$query = $this->db->get($this->_table);
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}	



}