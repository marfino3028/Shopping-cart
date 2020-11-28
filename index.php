<?php
//18:47
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

<link href="dist/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="dist/jquery-3.4.1.min.js"></script>
<script src="dist/bootstrap/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"

<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<link href="style.css" type="text/css" rel="stylesheet" />
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
  <form class="form-inline d-flex justify-content-center md-form form-sm active-cyan active-cyan-2 mt-5">
	<i class="fas fa-search" aria-hidden="true"></i>
		<input class="form-control form-control-sm  w-75 filter" id="search" name="search" type="text" placeholder="Search" aria-label="Search">
	<br>
  
</form>

<div id="myBtnContainer">
<div class="row">
 <div class="col-lg-6">
  </div>
  </div>

	<div class="container">
    <div class="card-text">
	<div id="product-card"></div>
	<?php
	
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC");
	
	?>

	</div>
	</div>

</div>
<nav>
	<ul class="pagination justify-content-center"></ul>
</nav>
</div>

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
var productAry = <?= json_encode($product_array) ?>;
var searching  = '';

function initData(page = 1, search = '')
{
	if(productAry.length > 0)
	{
		let data = [];
		
		if(search != '')
		{
			searching = search;
			
			productAry.forEach(e => {
				if(e.name.toLocaleUpperCase().match(new RegExp(search)))	
				{
					data.push(e);
				}
			});
		}
		else
		{
			searching = '';
			data = productAry;
		}
		
		let txt 	= '';
		let i		= 0;
		let max 	= page == 1? 6 : page * 6;
		let paging	= Math.ceil(data.length / 6);
		let active  = '';
		
		
		for(let row=(max-6); row < data.length; row++)
		{
			if(row == max)
			{
				break;
			}
			else
			{
				txt += `
				<div class="product-item ml-3 mb-5 cardz">
					<form method="post" action="index.php?action=add&code=${data[row].code}">
						<div class="product-image">
							<img src="product-images/${data[row].image}" width="150px" height="150px">
						</div>
						<div class="product-tile-footer">
								<div class="product-title ">${data[row].name}</div>
								<div class="product-price">Rp ${new Intl.NumberFormat().format(data[row].price)}</div>
								<div class="cart-action">
								<input type="text" class="product-quantity" name="quantity" value="1" size="1" />			
							</div>
							<br><br><br>
							<input type="submit" value="Add to Cart" class="btn btn-primary btn-block waves-effect waves-light btn-block" />
						</div>
					</form>
				</div>`;
			}
		}
			
		$('.pagination').empty();
		
		$('.pagination').append(`<li class="page-item">
			<a class="page-link"  href="javascript:" onClick="${page > 1 ? 'paging('+ (page-1) +')' : ''}">Previous</a>
		</li>`);
		
		
		for(let page_in = 1; page_in <= paging; page_in++)
		{
			$('.pagination').append(`<li class="page-item ${page == page_in? 'active' : ''}">
				<a class="page-link" href="javascript:" onClick="paging(${page_in})">${page_in}</a>
			</li>`);
		}
		
		
		$('.pagination').append(`<li class="page-item">
			<a  class="page-link" href="javascript:" onClick="${paging == page? '' : 'paging('+ (page+1) +')'}">Next</a>
		</li>`);
			
		
		$('#product-card').empty();
		$('#product-card').append(txt);
	}
}

function paging(page)
{
	initData(page,searching);
}

initData(1);

$(".filter").on("keyup", function() {
	let input = $(this).val().toUpperCase();
	initData(1, input);
});
</script>
</BODY>
</HTML>