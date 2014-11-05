<?php
define ( 'TABLE_ITEM', APP_DATABASE . '.item' );
define ( 'TABLE_IMAGE', APP_DATABASE . '.item_image' );

/**
 *
 * @property CI_DB_active_record $db
 */
class Item_model extends CI_Model {
	/* ======== ITEM ======== */
	public function get_item($itemId) {
		$this->db->where ( 'itemId', $itemId );
		$query = $this->db->get ( TABLE_ITEM );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.get_item: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		$result = $query->row_array ();
		return $result;
	}
	public function get_item_by_global_id($global_item_id) {
		$this->db->where ( 'Global_Item_ID', $global_item_id );
		$query = $this->db->get ( TABLE_ITEM );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.get_item: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		$result = $query->row_array ();
		return $result;
	}
	public function count_items($where) {
		$this->db->from ( TABLE_ITEM );
		if ($where) {
			$this->db->where ( $where );
		}
		$result = $this->db->count_all_results ();
		if ($this->db->_error_number ()) {
			log_message ( 'error', 'Item_model.count_items: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		}
		return $result;
	}
	public function query_items($where, $limit, $offset = null) {
		$this->db->from ( TABLE_ITEM );
		if ($where) {
			$this->db->where ( $where );
		}
		$this->db->order_by ( "item.recCreateTime", "desc" );
		$this->db->limit ( $limit, $offset );
		$query = $this->db->get ();
		log_message ( 'debug', "Item_model.query_items: SQL = \n" . $this->db->last_query () );
		if ($this->db->_error_number ()) {
			log_message ( 'error', 'Item_model.query_items: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		}
		$result = $query->result_array ();
		return $result;
	}
	public function insert_item($data) {
		$result = $this->db->insert ( TABLE_ITEM, $data );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.insert_item: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
	public function update_item($item) {
		$this->db->where ( 'itemId', $item ['itemId'] );
		$result = $this->db->update ( TABLE_ITEM, $item );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.update_item: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
	public function delete_item($item) {
		$this->db->where ( 'itemId', $item ['itemId'] );
		$this->db->delete ( TABLE_ITEM );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.delete_item: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
	}
	/* ======== ITEM HISTORY ======== */
	public function insert_item_history($data) {
		$result = $this->db->insert ( TABLE_HISTORY, $data );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.insert_item_history: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
	/* ======== ITEM IMAGE ======== */
	public function query_all_images($where) {
		if ($where)
			$this->db->where ( $where );
		$query = $this->db->get ( TABLE_IMAGE );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.query_all_images: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		$result = $query->result_array ();
		return $result;
	}
	public function get_images($globalItemId) {
		$this->db->where ( 'Global_Item_ID', $globalItemId );
		$query = $this->db->get ( TABLE_IMAGE );
		return $query->result_array ();
	}
	public function get_first_image($globalItemId) {
		$this->db->where ( 'Global_Item_ID', $globalItemId );
		$this->db->order_by ( "imageName", "" );
		$this->db->limit ( 1 );
		$query = $this->db->get ( TABLE_IMAGE );
		return $query->row_array ();
	}
	public function insert_image($globalItemId, $imageName) {
		$result = $this->db->insert ( TABLE_IMAGE, array (
				'Global_Item_ID' => $globalItemId,
				'imageName' => $imageName,
				'synchWp' => 'N' 
		) );
		if ($this->db->_error_number ())
			log_message ( 'error', 'Item_model.insert_image: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		return $result;
	}
	public function delete_image($globalItemId, $imageName) {
		$this->db->where ( 'Global_Item_ID', $globalItemId );
		$this->db->where ( 'imageName', $imageName );
		$this->db->delete ( TABLE_IMAGE );
	}
}
?>