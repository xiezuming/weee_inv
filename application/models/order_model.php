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
	
	public function query_orders($where, $limit, $offset = null) {
		$this->db->from ( TABLE_Order);
		if ($where) {
			$this->db->where ( $where );
		}
		$this->db->order_by ( "rec_create_time", "desc" );
		$this->db->limit ( $limit, $offset );
		$query = $this->db->get ();
		
		log_message ( 'debug', "Order_model.query_orders: SQL = \n" . $this->db->last_query () );
		if ($this->db->_error_number ()) {
			log_message ( 'error', 'Order_model.query_orders: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		}
		$result = $query->result_array ();
		return $result;
	}
	
	public function count_orders($where) {
		$this->db->from ( TABLE_Order );
		if ($where) {
			$this->db->where ( $where );
		}
		$result = $this->db->count_all_results ();
		if ($this->db->_error_number ()) {
			log_message ( 'error', 'Order_model.count_orders: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		}
		return $result;
	}
	
	public function query_order_items($id){
		$this->db->from ( TABLE_Order_item );
		$where="order_id = $id";
		$this->db->where($where);
		$this->db->select('order_line_num,inventory_id,title,quantity,price');
		$query = $this->db->get ();
		$result = $query->result_array ();
		return $result;
	}
	
}
?>