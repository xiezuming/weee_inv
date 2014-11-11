<?php
	if ( ! defined('BASEPATH')) 
		exit('No direct script access allowed');


	class Order extends CI_Controller {
		
		function __construct() {
			parent::__construct ();
			$this->load->model ( 'inventory_model' );
			$this->load->model ( 'order_model' );
			$this->load->helper ( 'form' );
			//global  $customer;
		}

		

		function index(){
			$data ['title'] = 'Item List';
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'order/index' );
			$this->load->view ( 'templates/footer' );
		
		}

		public function queryitem()
		{
			$found=false;
			$query_string=$_POST['itemid'];	
			
			if ($query_string) {
				$where = "app_global_item_id = $query_string";
				$count = $this->inventory_model->count_items ( $where );
				$iventorys = $this->inventory_model->query_items ( $where, 1 );
				if($count>0)$found=true;	
			}
			
			if ($found)
			{
				$data['result']='SUCCESS';
				$iventory=$iventorys[0];
				$data['id']=$iventory['app_global_item_id'];
				$data['title']=$iventory['title'];
				$data['price']=$iventory['price'];
				$data['stockquantity']=$iventory['quantity'];
			}
			else $data['result']='FAIL'; 
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $data ) );
		}
		
		
		function add(){
			$customer = $this->input->post ( 'iptCustomer' );
			$data ['title'] = 'Item List';
			$data ['customer'] = $customer;
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'sale/order', $data );
			$this->load->view ( 'templates/footer' );

		}
		
		function addorder()
		{
			$customer=$_POST['customer'];
			$sum=$_POST['sum'];
			$discount=$_POST['discount'];
			$tax=$_POST['tax'];
			$total=$_POST['total'];
			$data=$_POST['data'];
			$date_now = date ( 'Y-m-d H:i:s' );
			
			$success = $this->order_model->insert_order ( array (
					'customer_name' => $customer,
					'total_before_tax' => $sum,
					'discount' => $discount,
					'tax' => $tax,
					'total' => $total,
					'channel' => 'CHN',
					'rec_create_time' => $date_now
			));
			
			if ($success) {
				$order_id = $this->db->insert_id ();
				$line=0;
				foreach ( $data as $item ){
					$success=$this->inventory_model->reduce_quantity($item['id'],$item['quantity']);
					if ($success);
					else break;
					$line++;
					
					$where =  array ('app_global_item_id' =>  $item['id']);
					$query = $this->inventory_model->query_items($where,1);
					$inventory_id = $query[0]['inventory_id'];
					$title = $query[0]['title'];
					$price = $query[0]['price'];
					
					$success =$this->order_model->insert_order_item(array(
						'order_id'=>$order_id,
						'order_line_num' => $line,
						'inventory_id'=>$inventory_id,
						'quantity'=>$item['quantity'],
						'title'=>$title,
						'price'=>$price				
					));
					if($success){
						
					}
					else break;
				}	
			}
			if($success) echo 'OK';
			else echo 'ERROR';	
		}
		
		function test()
		{
			//print_r('fff');
			
			$this->order_model->insert_order_item(array(
				'order_id'=>2,
				'order_line_num'	=>2,
				'inventory_id'=>2,
				'quantity'=>2,
				'title'=>'2222',
				'price'=>2				
			));
			//print_r('fff');
			
			
			
		}
 
		function select_item(){
			$query_string = $this->input->post ( 'iptID' );
			$where = "app_global_item_id = -1";
			if ($query_string) {
				$where = "app_global_item_id = '$query_string' OR title like '%$query_string%'";
			}
			$count = $this->inventory_model->count_items ( $where );
			$items = $this->inventory_model->query_items ( $where, 10 );
			$items_with_image = array ();
			foreach ( $items as $item ) {
				$item ['url'] = '';
				$image_row = $this->app_model->get_first_image ( $item ['app_global_item_id'] );
				if ($image_row) {
					$user_id = $item ['user_id'];
					$image_name = $image_row ['imageName'];
					$image_name = substr_replace ( $image_name, '-360', - 4, 0 );
					// Hard code the url base string.
					$item ['image_url'] = "http://www.letustag.com/images/weee_app/$user_id/$image_name";
				}
				array_push ( $items_with_image, $item );
			}
			$customer = $this->input->post ( 'customer' );
			$data ['title'] = 'Item List';
			$data ['customer'] = $customer;
			//$data ['customer']="ccc";
			//echo "<script>alert($customer)</script>";

			$data ['items'] = $items_with_image;
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'sale/order', $data );
			$this->load->view ( 'sale/selection', $data );
			$this->load->view ( 'templates/footer' );
		
		}


	}
?>