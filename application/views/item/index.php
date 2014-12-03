<script type="text/javascript"> 
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
			"<?=base_url()?>index.php/item/queryitem",
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

<div class="panel panel-default">
  <div class="panel-body form-inline">
    <input id='iptQueryString' class="form-control"
      placeholder="Item ID, Name or Description" style="width: 300px" />
    <input id='iptUser' class="form-control" placeholder="User ID"
      style="width: 300px" /> <input type='button'
      class="btn btn-default" value='Query' onclick='Query()' />
  </div>
</div>

<a name='C1'></a>
<div class="panel panel-default">
  <div class="panel-body">
    <table class='table'>
      <thead>
        <th nowrap>Image</th>
        <th nowrap>Item ID</th>
        <th nowrap>Status</th>
        <th style='width: 100%'>Title</th>
        <th nowrap>Price</th>
        <th nowrap>Inventory ID</th>
        <th nowrap>Operation</th>
      </thead>
      <tbody id='tblItems'></tbody>
      <tfoot>
        <tr>
          <td colspan="7">
            <div id='divNavigation'>
              Show <select id='slcNumber'>
                <option value='10'>10</option>
                <option value='20'>20</option>
                <option value='50'>50</option>
                <option value='100'>100</option>
              </select> items in each page. Total <span id='spnTotal'
                style='color: red'>0</span>&nbsp items
              found!&nbsp&nbsp&nbsp Page <span id='spnPageIndex'
                style='color: red'></span> &nbspin&nbsp <span
                id='spnTotalPages' style='color: red'></span>
              &nbsppages. <a href="#C1" onclick='prev()'>Prev</a> <a
                href="#C1" onclick='next()'>Next</a> Goto <input
                id='iptGoto' style='width: 40' /><input id='iptGotoPage'
                type='button' value='GO' onclick='goto()' />
            </div>
          </td>
        </tr>
      </tfoot>
    </table>
  </div>
</div>
