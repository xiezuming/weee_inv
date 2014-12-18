<?php
define ( 'TABLE_Settings', 'settings' );


class Settings_model extends CI_Model {

	public function query_setting($caption)
	{
		$this->db->from ( TABLE_Settings );
		$where='setting_caption = "' . $caption. '"';
		$this->db->where ( $where );
		$query = $this->db->get ();
		log_message ( 'debug', "Settings_model.query_setting: SQL = \n" . $this->db->last_query () );
		if ($this->db->_error_number ()) {
			log_message ( 'error', 'Settings_model.query_setting: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
		}
		$result = $query->result_array ();
		return $result;
	}
	
	public function set_setting($caption,$value)
	{
		$where='setting_caption = "' . $caption. '"';
		$set = array('setting_value' => $value);
		$this->db->update(TABLE_Settings,$set,$where,1);
		return  $this->db->affected_rows();		
	}
	
	public function count_setting($caption,$value)
	{
	  $where='setting_caption = "' . $caption. '" and setting_value = "' .$value. '"';
	  $this->db->from ( TABLE_Settings );
	  $this->db->where ( $where );
      $result = $this->db->count_all_results ();
	  if ($this->db->_error_number ()) {
	    log_message ( 'error', 'settings_model.count_setting: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
	  }
      return $result;
	}
	
	
	
	public function add_setting($caption,$value)
	{
	  $data= array ("setting_caption"=>$caption,"setting_value"=>$value);
	  $result = $this->db->insert ( TABLE_Settings, $data );
	  if ($this->db->_error_number ())
	  	log_message ( 'error', 'Setting_model.add_setting: ' . $this->db->_error_number () . ':' . $this->db->_error_message () );
	  return $result;
	}
	
	public function delete_setting($setting_id)
	{
		$this->db->where('setting_id', $setting_id);
		$this->db->delete(TABLE_Settings);
		return  $this->db->affected_rows();
	}



}
?>