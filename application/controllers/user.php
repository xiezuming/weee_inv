<?php

if ( ! defined('BASEPATH'))
	exit('No direct script access allowed');


class User extends CI_Controller {
	
	function __construct() {
		parent::__construct ();
		$this->load->model ( 'inventory_model' );
		$this->load->model ( 'order_model' );
		$this->load->model ( 'app_model' );
		
	}
	
	function index(){
		$data ['title'] = 'User List';
		$this->load->view ( 'templates/header', $data );
		$this->load->view ( 'user/index' );
		$this->load->view ( 'templates/footer' );
	}
	
	public function query_user()
	{
		$user=$_POST['user'];
		$limit=	$_POST['limit'];
		$offset=$_POST['offset'];
		if ($user)
		{
			$where="Global_User_ID =  '$user' ";
			$where=$where."OR  userId = '$user' ";
			$where=$where."OR  firstName = '$user' ";
			$where=$where."OR  lastName = '$user' ";
			$where=$where."OR  alias = '$user' ";
			$where=$where."OR  email = '$user' ";
			$where=$where."OR  phoneNumber = '$user' ";
			$where=$where."OR  wechatId = '$user' ";
		}
		else $where=NULL; 

		$count=$this->app_model->count_user($where);
		$query=$this->app_model->query_user($where,$limit,$offset);
		array_push($query, $count);
		echo json_encode($query);
	}
	
	
	
}

?>