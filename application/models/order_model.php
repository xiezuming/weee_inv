<?php
define ( 'TABLE_Order', 'order' );
define ( 'TABLE_Order_item', 'order_item' );
define ( 'TABLE_inventory', 'inventory' );


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
	
	public function query_order_item_by_id($id){
		$this->db->from ( TABLE_Order_item );
		$where="order_id = $id";
		$this->db->where($where); 
		$this->db->select('order_line_num,inventory_id,title,quantity,price');
		$query = $this->db->get ();
		$result = $query->result_array ();
		return $result;
	}
	
	public function  query_order_items($userId,$query_string,$limit,$offset)
	{
		$SQL="SELECT * FROM order_item WHERE order_item_id > -1 ";
		if ($query_string)
			$SQL=$SQL." AND ( inventory_id = '$query_string' OR title like '%$query_string%') ";
		if($userId)
			$SQL=$SQL." AND ( inventory_id in (SELECT inventory_id FROM ".TABLE_inventory." WHERE user_Id='$userId'))";
		
		$query = $this->db->query($SQL);
		$count=count($query->result_array ());
		$SQL=$SQL." LIMIT $offset, $limit";
		$query = $this->db->query($SQL);
		$result = $query->result_array ();
		array_push($result, $count);	
		return $result;							
	}
	
}
?>