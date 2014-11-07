<?php
	echo "<h3>Customer Name:";
	echo form_open ( 'sale/add' );
	echo form_input ( 'iptCustomer', "");
	echo form_submit ( 'mysubmit', 'Add an Order' );
	echo '</form></h3>';   
?>