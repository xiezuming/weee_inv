<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

class Inventories extends CI_Controller {
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'inventory_model' );
		$this->load->model ( 'app_model' );
	}

	public function index() {
		$data['title']='Inventory List';
		$data['Global_User_ID']=NULL;
		$data['id']=NULL;
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'inventories/index',$data );
		$this->load->view ( 'templates/footer' );
	}

	public function inventoriesbyuser($user)
	{
		$data['title']='Inventory List';
		$data['Global_User_ID']=$user;
		$data['id']=NULL;
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'inventories/index',$data );
		$this->load->view ( 'templates/footer' );
	}
	
	public function inventoriesbyid($id)
	{
		$data['title']='Inventory List';
		$data['Global_User_ID']=NULL;
		$data['id']=$id;
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'inventories/index',$data );
		$this->load->view ( 'templates/footer' );
	}	
	
	public function queryinventories()
	{
		$Global_User_ID=$_POST['Global_User_ID'];
		$query_string=$_POST['query_string'];
		$limit=$_POST['limit'];
		$offset=$_POST['offset'];
		$where="inventory_id > -1 ";
		
		if($query_string)
			$where = $where." AND ( inventory_id = '$query_string' OR title like '%$query_string%') ";
		
		if ($Global_User_ID)
		{
			$userId=$this->app_model->get_userId($Global_User_ID);
			$where = $where." AND user_Id = '$userId'";
		}
		
		$count = $this->inventory_model->count_items ( $where );
		$items = $this->inventory_model->query_items ( $where, $limit,$offset );
		$result=array();
		foreach ($items as $item)
		{
			$backItem['inventory_id']= $item['inventory_id'];
			$backItem['Global_Item_ID']= $item['app_global_item_id'];
			$backItem['title']= $item['title'];
			$backItem['owner']= $item['user_name'];
			$backItem['floor_price']= $item['floor_price'];
			$backItem['price']= $item['price'];
			$backItem['floor_price']= $item['floor_price'];
			$backItem['cost']= $item['cost'];
			$backItem['floor_price']= $item['floor_price'];
			$backItem['sales_split']= $item['sales_split'];
			$backItem['quantity']= $item['quantity'];
			$backItem['remainder_quantity']= $item['remainder_quantity'];
			array_push($result, $backItem);
		}
		
		array_push($result, $count);
		echo  json_encode($result);
	}
	
	public function print_label($inventory_id) {
		$inventory_item = $this->inventory_model->get_inventory_item ( $inventory_id );
		if (! $inventory_item) {
			show_404 ();
			return;
		}
		$this->load->view ( 'inventories/print_label', $inventory_item );
	}
	
	public function in($global_item_id) {
		$this->load->helper ( 'form' );
	
		$item = $this->app_model->get_item_by_global_id ( $global_item_id );
		if (! $item) {
			show_404 ();
			return;
		}
		if ($item ['inventory_id']) {
			show_error ( 'The item has been checked in.' );
			return;
		}
	
		$user = $this->app_model->get_user ( $item ['userId'] );
		$user ['password'] = '********';
	
		$data ['title'] = 'Stock In';
		$data ['item'] = $item;
		$data ['user'] = $user;
	
		if ($this->input->post ()) {
			$this->load->library ( 'form_validation' );
				
			$this->form_validation->set_rules ( 'price', 'Price', 'required|numeric' );
			$this->form_validation->set_rules ( 'quantity', 'Quantity', 'required|integer' );
			if ($this->input->post ( 'submit_buy_and_sell' )) {
				$this->form_validation->set_rules ( 'cost', 'Cost', 'required|numeric' );
			} else {
				$this->form_validation->set_rules ( 'floor_price', 'Floor Price', 'required|numeric' );
				$this->form_validation->set_rules ( 'sales_split', 'Sales Split', 'required|numeric' );
			}
				
			if ($this->form_validation->run ()) {
				$inventory_id = self::save_inventory ( $item, $user );
				if ($inventory_id) {
					redirect ( '/inventories', 'refresh' );
				}
				$data ['error_message'] = 'Interal Error: Failed to update the database.';
			}
		}
	
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'inventories/in', $data );
		$this->load->view ( 'templates/footer' );
	}
	private function save_inventory($item, $user) {
		$user_name = $user ['lastName'] . ' ' . $user ['firstName'];
	
		$price = $this->input->post ( 'price' );
		$floor_price = $this->input->post ( 'floor_price' );
		$cost = $this->input->post ( 'cost' );
		$sales_split = $this->input->post ( 'sales_split' );
		if ($sales_split)
			$sales_split = $sales_split / 100.0;
		$date_now = date ( 'Y-m-d H:i:s' );
	
		$this->db->trans_start ();
	
		if ($this->input->post ( 'submit_buy_and_sell' )) {
			$user_name = 'Weee!';
			$old_global_item_id = $item ['Global_Item_ID'];
				
			// update the item as sold
			$this->app_model->insert_item_history ( $item );
			$item ['availability'] = 'SD';
			$item ['recUpdateTime'] = $date_now;
			$this->app_model->update_item ( $item );
				
			// insert a new item
			$this->load->helper ( 'uuid' );
			$item ['sourceItemId'] = $old_global_item_id;
			unset ( $item ['Global_Item_ID'] );
			$item ['itemId'] = gen_uuid ();
			$item ['inputSource'] = 'SYS';
			$item ['userId'] = APP_SYSTEM_USER_ID;
			$item ['availability'] = 'AB';
			$item ['recCreateTime'] = $date_now;
			$item ['synchWp'] = 'N';
			$this->app_model->insert_item ( $item );
			$item ['Global_Item_ID'] = $this->db->insert_id ();
				
			// insert images
			$source_image_folder = UPLOAD_BASE_PATH . DIRECTORY_SEPARATOR . $user ['userId'];
			$dest_image_folder = UPLOAD_BASE_PATH . DIRECTORY_SEPARATOR . APP_SYSTEM_USER_ID;
			if (! file_exists ( $dest_image_folder )) {
				mkdir ( $dest_image_folder, 0777, true );
			}
			foreach ( $this->app_model->get_images ( $old_global_item_id ) as $image_row ) {
				$source_image_file = $source_image_folder . DIRECTORY_SEPARATOR . $image_row ['imageName'];
				$dest_image_file = $dest_image_folder . DIRECTORY_SEPARATOR . $image_row ['imageName'];
				copy ( $source_image_file, $dest_image_file );
				$this->app_model->insert_image ( $item ['Global_Item_ID'], $image_row ['imageName'] );
			}
		}
	
		$success = $this->inventory_model->insert_inventory_item ( array (
				'app_global_item_id' => $item ['Global_Item_ID'],
				'app_item_id' => $item ['itemId'],
				'user_id' => $item ['userId'],
				'user_name' => $user_name,
				'title' => $item ['title'],
				'barcode' => $item ['barcode'],
				'category' => $item ['category'],
				'price' => $price,
				'floor_price' => $floor_price ? $floor_price : NULL,
				'cost' => $cost ? $cost : NULL,
				'sales_split' => $sales_split ? $sales_split : NULL,
				'quantity' => $this->input->post ( 'quantity' ),
				'remainder_quantity' => $this->input->post ( 'quantity' ),
				'rec_create_time' => $date_now,
				'rec_update_time' => $date_now
		) );
	
		if ($success) {
			$inventory_id = $this->db->insert_id ();
			$this->app_model->insert_item_history ( $item );
			$item ['inventory_id'] = $inventory_id;
			$item ['expectedPrice'] = $price;
			$item ['recUpdateTime'] = $date_now;
			$this->app_model->update_item ( $item );
		}
	
		$this->db->trans_complete ();
	
		if ($this->db->trans_status () === FALSE) {
			log_message ( 'error', 'Store.in_consignment: Failed to update the database.' );
			return FALSE;
		}
		return $inventory_id;
	}
	

}
?>