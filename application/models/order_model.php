<?php
define ( 'TABLE_Order', 'order' );
define ( 'TABLE_Order_item', 'order_item' );


/**
 *
 * @property CI_DB_active_record $db
 */
class Order_model extends CI_Model {
	
	public function get_order($order_id) {
		$where = array (
				'order_id' => $order_id 
		);
		$query = $this->db->get_where ( TABLE_Order, $where );
		return $query->row_array ();
	}
	
	public function insert_order($data) {
		$result = $this->db->insert ( TABLE_Order, $data );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Order_model.insert_order: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
	
	public function insert_order_item($data){
		$result = $this->db->insert ( TABLE_Order_item, $data );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Order_model.insert_order_item: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
	
}
?>