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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
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
  <input class="form-control form-control-sm  w-75 filter" type="text" placeholder="Search" aria-label="Search"> <br>
  
</form>


<div id="myBtnContainer">
<div class="row">
 <div class="col-lg-4">
<!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button type="button" class="btn btn-info w-full" onclick="filterSelection('item1')">Item 1</button>
</div>
<div class="col-lg-4">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <button type="button" class="btn btn-info w-full" onclick="filterSelection('item2')">Item 2</button>
  </div>
<div class="col-lg-4">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
  <button type="button" class="btn btn-info w-full " onclick="filterSelection('item3')">Item 3</button> -->
  </div>
  </div>

  <div class="container">
    <p class="card-text">
	<?php
	$batas = 5;
	$halaman = isset($_GET['halaman'])?(int)$_GET['halaman'] : 1;
	$halaman_awal = ($halaman>1) ? ($halaman * $batas) - $batas : 0;	
	$Previous = $halaman - 1;
	$next = $halaman + 1;
	$data = $db_handle->runQuery("SELECT * FROM tblproduct");
	$jumlah_data = count($data);
	$total_halaman = ceil($jumlah_data / $batas);
	$product_array = $db_handle->runQuery("SELECT * FROM tblproduct ORDER BY id ASC limit $halaman_awal, $batas");
	if (!empty($product_array)) { 
		foreach($product_array as $key=>$value){
	?>
		<div class="product-item ml-3 mb-5">
			<form method="post" action="index.php?action=add&code=<?php echo $product_array[$key]["code"]; ?>">
			<?php
			// include ImgCompressor.php
			include_once('lib/ImgCompressor.class.php');

			// setting
			$setting = array(
			'directory' => 'product-images', // directory file compressed output
			'file_type' => array( // file format allowed
				'image/jpeg',
				'image/png',
				'image/gif'
			)
			);

			// create object
			$ImgCompressor = new ImgCompressor($setting);

			?>
			<div class="product-image"><img src="<?php $result = $ImgCompressor->run('product-images/'.$product_array[$key]["image"], 'jpg', 5); echo 'product-images/comp_'.$product_array[$key]["image"]; ?>" width="150px" height="150px"></div>
			<div class="product-tile-footer">
			<div class="product-title cardz" data-string="<?php echo $product_array[$key]["name"]; ?>" ><?php echo $product_array[$key]["name"]; ?></div>
			<div class="product-price"><?php echo "Rp. ".number_format($product_array[$key]["price"],0,',','.');?></div>
			<div class="cart-action">
			<input type="text" class="product-quantity" name="quantity" value="1" size="1" />			
		</div>
		<br><br><br>
		<input type="submit" value="Add to Cart" class="btn btn-primary btn-block waves-effect waves-light btn-block" />
	</div>
			</form>
			
		</div>
	<?php
		}
	}
	?>

</div>

</div>
<nav>
			<ul class="pagination justify-content-center">
				<li class="page-item">
					<a class="page-link" <?php if($halaman > 1){ echo "href='?halaman=$Previous'"; } ?>>Previous</a>
				</li>
				<?php 
				for($x=1;$x<=$total_halaman;$x++){
					?> 
					<li class="page-item"><a class="page-link" href="?halaman=<?php echo $x ?>"><?php echo $x; ?></a></li>
					<?php
				}
				?>				
				<li class="page-item">
					<a  class="page-link" <?php if($halaman < $total_halaman) { echo "href='?halaman=$next'"; } ?>>Next</a>
				</li>
			</ul>
		</nav>
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
$(".filter").on("keyup", function() {
  var input = $(this).val().toUpperCase();

  $(".cardz").each(function() {
    if ($(this).data("string").toUpperCase().indexOf(input) < 0) {
      $(this).hide();
    } else {
      $(this).show();
    }
  })
});

filterSelection("all")
function filterSelection(c) {
  var x, i;
  x = document.getElementsByClassName("cardz");
  if (c == "all") c = "";
  for (i = 0; i < x.length; i++) {
    w3RemoveClass(x[i], "show");
    if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
  }
}

function w3AddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
  }
}

function w3RemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {
      arr1.splice(arr1.indexOf(arr2[i]), 1);     
    }
  }
  element.className = arr1.join(" ");
}

// Add active class to the current button (highlight it)
var btnContainer = document.getElementById("myBtnContainer");
var btns = btnContainer.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function(){
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>
</BODY>
</HTML>