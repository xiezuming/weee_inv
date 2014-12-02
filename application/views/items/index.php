<script  type="text/javascript" > 
	var pageindex=0;
	var totalpages=0;

	$(function() {
		$("#iptUser").val(<?php echo $Global_User_ID;?>);
		Query();
	});

	function Query()
	{
		pageindex=0;
		postQuery();
	}

	function postQuery()
	{
		var Global_User_ID = $("#iptUser").val();
		var query_string = $("#iptQueryString").val();
		var number = $("#slcNumber").val();
		var offset=pageindex*number;
		$.post(
			"<?=base_url()?>index.php/items/queryitem",
			{Global_User_ID:Global_User_ID,query_string:query_string,limit:number,offset:offset},
			function(data){
				var items = eval(data);
				var count = items[items.length-1];
				$("#spnTotal").text(count);
				totalpages=Math.ceil(count/number);	
				LoadItems(items);
				SetNavigation();
			}
		);
	}

	function SetNavigation()
	{
		if (totalpages==0)
		$("#spnPageIndex").text('0');
		else $("#spnPageIndex").text(pageindex+1);
		$("#spnTotalPages").text(totalpages);
	}

	function LoadItems(items)
	{
		if (items.length<=1)
		{
			alert('No item found!');
			$("#tblItems").html("");
			return;
		}
		var html="";
		for(var i=0;i<items.length-1;i++)
		{
			html+="<tr>";
			html+="<td>" + "<a href='"+items[i].image+"' target='_blank'><img src='"+items[i].image+"' height='50' /></a></td>";
			html+="<td>"+items[i].Global_Item_ID+"</td>";
			html+="<td>"+items[i].availability+"</td>";
			html+="<td>"+items[i].title+"</td>";
			html+="<td>$"+items[i].expectedPrice+"</td>";
			var inventoryID="";
			if (items[i].inventory_id) inventoryID=items[i].inventory_id;
			html+="<td>"+inventoryID+"</td>";
			var opt="";
			if (items[i].inventory_id) opt="<a href='<?=base_url()?>index.php/inventories/inventoriesbyid/"+items[i].inventory_id+"'>View@Inventory</a>";
			else if (items[i].availability=='SD');
			else opt="<a href='<?=base_url()?>index.php/inventories/in/"+items[i].Global_Item_ID+"'>Stock In</a>";
			html+="<td>"+opt+"</td>";
			html+="</tr>";
		}
		$("#tblItems").html(html);
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

</script>

<h3><?php echo $title;?></h3>
<p>User: <input id='iptUser' /> Item: <input id='iptQueryString' /> 
Show
<select style='width: 50px' id='slcNumber'>
  <option value ='10'>10</option>
  <option value ='20'>20</option>
  <option value ='50'>50</option>
  <option value ='100'>100</option>
</select>
items in each page.

<input type='button' value='Query' onclick='Query()' />
</p>

<hr/>
<a name='C1'></a>
<table class='gridtable' style="width: 1000">
	<thead >
		<th style='width: 100'>Image</th>
		<th style='width: 100'>Global Item ID</th>
		<th style='width: 50' >Status</th>
		<th style='width: 500'>Title</th>
		<th style='width: 100'>Price</th>
		<th style='width: 100'>Inventory ID</th>
		<th style='width: 100'>Operation</th>	
	</thead>
	<tbody id='tblItems'></tbody>
</table>
<br/>
<div id='divNavigation'>
	Total <span id='spnTotal' style='color: red'>0</span>&nbsp items found!&nbsp&nbsp&nbsp
	Page <span id='spnPageIndex' style='color: red'></span> &nbspin&nbsp <span id='spnTotalPages' style='color: red'></span> &nbsppages.
	<a href="#C1" onclick='prev()'>Prev</a> <a href="#C1" onclick='next()' >Next</a> Goto <input id='iptGoto' style='width: 40' /><input id='iptGotoPage' type='button' value='GO' onclick='goto()'/>
</div>




