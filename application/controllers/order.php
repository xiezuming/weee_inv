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
				$data['stockquantity']=$iventory['quantity'];
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
					$success=$this->inventory_model->reduce_quantity($item['id'],$item['quantity']);
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
		
		function query_orders()
		{
			$customer=$_POST['customer'];
			$startdate=$_POST['startdate'];
			$enddate=$_POST['enddate'];
			$number=$_POST['number'];
			$offset=$_POST['offset'];
			$where="order_id > 0 ";
			if ($customer)  $where = $where." AND customer_name = '$customer'";
			if ($startdate) $where = $where." AND rec_create_time >= '$startdate'";
			if ($enddate)   $where = $where." AND rec_create_time <= '$enddate'";
			$count=$this->order_model->count_orders($where);
			$orders=$this->order_model->query_orders($where, $number,$offset);
			array_push($orders, $count);
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $orders )) ;
		}
		
		function query_order_detail()
		{
			$id=$_POST['id'];	
			$items=$this->order_model->query_order_items($id);
			$this->output->set_content_type ( 'application/json' )->set_output ( json_encode ( $items )) ;
		
		}
		
		
		
 
	}
?>