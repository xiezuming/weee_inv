<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"
	lang="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title ?> - Weee!</title>
<link rel="stylesheet" href="<?php echo base_url('/css/style.css')?>">
<script type="text/javascript"
	src="<?php echo base_url('/js/jquery.js')?>"></script>
</head>

<body id="content">
	<h2>Weee!</h2>
	<?php echo anchor ( 'item' , 'Item List' );?>&nbsp;&nbsp;&nbsp;
	<?php echo anchor ( 'store' , 'Inventory List' );?>&nbsp;&nbsp;&nbsp;
	<?php echo anchor ( 'order' , 'Order List' );?>
	<hr />

	<?php echo '<div class="main">'?>
	
