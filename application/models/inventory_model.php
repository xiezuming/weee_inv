<?php
define ( 'TABLE_Inventory', 'inventory' );
define ( 'TABLE_Inventory_picked', 'inventory_picked' );

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
		return  $inventory_item;
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
			'inventory_id' => $id 
		);
		$this->db->where ( $where );
		$query = $this->db->get();
		$row = $query->row_array();
		$q=$row['remainder_quantity'];
		
		if ($q<$quantity) return ;
		else 
		{
			$left = $q-$quantity;
			$date_now = date ( 'Y-m-d H:i:s' );
			$set = array('remainder_quantity' => $left,'rec_update_time' => $date_now);
			$this->db->update(TABLE_Inventory,$set,$where,1);
			return 'OK';
		} 
	}
	
	public function add_inventory_picked($id,$quantity)
	{
		$result = $this->db->insert ( TABLE_Inventory_picked, array('inventory_id' => $id, 'quantity' => $quantity, 'pick_time' => date ( 'Y-m-d H:i:s' )) );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Inventory_model.add_inventory_picked: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
}
?>