<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"
	lang="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Print - Weee!</title>
<script type="text/javascript"
	src="<?php echo base_url('/js/jquery.js')?>"></script>
<script type="text/javascript"
	src="<?php echo base_url('/js/jquery-barcode.js')?>"></script>

<style type="text/css">

#table_print {
	width: 400px;
	border-width: 1px;
	border-style: solid;
	border-color: #000;
	border-width: 1px;
}
#label_price {
	font-size: 50px;
}
#label_title {
	font-size: 20px;
}
</style>

</head>

<body>
	<input type="hidden" id='inventory_id'
		value='<?php echo $inventory_id?>' />
	<table id='table_print'>
		<tr>
			<td rowspan='2'><div id="bcTarget"></div></td>
			<td><label id='label_title'><?php echo $title?></label></td>
		</tr>
		<tr>
			<td nowrap><label id='label_price'>$ <?php echo $price?></label></td>
		</tr>
	</table>

</body>

<script type="text/javascript">
<!--
$( document ).ready(function() {
	$("#bcTarget").barcode($('#inventory_id').val(), "code39", {barWidth:3, barHeight:80, fontSize:20});
});
//-->
</script>

</html>

