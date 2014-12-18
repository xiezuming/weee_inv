<script  type="text/javascript" >

	$(function() {
	  GetTaxRate();
	  GetChannels();
	});

	function GetTaxRate()
	{
	  $.post(
	    "<?=base_url()?>index.php/settings/gettaxrate",
		function(data){
		  $("#iptTaxRate").val(data);
		}
	  );
	}

	function SaveTaxRate()
	{
		var t=$("#iptTaxRate").val();
		if ($.isNumeric(t))
		{
			$.post(
				"<?=base_url()?>index.php/settings/settaxrate",
				{t:t},
				function(data)
				{
					if(data=='OK') alert('Tax rate saved!');
					else alert('Save failed!');
				}		
			);
		}
		else alert('Input error!');
	}

	function GetChannels()
	{
	  $.post(
	    "<?=base_url()?>index.php/settings/getchannels",
	    function(data)
	    {
		  var values = eval(data);
	      var ChannelDefault = values[values.length-1];
	      var html="";
	      $("#iptDefaultChannel").val(ChannelDefault);
	      for (var i=0;i<values.length-1;i++)
	      {
		    html+='<input class="form-control" value="'+values[i].setting_value+'" style="width: 200px" readonly>';
		    if(ChannelDefault!=values[i].setting_value){
			  html+=' <input type="button" class="btn btn-default" value="Delete" style="width: 100px" onclick="DeleteChannel('+values[i].setting_ID+')">';
			  html+=' <input type="button" class="btn btn-default" value="Default" style="width: 100px" onclick="DefaultChannel('+"'"+ values[i].setting_value +"'"+')">';
		    }
			html+=" <br/><br/>"
		  }
		  $("#divChannels").html(html);
		}
	  );
	}

	function NewChannel()
	{
	  var channel=$("#iptNewChannel").val();
	  if (channel=="") return;
      $.post(
    	"<?=base_url()?>index.php/settings/addchannel",
    	{channel:channel},
    	function(data)
    	{
          if(data=="EXIST")  alert("Channel already saved!");
          else if(data!="OK") alert("Add channel failed!");
          else GetChannels();
        }     
      );
	}

	function DeleteChannel(channel)
	{
	  $.post(
        "<?=base_url()?>index.php/settings/deletechannel",
        {channel:channel},
        function(data)
        {
          if(data!="OK")alert("Channel delete failed!");
          else GetChannels();
        }
      );
	}

	function DefaultChannel(channel)
	{
	  $.post(
	    "<?=base_url()?>index.php/settings/defaultchannel",
	    {channel:channel},
	    function(data)
	    {
		  if(data=="OK") GetChannels();
		  else alert("Set default failed!");
		}
	  );
	}



</script>



<h3>Tax Rate setting</h3>
<div class="panel panel-default">
  <div class="panel-body form-inline">
    Tax Rate: <input id='iptTaxRate' class="form-control" style="width: 100px" >%
    <input type='button' class="btn btn-default" value='Save' onclick='SaveTaxRate()' >
  </div>
</div>
<hr/>

<h3>Channels setting</h3>
<div class="panel panel-default">
  <div class="panel-body form-inline">
    Default channel: 
    <input id="iptDefaultChannel" class="form-control" style="width: 200px" readonly>
    &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
    New Channnel(No more than 3 characters): 
    <input id='iptNewChannel' class="form-control" style="width: 200px" placeholder="New Channel">
    <input type='button' class="btn btn-default" value="Add" onclick="NewChannel()">
    <hr/>
    <div id='divChannels'>
      <input class="form-control" value="str" style="width: 200px" readonly><input type="button" class="btn btn-default" value="Delete" style="width: 100px"><br/><br/>
    </div>
    
  </div>
</div>
<hr/>