<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8"
  lang="en-us">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo $title ?> - Weee!</title>

<link rel="stylesheet" href="<?=base_url('/css/bootstrap.min.css')?>">
<script src="<?=base_url('/js/jquery.min.js')?>"></script>
<script src="<?=base_url('/js/bootstrap.min.js')?>"></script>
</head>

<body id="content">
  <div class="container">

    <nav class="navbar navbar-default" role="navigation">
      <div class="container-fluid">
        <div class="collapse navbar-collapse"
          id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav">
          <?php 
          $navs = array('item'=>'Item', 'inventory'=>'Inventory', 'order'=>'Order', 'user'=>'User');
          foreach ($navs as $key=>$value){
          	echo '<li';
          	if (isset($title) && $title === $key)
          		echo ' class="active"';
          	echo '>'.anchor ( $key , $value).'</li>';
          }
          ?>
          </ul>
        </div>
      </div>
    </nav>
