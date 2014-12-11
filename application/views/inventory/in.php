

<form role="form"
  action=<?=site_url('inventory/in/'.$item['Global_Item_ID'])?>
  method='POST'>
  <div class="panel panel-primary">
    <div class="panel-heading"><?php echo $title;?></div>
    <div class="panel-body">

      <font color='red'>
        <?php echo validation_errors(); ?>
        <?php echo isset($error_message) ? $error_message : ''; ?>
      </font>

      <h4><?=$item['title']?></h4>

      <div class="form-group col-sm-6">
        <label for="price" class="control-label">Price</label>
        <?=form_input('price', set_value('price', $item['expectedPrice']), 'class="form-control"');?>
      </div>
      <div class="form-group col-sm-6">
        <label for="quantity" class="control-label">Quantity</label>
        <?=form_input('quantity', set_value('quantity', '1'), 'class="form-control"');?>
      </div>


      <div class="col-sm-6">
        <div class="border-group">
          <div class="form-group">
            <label for="floor_price">Floor Price</label> <input
              name="floor_price" class="form-control"
              value="<?=set_value('floor_price')?>" /> <label
              for="Sales Split">Sales Split</label>
            <div class="input-group">
              <?=form_input('sales_split', set_value('sales_split', '30'), 'class="form-control"')?>
              <span class="input-group-addon">%</span>
            </div>
          </div>
          <button type="submit" class="btn btn-primary"
            name="submit_consignment" value="1">Consignment</button>
        </div>
      </div>

      <div class="col-sm-6">
        <div class="border-group">
          <div class="form-group">
            <label for="cost">Cost</label>
            <?=form_input('cost', set_value('cost'), 'class="form-control"')?>
          </div>
          <button type="submit" class="btn btn-primary"
            name="submit_buy_and_sell" value="1">Buy and Sell</button>
        </div>
      </div>
    </div>
  </div>

</form>

<div class="panel panel-primary">
  <div class="panel-heading">Item Details</div>
  <div class="panel-body">
    <table class='table'>
      <?php
      foreach ( $item as $key => $value ) {
        echo "<tr><th>$key</th><td>$value</td></tr>";
      }
      ?>
    </table>
  </div>
</div>

<div class="panel panel-primary">
  <div class="panel-heading">Owner Details</div>
  <div class="panel-body">
    <table class='table'>
      <?php
      foreach ( $user as $key => $value ) {
        echo "<tr><th>$key</th><td>$value</td></tr>";
      }
      ?>
    </table>
  </div>
</div>

<style>
.border-group {
	border: 1px solid #888;
	margin: 10 0;
	padding: 10;
	border-radius: 5
}
</style>