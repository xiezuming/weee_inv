<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Settings extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'settings_model' );
	}
	
	public function index() {
		$data['title']='settings';
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'settings/index',$data );
		$this->load->view ( 'templates/footer' );
	}

	public function gettaxrate()
	{
		$caption='TaxRate';
		$value=$this->settings_model->query_setting($caption);
		$TaxRate=$value[0]['setting_value'];
		if(preg_match("/[^\d-., ]/",$TaxRate)) echo 'ERROR';
		else echo $TaxRate;
	}
	
	public function settaxrate()
	{
		$value=$_POST['t'];
		$caption="TaxRate";
		$result=$this->settings_model->set_setting($caption,$value);
		if ($result==1) echo 'OK';
		else echo 'ERROR';
	}

	public function getchannels()
	{
	  $query=$this->settings_model->query_setting('ChannelDefault');	
	  $channeldefault=$query[0]['setting_value'];	
	  $result=$this->settings_model->query_setting("Channel");
	  array_push($result, $channeldefault);
	  echo  json_encode($result);
	}
	
	public  function addchannel()
	{
	  $value=$_POST["channel"];
	  $caption="Channel";
	  $result=$this->settings_model->count_setting($caption,$value);
	  if($result>0){
	    echo "EXIST";
	    return;
	  }
	  $result=$this->settings_model->add_setting($caption,$value);
	  if ($result==1) echo 'OK';
	  else echo 'ERROR';
	}

	public function deletechannel()
	{
		$setting_id=$_POST["channel"];
		$result=$this->settings_model->delete_setting($setting_id);
		if($result==1)echo "OK";
		else echo "ERROR";
	}
	
	public function defaultchannel()
	{
	  $channel=$_POST["channel"];
	  $result=$this->settings_model->set_setting("ChannelDefault",$channel);
	  if($result==1)echo "OK";
	  else echo "ERROR";
	}
}
?>