<?php
	if ( ! defined('BASEPATH')) 
		exit('No direct script access allowed');


	class Sale extends CI_Controller {
		
		function __construct() {
			parent::__construct ();
			$this->load->model ( 'inventory_model' );
			$this->load->model ( 'app_model' );
			$this->load->helper ( 'form' );
			//global  $customer;
		}

		

		function index(){
			

			$data ['title'] = 'Item List';
		
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'sale/order' );

			$this->load->view ( 'templates/footer' );
		
		}

		function add(){
			$customer = $this->input->post ( 'iptCustomer' );
			$data ['title'] = 'Item List';
			$data ['customer'] = $customer;
			$this->load->view ( 'templates/header', $data );
			$this->load->view ( 'sale/order', $data );
			$this->load->view ( 'templates/footer' );

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