<h3><?php echo $title;?></h3>

<?php
echo form_open ( 'item' );
echo form_input ( 'query_string', set_value ( 'query_string' ), 'size="40" placeholder="Item ID or Title"' );
echo form_submit ( 'mysubmit', 'Query' );
echo '</form>'?>

<table class='gridtable' width='1000'>
	<tr>
		<th>Global Item Id</th>
		<th>Title</th>
		<th>Price</th>
		<th></th>
	</tr>
	<?php foreach ( $items as $item ) { ?>
	<tr>
		<td nowrap><?php echo $item['Global_Item_ID']?></td>
		<td><?php echo $item['title']?></td>
		<td nowrap align="right">$<?php echo $item['expectedPrice']?></td>
		<td nowrap><a
			href='<?php echo site_url('store/in/'.$item['Global_Item_ID'])?>'>
				Stock In</a></td>
	</tr>
	<?php
	}
	?>
</table>
<?php echo "10/$count"?>