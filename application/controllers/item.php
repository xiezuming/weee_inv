<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

/**
 *
 * @property App_model $app_model
 */
class Item extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'app_model' );
	}
	
	public function index() {
		$data['title']='item';
		$data['Global_User_ID']="";
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'item/index',$data );
		$this->load->view ( 'templates/footer' );
	}
	
	public function itembyuser($user)
	{
		$data['title']='Item List';
		$data['Global_User_ID']=$user;
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'item/index',$data );
		$this->load->view ( 'templates/footer' );
	}
	
	public function queryitem()
	{
		$Global_User_ID=$_POST['Global_User_ID'];
		$query_string=$_POST['query_string'];
		$limit=$_POST['limit'];
		$offset=$_POST['offset'];
		$where="Global_Item_ID > -1 ";
		
		if($query_string)
			$where = $where." AND ( global_item_id = '$query_string' OR title like '%$query_string%') ";
		
		if ($Global_User_ID)
		{
			$userId=$this->app_model->get_userId($Global_User_ID);
			$where = $where." AND userId = '$userId'";
		}
		
		$count = $this->app_model->count_items ( $where );
		$items = $this->app_model->query_items ( $where, $limit,$offset );
		$result=array();
		foreach ($items as $item)
		{
			$ID=$item['Global_Item_ID'];
			$image_row = $this->app_model->get_first_image ( $ID );
			$user_id = $item ['userId'];
			if ($image_row) {
				$image_name = $image_row ['imageName'];
				$image_name = substr_replace ( $image_name, '-360', - 4, 0 );
				$backItem['image'] = "http://www.letustag.com/images/weee_app/$user_id/$image_name";
			} else {
				$backItem['image'] = "";
			}
			$backItem['Global_Item_ID']=$ID;
			$backItem['availability']=$item['availability'];
			$backItem['title']=$item['title'];
			$backItem['expectedPrice']=$item['expectedPrice'];
			$backItem['inventory_id']=$item['inventory_id'];
			array_push($result, $backItem);
		}
		array_push($result, $count);
		echo  json_encode($result);
	}
	
	
	
}	

?>