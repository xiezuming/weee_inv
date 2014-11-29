<h3><?php echo $title;?></h3>

<?php
echo form_open ( 'item' );
echo form_input ( 'query_string', $query_string, 'size="40" placeholder="Item ID or Title"' );
echo form_submit ( 'mysubmit', 'Query' );
echo '</form>'?>

<table class='gridtable' style="width: 1200">
	<tr>
		<th></th>
		<th>Global Item Id</th>
		<th>Status</th>
		<th>Title</th>
		<th>Price</th>
		<th>Inventory Id</th>
		<th></th>
	</tr>
	<?php foreach ( $items as $item ) { ?>
	<tr>
		<td><a href='<?php echo $item['image_url']?>' target='_blank'><img
				src='<?php echo $item['image_url']?>' height='50' /></a></td>
		<td nowrap><?php echo $item['Global_Item_ID']?></td>
		<td nowrap><?php echo $item['availability']?></td>
		<td><?php echo $item['title']?></td>
		<td nowrap align="right">$<?php echo $item['expectedPrice']?></td>
		<td nowrap> <?php echo $item ['inventory_id']?></td>
		<td nowrap>
		<?php
		if ($item ['inventory_id'])
			echo anchor ( 'store/index/' . $item ['inventory_id'], 'View@Inventory' );
		else if ($item ['availability'] == 'SD')
			echo '';
		else
			echo anchor ( 'store/in/' . $item ['Global_Item_ID'], 'Stock In' );
		?>
		</td>
	</tr>
	<?php
	}
	?>
</table>
<?php echo count($items). "/$count"?>