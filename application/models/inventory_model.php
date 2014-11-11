<?php
define ( 'TABLE_Inventory', 'inventory' );

/**
 *
 * @property CI_DB_active_record $db
 */
class Inventory_model extends CI_Model {
	public function get_inventory_item($inventory_id) {
		$where = array (
				'inventory_id' => $inventory_id 
		);
		$query = $this->db->get_where ( TABLE_Inventory, $where );
		$inventory_item = $query->row_array ();
		return $inventory_item;
	}
	public function insert_inventory_item($data) {
		$result = $this->db->insert ( TABLE_Inventory, $data );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Inventory_model.insert_inventory_item: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
	public function count_items($where) {
		$this->db->from ( TABLE_Inventory );
		if ($where) {
			$this->db->where ( $where );
		}
		$result = $this->db->count_all_results ();
		if ($this->db->_error_number ()) {
			log_message ( 'error', 'Inventory_model.count_items: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		}
		return $result;
	}
	public function query_items($where, $limit, $offset = null) {
		$this->db->from ( TABLE_Inventory );
		if ($where) {
			$this->db->where ( $where );
		}
		$this->db->order_by ( "rec_create_time", "desc" );
		$this->db->limit ( $limit, $offset );
		$query = $this->db->get ();
		log_message ( 'debug', "Inventory_model.query_items: SQL = \n" . $this->db->last_query () );
		if ($this->db->_error_number ()) {
			log_message ( 'error', 'Inventory_model.query_items: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		}
		$result = $query->result_array ();
		return $result;
	}
	
	public function reduce_quantity($id,$quantity){
		$this->db->from ( TABLE_Inventory );
		$where = array (
			'app_global_item_id' => $id 
		);
		$this->db->where ( $where );
		$query = $this->db->get();
		$row = $query->row_array();
		$q=$row['quantity'];
		
		
		if ($q<$quantity) return ;
		else 
		{
			$left = $q-$quantity;
			//print_r($left);
			$set = array('quantity' => $left);
			$this->db->set('quantity',$left);
			$this->db->update(TABLE_Inventory,$set,$where,1);
			//print_r($str); 
			return 'Success';
		} 
	}
}
?>