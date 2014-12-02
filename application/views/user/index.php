<script  type="text/javascript" > 

	var pageindex=0;
	var totalpages=0;

	$(function() {
		Query();
	});

	function Query()
	{
		pageindex=0;
		postQuery();
	}

	function postQuery()
	{
		var str = $("#iptUser").val();
		var query_string = $("#iptQueryString").val();
		var number = $("#slcNumber").val();
		var offset=pageindex*number;
		$.post(
			"<?=base_url()?>index.php/user/query_user",
			{user:str,limit:number,offset:offset},
			function(data){
				//alert(data);
				//return;
				var items = eval(data);
				var count = items[items.length-1];
				$("#spnTotal").text(count);
				totalpages=Math.ceil(count/number);	
				LoadItems(items);
				SetNavigation();
			}
		);
	}

	function LoadItems(items)
	{
		if (items.length<=1)
		{
			alert('No user found!');
			$("#tblUsers").html("");
			return;
		}
		var html="";
		for(var i=0;i<items.length-1;i++)
		{
			html+="<tr>";
			html+="<td>"+items[i].Global_User_ID+"</td>";
			html+="<td>"+items[i].firstName+"</td>";
			html+="<td>"+items[i].lastName+"</td>";
			html+="<td>"+items[i].alias+"</td>";
			html+="<td>"+items[i].email+"</td>";
			html+="<td>"+items[i].phoneNumber+"</td>";
			html+="<td>"+items[i].wechatId+"</td>";
			html+="<td>";
			html+="<a href='<?=base_url()?>index.php/items/itembyuser/"+items[i].Global_User_ID+"'>Items </a>&nbsp";
			html+="<a href='<?=base_url()?>index.php/inventories/inventoriesbyuser/"+items[i].Global_User_ID+"'>Inventories </a>&nbsp";
			html+="<a href='<?=base_url()?>index.php/order/orderitemsbyuser/"+items[i].Global_User_ID+"'>Order Items </a>";
			html+="</td>";
			html+="</tr>";	
		}
		$("#tblUsers").html(html);

	}


	function SetNavigation()
	{
		if (totalpages==0)
		$("#spnPageIndex").text('0');
		else $("#spnPageIndex").text(pageindex+1);
		$("#spnTotalPages").text(totalpages);
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

<p>Find the user: <input id='iptUser'  /><input type="button" onclick='Query()' value='Query' />
Show
<select style='width: 50px' id='slcNumber'>
  <option value ='10'>10</option>
  <option value ='20'>20</option>
  <option value ='50'>50</option>
  <option value ='100'>100</option>
</select>
users in each page.
<input type='button' value='Query' onclick='Query()' />
</p>
<hr/>
<a name='C1'></a>
<table class='gridtable' style="width: 1000">
	<thead>
		<th style='width: 50'>Global_User_ID</th>
		<th>First Name</th>
		<th>Last Name</th>
		<th>Alias</th>
		<th>Email</th>
		<th>Phone Number</th>
		<th>Wechat ID</th>
		<th>Link</th>
		
	</thead>
	<tbody id='tblUsers'></tbody>
</table>
<br/>
<div id='divNavigation'>
	Total <span id='spnTotal' style='color: red'>0</span>&nbsp inventories found!&nbsp&nbsp&nbsp
	Page <span id='spnPageIndex' style='color: red'></span> &nbspin&nbsp <span id='spnTotalPages' style='color: red'></span> &nbsppages.
	<a href="#C1" onclick='prev()'>Prev</a> <a href="#C1" onclick='next()' >Next</a> Goto <input id='iptGoto' style='width: 40' /><input id='iptGotoPage' type='button' value='GO' onclick='goto()'/>
</div>


<?php
?>