<?php
	if ( ! defined('BASEPATH')) 
		exit('No direct script access allowed');


	class Order extends CI_Controller {
		
		function __construct() {
			parent::__construct ();
			$this->load->model ( 'inventory_model' );
			$this->load->model ( 'order_model' );
			$this->load->model ( 'app_model' );
			$this->load->helper ( 'form' );
			//global  $customer;
		}

		function index(){
			$data ['title'] = 'Order List';
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'order/index' );
			$this->load->view ( 'templates/footer' );
		}

		public function queryitem()
		{
			$found=false;
			$query_string=$_POST['itemid'];	 
			
			if ($query_string) {
				$where = "inventory_id = $query_string";
				$count = $this->inventory_model->count_items ( $where );
				$iventorys = $this->inventory_model->query_items ( $where, 1 );
				if($count>0)$found=true;	
			}
			
			if ($found)
			{
				$data['result']='SUCCESS';
				$iventory=$iventorys[0];
				$data['id']=$iventory['inventory_id'];
				$data['title']=$iventory['title'];
				$data['price']=$iventory['price'];
				$data['stockquantity']=$iventory['remainder_quantity'];
			}
			else $data['result']='FAIL'; 
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $data ) );
		}
		
		
		function add(){
			$data ['title'] = 'Add an order';
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'order/add');
			$this->load->view ( 'templates/footer' );

		}
		
		function orderitems()
		{
			$data ['title'] = 'Order Items';
			$data ['Global_User_ID']="";
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'order/orderitems');
			$this->load->view ( 'templates/footer' );
		}
		
		function orderitemsbyuser($user)
		{
			$data ['title'] = 'Order Items';
			$data ['Global_User_ID']=$user;
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'order/orderitems');
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
					'channel' => 'str',
					'rec_create_time' => $date_now
			));
			
			
			
			if ($success) {
				$order_id = $this->db->insert_id ();
				$line=0; 
				foreach ( $data as $item ){
					$success=$this->inventory_model->reduce_quantity($item['id'],$item['purchase_quantity']);
					if ($success);
					else break;
					$line++;
					
					$where =  array ('inventory_id' =>  $item['id']);
					$query = $this->inventory_model->query_items($where,1);
					$inventory_id = $query[0]['inventory_id'];
					$title = $query[0]['title'];
					$price = $query[0]['price'];
					
					$success =$this->order_model->insert_order_item(array(
						'order_id'=>$order_id,
						'order_line_num' => $line,
						'inventory_id'=>$inventory_id,
						'quantity'=>$item['purchase_quantity'],
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
		
		function query_orders()
		{
			$customer=$_POST['customer'];
			$startdate=$_POST['startdate'];
			$enddate=$_POST['enddate'];
			$limit=$_POST['limit'];
			$offset=$_POST['offset'];
			$where="order_id > 0 ";
			if ($customer)  $where = $where." AND customer_name = '$customer'";
			if ($startdate) $where = $where." AND rec_create_time >= '$startdate'";
			if ($enddate)   $where = $where." AND rec_create_time <= '$enddate'";
			$count=$this->order_model->count_orders($where);
			$orders=$this->order_model->query_orders($where, $limit,$offset);
			array_push($orders, $count);
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $orders )) ;
		}
		
		function query_order_detail()
		{
			$id=$_POST['id'];	
			$items=$this->order_model->query_order_item_by_id($id);
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $items )) ;
		
		}
		
		function query_order_items()
		{
			$Global_User_ID=$_POST['Global_User_ID'];
			if ($Global_User_ID) $userId= $this->app_model->get_userId($Global_User_ID);
			else $userId=NULL;
			$query_string=$_POST['query_string'];
			$limit=$_POST['limit'];
			$offset=$_POST['offset'];
			$query=$this->order_model->query_order_items($userId,$query_string,$limit,$offset);
			echo json_encode($query);
			//echo $query;
			/*
			$where="SELECT * FROM order_item where inventory_id > -1 ";
			if($query_string)
				$where = $where." AND ( inventory_id = '$query_string' OR title like '%$query_string%') ";
			if ($Global_User_ID)
			{
				$userId= $this->app_model->get_userId($Global_User_ID);
				$where=$where." AND (inventory_id in (SELECT inventory_id FROM inventory WHERE user_Id='$userId'))";
			} 
			
			$count=$this->order_model->count_order_items($where);
			//$order_items=$this->order_model->query_order_items($where, $limit,$offset);
			echo  	$count;*/
			
			
		}
		
		
		
 
	}
?>