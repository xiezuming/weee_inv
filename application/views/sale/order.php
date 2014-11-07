<H3>Customer Name:</H3>







<?php
	echo "<h3>Customer Name:";
	echo form_open ( 'sale/add' );
	echo form_input ( 'iptCustomer', "");
	echo '</h3>';

	echo form_input ( 'iptID', "", 'size="40" placeholder="Item ID or Title"' );
	//echo form_input ('customer',$customer,'size="40" placeholder="Item ID or Title"' );
	echo form_submit ( 'mysubmit', 'Query' );
	echo '</form>'
?>
<hr/>

<h3>Selected items:</h3>