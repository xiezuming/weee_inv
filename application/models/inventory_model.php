<?php
define ( 'TABLE_Inventory', 'inventory' );

/**
 *
 * @property CI_DB_active_record $db
 */
class Inventory_model extends CI_Model {
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
}
?>