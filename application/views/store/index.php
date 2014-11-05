<h3><?php echo $title;?></h3>

<?php
echo form_open ( 'store' );
echo form_input ( 'query_string', $query_string, 'size="40" placeholder="Inventory item ID or Title"' );
echo form_submit ( 'mysubmit', 'Query' );
echo '</form>'?>

<table class='gridtable' style="width: 1200">
	<tr>
		<th>Inventory Id</th>
		<th>Global Item Id</th>
		<th>Title</th>
		<th>Owner</th>
		<th>Floor Price</th>
		<th>Price</th>
		<th>Cost</th>
		<th>Sales Split</th>
		<th>Quantity</th>
		<th></th>
	</tr>
	<?php foreach ( $items as $item ) { ?>
	<tr>
		<td nowrap><?php echo $item['inventory_id']?></td>
		<td nowrap><?php echo $item['app_global_item_id']?></td>
		<td><?php echo $item['title']?></td>
		<td nowrap><?php echo $item['user_name']?></td>
		<td nowrap><?php echo $item['floor_price']?></td>
		<td nowrap><?php echo $item['price']?></td>
		<td nowrap><?php echo $item['cost']?></td>
		<td nowrap><?php echo $item['sales_split']?></td>
		<td nowrap><?php echo $item['quantity']?></td>
		<td nowrap><?php echo anchor ( 'store/print_label/' . $item ['inventory_id'], 'Print' );?></td>
	</tr>
	<?php
	}
	?>


</table>
<p><?php echo count($items). "/$count"?></p>