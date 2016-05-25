<?php
session_start();

//Set timezone to Central Time/Chicago
date_default_timezone_set('America/Chicago');

include('inc_connect.php');

//Get all product info from DB
$sql = "SELECT * FROM products WHERE id = :id";
$stmt = $db->prepare($sql);
$stmt->bindParam(':id',$_GET['id']);
$stmt->execute();

//$row will be used to target a specific field in the DB
$row = $stmt->fetch();

//If shopping cart doesn't exist, create an array for it
if (!isset($_SESSION['shoppingCart']) && !isset($_GET['e'])) 
{
	$_SESSION['shoppingCart'] = array();

	//Create an empty array that will later be used to check for duplicate items.
	$cleanArray = array();
}

//Only add item if the item ID gets passed
if (isset($_GET['id'])) 
{	
	//Add an item by storing it in an array
	$item = array(
		'ID' => $_GET['id'],
		'Name' => $row['name'],
		'Price' => $row['price'],
		'Quantity' => $_GET['qty'], //Defaults to '1' qty
		'Available' => $row['qty'] //This is how many of the item is available
	);

	//Add the item to the shopping cart
	$_SESSION['shoppingCart'][] = $item;

	//Redirect back to shopping cart page. Also clears the private URL Variables
	header('location: index.php?page=cart');

}
elseif (isset($_GET['remove'])) 
{
	//Removed the item from the cart
	unset($_SESSION['shoppingCart'][$_GET['remove']]);

	//Redirect back to shopping cart page. Also clears the private URL Variables
	header('location: index.php?page=cart');

}
elseif (isset($_GET['empty'])) 
{
	//Clear the shopping cart session
	unset($_SESSION['shoppingCart']);
	unset($_SESSION['totalItems']);
	unset($_SESSION['totalPrice']);

	//Redirect back to shopping cart page. Also clears the private URL Variables
	header('location: index.php?page=cart&e');

}
elseif (isset($_GET['update'])) 
{
	//Update quantity for all items
	foreach ($_GET['items_qty'] as $itemID => $qty) 
	{
		//If the quantity is "0" remove it from the cart
		if ($qty == 0) 
		{
			//Remove it from the cart
			unset($_SESSION['shoppingCart'][$itemID]);
		}

		//If quantity is more than 1, update to the new Quantity
		elseif ($qty >= 1) 
		{
			//Update to the new Qty
			$_SESSION['shoppingCart'][$itemID]['Quantity'] = $qty;
		}
	}
	//Redirect back to shopping cart page. Also clears the private URL Variables
	header('location: index.php?page=cart');
}
elseif (isset($_GET['checkout'])) 
{

  try {

		$order = json_encode($_SESSION['shoppingCart']);
		$orderDecode = json_decode($order);

		$orderDate = time();
		$status = "Pending";

    include('inc_connect.php');
    $query = "INSERT INTO orders (products_InOrder,
    															orderDate,
    															customer,
    															totalPrice,
    															status) 
	                                VALUES 
	                               (:order,
	                               	:orderDate,
	                               	:customer,
	                               	:totalPrice,
	                               	:status)";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':order',$order);
    $stmt->bindParam(':orderDate',$orderDate);
    $stmt->bindParam(':customer',$_SESSION['user']);
    $stmt->bindParam(':totalPrice',$_SESSION['totalPrice']);
    $stmt->bindParam(':status',$status);    

    //Update Available Quantity in the DB when user checks out
    foreach ($_SESSION['shoppingCart'] as $itemNum => $item) 
		{

			$newQty = $item['Available'] - $item['Quantity'];

			$add = "UPDATE products SET qty = :qty WHERE id = :id";

			$run = $db->prepare($add);
			$run->bindParam(':qty',$newQty);
			$run->bindParam(':id',$item['ID']);
			$run->execute();
		}

    if ($stmt->execute())
    {
      $success = TRUE;
      echo "<div class='alert alert-success' role='alert' style='line-height:34px;overflow:auto;'>";
      echo "<h4 style='margin:0;'>Thank you! We will begin processing your order.</h4>";
      echo "</div>";

      //Clear cart session variables if order is successfully added
      unset($_SESSION['shoppingCart']);
			unset($_SESSION['totalItems']);
			unset($_SESSION['totalPrice']);
    } 
    else 
    {
      echo "<div class='alert alert-danger' role='alert' style='line-height:34px;overflow:auto;'>";
      echo "<h4 style='margin:0;'>There was an error with your order. Please contact us for assistance.</h4>";
      echo "</div>";        
    }
  }//End try

  catch(PDOException $e) {
    echo "<div class='alert alert-danger' role='alert'>";
    echo "<h4 style='margin:0;'>Something went wrong.</h4>";
    echo "Error: ".$e->getMessage();
    echo "</div>";      
  }

	//header('location:?page=shop');
}

/* Go thru the shopping cart and store each index of the array into the clean array that was created previously. The reason for this being: A user can add duplicates of any item and instead of adding a new line in the cart, it increases the quantity. */
if (isset($_SESSION['shoppingCart'])) 
{
	foreach ($_SESSION['shoppingCart'] as $item) 
	{
		if ($cleanArray[$item['ID']]) 
		{
			$cleanArray[$item['ID']]['Quantity'] += $item['Quantity'];
		} 
		else 
		{
			$cleanArray[$item['ID']] = $item;
		}
	}

	//Replace the contents of the session cart with improved $cleanArray values
	$_SESSION['shoppingCart'] = $cleanArray;
}

?>
<h1 class="content-header">Your Cart</h1>
<center>
	<h3><a href="index.php?page=shop">&lt;&lt;&nbsp;Keep shopping</a></h3><br>
</center>

<div style="overflow: hidden;"> <!-- Ensures the form buttons get floated within the form -->
<form action="index.php?page=cart" method="GET" name="cart">

<?php ob_start(); //Turn on output buffering ?>

<table class="table table-bordered table-striped" id="shopping-cart">
	<!-- Table Headers -->
	<thead>
		<tr>
			<th>Item#</th>
			<th>Name</th>
			<th>Price</th>
			<th>Qty</th>
			<th>&nbsp;</th>
			<th>Total</th>
		</tr>
	</thead>
	<tbody>

<?php 

//Ensure shopping cart session is not empty before trying to populate the table
if (!empty($_SESSION['shoppingCart'])) 
{

//Go thru the shopping cart session array and populate the table
foreach ($_SESSION['shoppingCart'] as $itemNum => $item) 
{
	//Keep a running total of both price and number of items in the cart
	$totalPrice += $item['Quantity'] * $item['Price'];
	$totalItems += $item['Quantity'];
	$_SESSION['totalPrice'] = $totalPrice;
	$_SESSION['totalItems'] = $totalItems;

?>

	<!-- Table rows for each item in the shopping cart -->
	<tr id="item <?php echo $itemNum; ?>">
		<!-- ID -->
		<td width="10%">NPC_<?php echo $item['ID']; ?></td>
		<!-- Item Name -->
		<td><strong><?php echo $item['Name']; ?></strong></td>
		<!-- Item Price -->
		<td><strong>$ </strong><?php echo number_format($item['Price'],2); ?></td>
		<!-- Quantity as input field -->
		<td><input name="items_qty[<?php echo $itemNum; ?>]&page=cart"
							 type="number"
							 id="item<?php echo $itemNum; ?>_qty"
							 min="1"
							 max="<?php echo $item['Available']; ?>"
							 value="<?php echo $item['Quantity']; ?>"
							 class="center-input">
				<br><?php echo $item['Available']; ?> in stock</td>
		<!-- 'Remove' button -->
		<td><a class="btn btn-lg btn-warning" style="border:1px solid black;color:#000;" href="?page=cart&remove=<?php echo $itemNum; ?>">Remove</a></td>
		<!-- Line total -->
		<td><strong>$ </strong><?php echo number_format($item['Quantity'] * $item['Price'],2); ?></td>
	</tr>

<?php
}//End foreach table population
}//End if (!empty) check
else 
{
	//If the shopping cart is empty, set total items = 0
	$_SESSION['totalItems'] = 0;
}
?>

	</tbody>
</table>
<br>

<!-- Subtotal, Tax, Shipping, Total, etc. -->
<div class="order-box pull-left">

	<?php
		//Declare money variables
		$taxes = round(($totalPrice * .075), 2);

		//Shipping should be set to 0 if no items are in the cart
		if (empty($_SESSION['shoppingCart'])) 
		{
			$shipping = 0;
		} 
		else 
		{
			$shipping = 10;
		}

		$total = $totalPrice + $taxes + $shipping;
	?>

	<!-- Order summary table -->
	<table class="table table-bordered" id="order-summary">
		<thead>
			<tr>
				<th class="order-summary">Subtotal</th>
				<th class="order-summary">Taxes</th>
				<th class="order-summary">Shipping</th>
				<th class="order-summary">Total</th>
			</tr>
		</thead>
		<!-- number_format() is used to round the numbers -->
		<tbody>
			<tr>
				<td>$ <?php echo number_format($totalPrice,2); ?></td>
				<td>$ <?php echo number_format($taxes,2); ?></td>
				<td>$ <?php echo number_format($shipping,2); ?></td>
				<td>$ <?php echo number_format($total,2); ?></td>
			</tr>
		</tbody>
	</table>

</div>

<?php
//Ensure cart buttons are only visible if items are in shopping cart
if (isset($_SESSION['shoppingCart'])) 
{
?>
<!-- Update, empty cart and checkout buttons -->
<div class="pull-right cart-buttons">

	<a class="btn btn-primary btn-lg" style="margin-right:5px;" 
					href="index.php?page=cart&checkout=y">Checkout</a>	

	<button class="btn btn-success btn-lg" style="margin-right:5px;" 
					type="submit" 
					name="update" 
					id="update" 
					value="Update Cart">Update Cart</button>

	<a class="btn btn-lg btn-danger" href="?page=cart&empty">Empty Cart</a>

</div>

<?php 
}
//outputs the content of internal buffer to $_SESSION['shoppingCart_HTML']
$_SESSION['shoppingCart_HTML'] = ob_get_flush();

?>

</form>
</div>
<!-- <pre>
<?php 
// print_r($_SESSION['shoppingCart']);
// print_r($order);
// print_r($orderDecode);
?>
</pre> -->

<table class="table table-bordered table-striped">
	<thead>
	</thead>
</table>
