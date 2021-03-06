<link rel="stylesheet" href="<?php echo base_url('/css/themes/base/ui.all.css')?>">
<script type="text/javascript" src="<?php echo base_url('/js/jquery-1.3.2.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('/js/ui.core.js')?>"></script>
<script type="text/javascript" src="<?php echo base_url('/js/ui.datepicker.js')?>"></script>

<script  type="text/javascript" > 
	var pageindex=0;
	var totalpages=0;

	$(function() {
		$("#iptStartDate").datepicker({
			showButtonPanel: true,
			showClearButton:true
		});
		
		$("#iptEndDate").datepicker({
			showButtonPanel: true,
			showClearButton:true
		});

		SetNavigation();
		$("#divDetail").hide();
		Query();
	});

	function SetNavigation()
	{
		if (totalpages==0)
		$("#spnPageIndex").text('0');
		else $("#spnPageIndex").text(pageindex+1);
		$("#spnTotalPages").text(totalpages);

	}

	function Query()
	{
		pageindex=0;
		postQuery();
	}

	function postQuery()
	{
		var customer = $('#iptCustomer').val();
		var startdate = $('#iptStartDate').val();
		var enddate = $('#iptEndDate').val();
		var number = $('#slcNumber').val();
		var offset=pageindex*number;
		$.post(
			"<?=base_url()?>index.php/order/query_orders",
			{customer:customer,startdate:startdate,enddate:enddate,limit:number,offset:offset},
			function(data){
				var orders = eval(data);
				var count = orders[orders.length-1];
				$("#spnTotal").text(count);
				totalpages=Math.ceil(count/number);				
				LoadOrders(orders);
				SetNavigation();
			}
		);
	}

	function Detail(id)
	{
		$.post(
			"<?=base_url()?>index.php/order/query_order_detail",
			{id:id},
			function(data){
				var items = eval(data);
				var html="";
				var count=items.length;
				$("#spnOrderID").text(id);
				for(var i=0;i<count;i++)
				{
					html+="<tr>";
					html+="<td>"+items[i].order_line_num+"</td>";
					html+="<td>"+items[i].inventory_id+"</td>";
					html+="<td>"+items[i].title+"</td>";
					html+="<td>"+items[i].quantity+"</td>";
					html+="<td>$"+items[i].price+"</td>";
					html+="</tr>";
				}
				$("#divDetail").show();
				$("#tblDetail").html(html);
			}
		);
	}
	
	function LoadOrders(orders)
	{
	
		if (orders.length<=1)
		{
			alert('No order found!');
			$("#tblOrders").html("");
		}
		else
		{
			var i=0;
			var html="";
			for(i=0;i<orders.length-1;i++)
			{
				html+="<tr>";
				html+="<td>"+orders[i].order_id+"</td>";
				html+="<td>"+orders[i].customer_name+"</td>";
				html+="<td>$"+orders[i].total_before_tax+"</td>";
				html+="<td>$"+orders[i].discount+"</td>";
				html+="<td>$"+orders[i].tax+"</td>";
				html+="<td>$"+orders[i].total+"</td>";
				html+="<td>"+orders[i].channel+"</td>";
				html+="<td>"+orders[i].rec_create_time+"</td>";
				html+="<td>"+"<a href='#C2' onclick='Detail("+orders[i].order_id+")' >Detail</a>" +"</td>";
				html+="</tr>";
			}
			$("#tblOrders").html(html);
		}
	}

	function Add()
	{
		
		window.location.href="<?=base_url()?>index.php/order/add";
	}	

	function goto()
	{
		var str = $("#iptGoto").val()

		var ex = /^\d+$/;
		if (ex.test(str)) {
			if ((str<=totalpages)&&(str>=1))
			{
				pageindex=str-1;
				postQuery();
			}
			else alert('Input ERROR!');
		}
		else alert('Input ERROR!');
	}

	function next()
	{
		if (pageindex+1>=totalpages)
		{
			alert('Last page already!')
			return;
		}

		pageindex++;
		postQuery();
	}

	function prev()
	{
		if (pageindex==0)
		{
			alert('First page already!')
			return;
		}
		pageindex--;
		postQuery();
	}

	function closedetail()
	{
		$("#divDetail").hide();
	}

</script>

<div class="panel panel-default">
  <div class="panel-body form-inline">
    <input id='iptCustomer' class="form-control"  placeholder="Customer" style="width: 300px" />
    <input id='iptStartDate' class="form-control" placeholder="from this date" readonly="true"  style="width: 200px" /> 
    <input id='iptEndDate'   class="form-control" placeholder="to this date"   readonly="true"  style="width: 200px" />
    <input type='button'     class="btn btn-default" value='Query' onclick='Query()' />
    <input type='button'     class="btn btn-default" value='Add' onclick='Add()' />
  </div>
</div>

<hr/>
<a name='C1'></a>
<div class="panel panel-default">
  <div class="panel-body">
    <table class='table'>
	  <thead>
	    <th style='width: 100'>Order ID</th>
		<th style='width: 200'>Customer</th>
		<th>Total befor tax</th>
		<th>Discount</th>
		<th>Tax</th>
		<th>Total</th>
		<th>Channel</th>
		<th>Date Time</th>
		<th>Detail</th>	
	  </thead>
	  <tbody id='tblOrders'></tbody>
      <tfoot>
        <tr>
          <td colspan="11">
            <div id='divNavigation'>
              <br/>
			  Total <span id='spnTotal' style='color: red'>0</span>&nbsp items found!&nbsp&nbsp&nbsp 
			  Show <select id='slcNumber' onchange="Query()">
                <option value='10'>10</option>
                <option value='20'>20</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
              </select> items in each page. 
              <br/><br/>
			  Page <span id='spnPageIndex' style='color: red'></span> &nbspin&nbsp 
			  <span id='spnTotalPages' style='color: red'></span>&nbsppages. 
			  <a href="#C1" onclick='prev()'>Prev</a> <a href="#C1" onclick='next()'>Next</a> 
			  Goto <input id='iptGoto' style='width: 40' />
			  <input id='iptGotoPage' type='button' value='GO' onclick='goto()' />
            </div>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>

<br/>

<div id='divDetail'>
	<hr/>
	<a name='C2'></a>
	<h4>Details of order &nbsp<span id='spnOrderID' style='color: red'> </span>&nbsp <input type='button' value='Close' onclick='closedetail()'/> </p>
	<div class="panel panel-default">
      <div class="panel-body">
	    <table class='table'>
		  <thead>
			<th>Order Line Num </th>
			<th>Inventory ID </th>
			<th>Title</th>
			<th>Quantity</th>
			<th>Price</th>
		  </thead>
		  <tbody id='tblDetail'></tbody>
	    </table>
	  </div>
	</div>
</div>



<?php




?>
