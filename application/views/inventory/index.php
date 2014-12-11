
<script type="text/javascript"> 
	var pageindex=0;
	var totalpages=0;

	$(function() {
		$("#iptUser").val(<?php echo $Global_User_ID;?>);
		$("#iptQueryString").val(<?php echo $id;?>);
		
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
			"<?=base_url()?>index.php/inventory/queryinventories",
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
			html+="<td>"+items[i].inventory_id+"</td>";
			html+="<td>"+items[i].Global_Item_ID+"</td>";
			html+="<td>"+items[i].title+"</td>";
			html+="<td>"+items[i].owner+"</td>";
			html+="<td>"+(items[i].floor_price?items[i].floor_price:'')+"</td>";
			html+="<td>"+items[i].price+"</td>";
			html+="<td>"+(items[i].cost?items[i].cost:'')+"</td>";
			html+="<td>"+items[i].sales_split+"</td>";
			html+="<td>"+items[i].quantity+"</td>";
			html+="<td>"+items[i].remainder_quantity+"</td>";
			html+="<td><a href='<?=base_url()?>index.php/inventory/print_label/"+items[i].inventory_id+"' target='blank' >Print</a></td>";
			html+="</tr>";
		}
		$("#tblItems").html(html);
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

<div class="panel panel-default">
  <div class="panel-body form-inline">
    <input id='iptQueryString' class="form-control"  placeholder="Item ID, Name or Description" style="width: 300px" />
    <input id='iptUser' class="form-control" placeholder="User ID" style="width: 300px" /> 
    <input type='button' class="btn btn-default" value='Query' onclick='Query()' />
  </div>
</div>

<a name='C1'></a>
<div class="panel panel-default">
  <div class="panel-body">
    <table class='table'>
      <thead>
        <th style='width: 100'>Inventory ID</th>
        <th style='width: 100'>Global Item ID</th>
        <th style='width: 500'>Title</th>
        <th style='width: 100'>Owner</th>
        <th style='width: 100'>Floor Price</th>
        <th style='width: 100'>Price</th>
        <th style='width: 100'>Cost</th>
        <th style='width: 100'>Sales Split</th>
        <th style='width: 100'>Quantity</th>
        <th style='width: 100'>Reaminder</th>
        <th style='width: 100'>Print</th>
      </thead>
      <tbody id='tblItems'></tbody>
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