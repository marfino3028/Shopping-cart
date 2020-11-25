<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(!empty($_GET["action"])) {
switch($_GET["action"]) {
	case "add":
		if(!empty($_POST["quantity"])) {
			$productByCode = $db_handle->runQuery("SELECT * FROM tblproduct WHERE code='" . $_GET["code"] . "'");
			$itemArray = array($productByCode[0]["code"]=>array('name'=>$productByCode[0]["name"], 'code'=>$productByCode[0]["code"], 'quantity'=>$_POST["quantity"], 'price'=>$productByCode[0]["price"], 'image'=>$productByCode[0]["image"]));
			
			if(!empty($_SESSION["cart_item"])) {
				if(in_array($productByCode[0]["code"],array_keys($_SESSION["cart_item"]))) {
					foreach($_SESSION["cart_item"] as $k => $v) {
							if($productByCode[0]["code"] == $k) {
								if(empty($_SESSION["cart_item"][$k]["quantity"])) {
									$_SESSION["cart_item"][$k]["quantity"] = 0;
								}
								$_SESSION["cart_item"][$k]["quantity"] += $_POST["quantity"];
							}
					}
				} else {
					$_SESSION["cart_item"] = array_merge($_SESSION["cart_item"],$itemArray);
				}
			} else {
				$_SESSION["cart_item"] = $itemArray;
			}
		}
	break;
	case "remove":
		if(!empty($_SESSION["cart_item"])) {
			foreach($_SESSION["cart_item"] as $k => $v) {
					if($_GET["code"] == $k)
						unset($_SESSION["cart_item"][$k]);				
					if(empty($_SESSION["cart_item"]))
						unset($_SESSION["cart_item"]);
			}
		}
	break;
	case "empty":
		unset($_SESSION["cart_item"]);
	break;	
}
}
?>
<HTML>
<HEAD>
<TITLE>Shopping cart</TITLE>
<link href="style.css" type="text/css" rel="stylesheet"/>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
	<link rel="stylesheet" href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
	<script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
.product-item{
border-radius: 10px;
}
.cart-action{
border-radius: 15px;
}
.active-cyan-2 input.form-control[type=text]:focus:not([readonly]) {
  border-bottom: 1px solid #4dd0e1;
  box-shadow: 0 1px 0 0 #4dd0e1;
}
.active-cyan input.form-control[type=text] {
  border-bottom: 1px solid #4dd0e1;
  box-shadow: 0 1px 0 0 #4dd0e1;
}
.active-cyan .fa, .active-cyan-2 .fa {
  color: #4dd0e1;
}
.btn-info {
  font-family: Raleway-SemiBold;
  font-size: 15px;
  color: rgba(91, 192, 222, 0.75);
  letter-spacing: 1px;
  line-height: 25px;
  border: 5px solid rgba(91, 192, 222, 0.75);
  border-radius: 12px;
  background: transparent;
  transition: all 0.3s ease 0s;
}

.btn-info:hover {
  color: #FFF;
  background: rgba(91, 192, 222, 0.75);
  border: 5px solid rgba(91, 192, 222, 0.75);
}
</style>
</HEAD>
<BODY>
<!--Grid row-->
<div class="row">
  
  <!--Grid column-->
<div class="col-lg-6">

<!-- products -->
<div id="product-grid">
<div class="card border-info ml-3" style="max-width: 80rem;">
  <div class="card-header">Products  
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <a href="upload.php" type="button" class="btn btn-success">Add Products</a></div> <br>
	
    <p class="card-text">
	<?php	
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
			?>
		<div class="product-item ml-3 mb-5">
			<form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<table id="table_id" class="table table-striped table-bordered" style="width:100%">
			<tr>
			<td><div class="product-image" ><img src="<?php echo 'product-images/'.$product_array[$key]["image"]; ?>" width="150px" height="150px"></div></td>
			<div class="product-tile-footer">
			<td><div class="product-title " ><?php echo $product_array[$key]["name"]; ?></div></td>
			<td><div class="product-price"><?php echo "Rp. ".number_format($product_array[$key]["price"],0,',','.');?></div></td>
			<td><div class="cart-action"><input type="text" class="product-quantity" name="quantity" value="1" size="1" /></div></td>		
		
		<td><input type="submit" value="Add to Cart" class="btn btn-primary btn-block waves-effect waves-light btn-block" /></td>
	</div>
	</tr>
	</table>
			</form>		
		</div>
	<?php
		}
	}
	?>
</div>
	</p>
  </div>
</div>
	

<!--Grid column-->
<div class="col-lg-6">
<!-- checkout -->
<div id="shopping-cart">
<div class="card border-info mb-3" style="max-width: 50rem;">
  <div class="card-header">Shopping Cart</div>
  <div class="card-body text-info">
    <p class="card-text">
	<a id="btnEmpty" href="index.php?action=empty">Empty Cart</a>
<?php
if(isset($_SESSION["cart_item"])){
    $total_quantity = 0;
    $total_price = 0;
?>	
<table class="tbl-cart" cellpadding="10" cellspacing="1">
<tbody>
<tr>
<th style="text-align:left;">Name</th>
<th style="text-align:left;">Code</th>
<th style="text-align:right;" width="5%">Quantity</th>
<th style="text-align:right;" width="10%">Unit Price</th>
<th style="text-align:right;" width="10%">Price</th>
<th style="text-align:center;" width="5%">Remove</th>
</tr>	
<?php		
    foreach ($_SESSION["cart_item"] as $item){
        $item_price = $item["quantity"]*$item["price"];
		?>
				<tr>
				<td><img src="<?php echo 'product-images/comp_'.$item["image"]; ?>" class="cart-item-image" /><?php echo $item["name"]; ?></td>
				<td><?php echo $item["code"]; ?></td>
				<td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
				<td  style="text-align:right;"><?php echo "Rp.  ".$item["price"]; ?></td>
				<td  style="text-align:right;"><?php echo "Rp.  ". number_format($item_price,0,',','.'); ?></td>
				<td style="text-align:center;"><a href="index.php?action=remove&code=<?php echo $item["code"]; ?>" class="btnRemoveAction"><img src="icon-delete.png" alt="Remove Item" /></a></td>
				</tr>
				<?php
				$total_quantity += $item["quantity"];
				$total_price += ($item["price"]*$item["quantity"]);
		}
		?>

<tr>
<td></td>
<td colspan="2" align="right">Total:</td>
<td align="right"><?php echo $total_quantity; ?></td>
<td align="right" colspan="2"><strong><?php echo "Rp.  ".number_format($total_price,0,',','.'); ?></strong></td>

</tr>
</tbody>
</table> <br>
<input type="submit" value="Checkout" class="btn btn-primary btn-block waves-effect waves-light btn-block" />
  <?php
} else {
?>
<div class="no-records">Your Cart is Empty</div>
<?php 
}
?>	
	</p>
  </div>
</div>
</div>
</div>
</div>
<script>
$(function () {
    $('#example').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
    })
  })
</script>
</BODY>
</HTML>