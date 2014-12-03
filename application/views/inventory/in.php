<h3><?php echo $title;?></h3>

<font color='red'>
<?php echo validation_errors(); ?>
<?php echo isset($error_message) ? $error_message : ''; ?>
</font>

<?php echo form_open('inventories/in/'.$item['Global_Item_ID']);?>
<fieldset>
	<legend>Price &amp; Quantity:</legend>
	<table class='gridtable'>
		<tr>
			<th>Item Title</th>
			<td><?php echo $item['title']?></td>
		</tr>
		<tr>
			<th>Price</th>
			<td><?php echo form_input('price', set_value('price', $item['expectedPrice']), 'class="rightJustified"');?></td>
		</tr>
		<tr>
			<th>Quantity</th>
			<td><?php echo form_input('quantity', set_value('quantity', '1'), 'class="rightJustified"');?></td>
		</tr>
	</table>
</fieldset>


<div style="float: left; width: 50%;">
	<fieldset style="height: 120">
		<legend>Consignment:</legend>
		<table class='gridtable'>
			<tr>
				<th>Floor Price</th>
				<td><?php echo form_input('floor_price', set_value('floor_price'), 'class="rightJustified"');?></td>
			</tr>
			<tr>
				<th>Sales Split</th>
				<td><?php echo form_input('sales_split', set_value('sales_split', '30'), 'class="rightJustified"');?>%</td>
			</tr>
		</table>
		<p><?php echo form_submit('submit_consignment', 'Consignment');?></p>
	</fieldset>
</div>

<div style="float: right; width: 50%;">
	<fieldset style="height: 120">
		<legend>Buy and Sell:</legend>
		<table class='gridtable'>
			<tr>
				<th>Cost</th>
				<td><?php echo form_input('cost', set_value('cost'), 'class="rightJustified"');?></td>
			</tr>
		</table>
		<p><?php echo form_submit('submit_buy_and_sell', 'Buy and Sell');?></p>
	</fieldset>
</div>
<div style="clear: both;"></div>

<?php echo '</form>'?>

<fieldset>
	<legend class='togvis'>Item Details:</legend>
	<div class="contents" style="display: none;">
		<table class='gridtable'>
			<?php
			foreach ( $item as $key => $value ) {
				echo "<tr><th>$key</th><td>$value</td></tr>";
			}
			?>
		</table>
	</div>
</fieldset>

<fieldset>
	<legend class='togvis'>Owner Details:</legend>
	<div class="contents" style="display: none;">
		<table class='gridtable'>
			<?php
			foreach ( $user as $key => $value ) {
				echo "<tr><th>$key</th><td>$value</td></tr>";
			}
			?>
		</table>
	</div>
</fieldset>

<script>
$('legend.togvis').click(function() {
	$(this).parent().find("div").toggle();
    return false;
});
</script>