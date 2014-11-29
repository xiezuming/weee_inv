<script  type="text/javascript" > 

	var arrayObj = new Array();
	var indexofcart=0;

	$(function() {
		
	});
	
	function Query()
	{
		var id = $("#iptQueryItem").val();
		$.post(
			"<?=base_url()?>index.php/order/queryitem",
			{itemid:id},
			function(data){
				if (data.result=='SUCCESS') AddToCart(data);
				else alert('Item not found!');
			}
		);
	}

	function deleteitem(index)
	{
		var i;
		for(i=0;i<arrayObj.length;i++)
		{
			if (arrayObj[i].index==index)
			{
				arrayObj.splice(i,1);
				break;
			} 
		}

		for(i=0;i<arrayObj.length;i++)
		{
			arrayObj[i].index=i;	
		}
		
		LoadCart();
		GetSum();
	}

	function LeftQuantity(index,data)
	{
		var stock=data['stockquantity'];
		var i;
		for(i=0;i<arrayObj.length;i++)
		{
			if (arrayObj[i].index==index) continue;
			if (arrayObj[i].id==data.id)
			{
				stock-=arrayObj[i].purchase_quantity;
			}
		}
		return stock;
	}
	
	function AddToCart(data)
	{
		indexofcart++;
		data['index']=indexofcart;
		data['purchase_quantity']=1;
		if (LeftQuantity(-1,data)>=1)
		{
			arrayObj.push(data);
			LoadCart();
			GetSum();
		}
		else alert('Not enough item!');
	}

	function checkout()
	{
		if (confirm("Do you really want to check out?") == false) return;
		if (arrayObj.length==0){
			alert("No item selected!");
			return;
		}
        var items=new Array();
		$.each(arrayObj,function(name,value){
			var item={};
			item['id']= this.id;
			item['purchase_quantity']= this.purchase_quantity;
			items.push(item);
		});
		var sum = $("#spnSum").html();
		var discount=$("#iptDiscount").val();
		var tax=$("#spnTax").html();
		var total=$("#spnTotal").html(); 
		var customer = $("#iptCustomer").val();

		$.post(
			"<?=base_url()?>index.php/order/addorder",
			{customer:customer,sum:sum,discount:discount,tax:tax,total:total,data:items},
			function(data){
				if (data=='OK')
				{ 
					alert('Order saved!');
					location.href('<?=base_url()?>index.php/order');
				}
				else alert('ERROR!');
			}
		);
	}

	function SetQuantity(index,quantity)
	{
		for(var i=0;i<arrayObj.length;i++)
		{
			if (arrayObj[i].index==index) 
			{
				var left = LeftQuantity(index,arrayObj[i]);
				if (eval(quantity)<=eval(left)) arrayObj[i].purchase_quantity=quantity;
				else arrayObj[i].purchase_quantity=left;
				return arrayObj[i].puechase_quantity;
			}
		}
	}

	function GetSum()
	{
		if(arrayObj.length==0) {
			$("#spnSum").html('0');
			$("#spnTax").html('0');
			$("#spnTotal").html('0'); 

			return;	
		}
		var i;
		var sum=0,total=0,taxrate=0.07,tax=0;
		var discount=$("#iptDiscount").val();
		
		for(i=0;i<arrayObj.length;i++)
		{
			sum+=arrayObj[i].price*arrayObj[i].purchase_quantity;
		}
		tax= (sum-discount)*taxrate;
		total = sum-discount+tax;
		$("#spnSum").html(sum);
		tax=tax.toFixed(2);
		$("#spnTax").html(tax);
		$("#spnTotal").html(total);
	}

	function OnQuantityChange(index)
	{
		
		var ex = /^\d+$/;
		var str=$("#iptPurchaseQuantity"+index).val();
		if (ex.test(str)) {
			var q=SetQuantity(index,str);
			if (str>q){
				alert('Only '+q+' left');
				$("#iptPurchaseQuantity"+index).val(q);
			}
			GetSum();
		}
		else
		{
			alert("Input Error!");
			$("#iptPurchaseQuantity"+index).val("1");
			SetQuantity(index,1);
			GetSum();
		}
	}

	function OnDiscountChange()
	{
		var discount= $("#iptDiscount").val();
		if (isNaN(discount)) {
			alert('Input Error!');
			$("#iptDiscount").val('0');
		}
		GetSum();
	}

	function LoadCart()
	{
		var html='';
		$.each(arrayObj,function(name,value){
			html+="<tr>";
			html+="<td>"+this.id+"</td>";
			html+="<td>"+this.title+"</td>";
			html+="<td>"+this.price+"</td>";
			html+="<td>"+this.stockquantity+"</td>";
			html+="<td><input id='iptPurchaseQuantity"+this.index+"' style='width:90' value='"+this.purchase_quantity+"' onchange='OnQuantityChange("+this.index+")'/>"+"</td>";
			html+="<td>"+"<input type='button' value='Delete' onclick='deleteitem("+this.index+")'/></td>";
			html+="</tr>";
		});
		$("#tblCart1").html(html);
	};
	

</script>

<br/>
<p>Inventory ID:
	<input id='iptQueryItem' onkeydown="if(event.keyCode==13) Query()"/>
	<input type="button" value="Query" onclick="Query()"/>
</p>
<hr/>
<h3>Order</h3>
<table id='tblCart' class='gridtable' style="width: 1200">
	<thead>
	<tr>
	<th style='width: 100'>Inventory Id</th>
	<th style='width: 500'>Title</th>
	<th style='width: 100'>Price($)</th>
	<th style='width: 100'>Available Quantity</th>
	<th style='width: 100'>Purchase Quantity</th>
	<th>Delete</th>
	</tr>
	</thead>
	<tbody id='tblCart1' ></tbody>

</table>
<hr/>

<table class='gridtable'>
	<tr>
		<td style="width: 200">Customer</td>
		<td style="width: 200;text-align:right"><input id='iptCustomer' style="text-align:right" value='Anonymous'/> </td>
	
	</tr>
	<tr>
		<td style="width: 200">Total before tax:</td>
		<td style="width: 200;text-align:right">$<span id='spnSum' >0</span></td>
	</tr>
	<tr>
		<td >Discount</td>
		<td style="text-align:right">$<input style="text-align:right" id='iptDiscount' value='0' onchange='OnDiscountChange()'/></td>
	</tr>
	<tr>
		<td >Tax:</td>
		<td style="text-align:right">$<span id='spnTax' >0</span></td>
	</tr>
	<tr>
		<td >Total:</td>
		<td style="text-align:right">$<span id='spnTotal' >0</span></td>
	</tr>
</table>

<table>
	<tr>
		<td style="width: 200"></td>
		<td style="width: 200;text-align:right"><input type='button' value='Check Out' onclick='checkout()'/></td>
	</tr>
</table>



<?php




?>