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
		$this->load->helper ( 'form' );
		$Global_User_ID = $this->input->post ( 'Global_User_ID' );
		$query_string = $this->input->post ( 'query_string' );
		$where = "Global_Item_ID > -1 ";
		if ($query_string) {
			$where = $where." AND (global_item_id = '$query_string' OR title like '%$query_string%')";
		}
		
		if ($Global_User_ID)
		{
			$userId=$this->app_model->get_userId($Global_User_ID);
			$where=$where." AND userId = '$userId'";
		}
		
		$count = $this->app_model->count_items ( $where );
		$items = $this->app_model->query_items ( $where, 10 );
		$items_with_image = array ();
		foreach ( $items as $item ) {
			$item ['url'] = '';
			$image_row = $this->app_model->get_first_image ( $item ['Global_Item_ID'] );
			if ($image_row) {
				$user_id = $item ['userId'];
				$image_name = $image_row ['imageName'];
				$image_name = substr_replace ( $image_name, '-360', - 4, 0 );
				// Hard code the url base string.
				$item ['image_url'] = "http://www.letustag.com/images/weee_app/$user_id/$image_name";
			}
			array_push ( $items_with_image, $item );
		}
		
		$data ['title'] = 'Item List';
		$data ['count'] = $count;
		$data ['items'] = $items_with_image;
		$data ['query_string'] = $query_string;
		$data ['Global_User_ID'] =$Global_User_ID;
		
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'item/index', $data );
		$this->load->view ( 'templates/footer' );
	}
	
	
	public function GetItemsByUser($Global_User_ID) 
	{
		$this->load->helper ( 'form' );
		$userId=$this->app_model->get_userId($Global_User_ID);
		$where = "userId = '$userId'";
		$count = $this->app_model->count_items ( $where );
		$items = $this->app_model->query_items ( $where, 10 );
		$items_with_image = array ();
		foreach ( $items as $item ) {
			$item ['url'] = '';
			$image_row = $this->app_model->get_first_image ( $item ['Global_Item_ID'] );
			if ($image_row) {
				$user_id = $item ['userId'];
				$image_name = $image_row ['imageName'];
				$image_name = substr_replace ( $image_name, '-360', - 4, 0 );
				// Hard code the url base string.
				$item ['image_url'] = "http://www.letustag.com/images/weee_app/$user_id/$image_name";
			}
			array_push ( $items_with_image, $item );
		}
		
		$data ['title'] = 'Item List';
		$data ['count'] = $count;
		$data ['items'] = $items_with_image;
		$data ['query_string'] = "";
		$data ['Global_User_ID'] =$Global_User_ID;
		
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'item/index', $data );
		$this->load->view ( 'templates/footer' );
		
	}
}

?>
