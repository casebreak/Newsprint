<?php
session_start();

if (isset($_SESSION['terms']['orders'])) {
	unset($_SESSION['terms']['orders']);
}
if (isset($_SESSION['terms']['inventory'])) {
	unset($_SESSION['terms']['inventory']);
}
if (isset($_POST['clearSearch'])) {
	unset($_SESSION['terms']['shop']);
}

//Connect to the DB
include('includes/inc_connect.php');

//Gather all data from the products table if their quantity is greater than 1
$stmt = "SELECT * FROM products WHERE qty > 0 ";

if (($_GET['category']) == 'New') {
	$stmt .= " AND category LIKE '%new%' ";
}
elseif (($_GET['category']) == 'Used') {
	$stmt .= " AND category LIKE '%used%' ";
}
elseif (($_GET['category']) == 'All') {
	$stmt .= " ";
}
elseif (($_GET['category']) == 'Recently Added') {
	//Set recent time to current time minus one day (86400 seconds)
	$recent = time() - 86400;
	$stmt .= " AND dateModified > '$recent' ";
}

//Search functionality. Searches for price, name, or condition
if (isset($_POST['go']) && !empty($_POST['search'])) {
	$_SESSION['terms'] = array();
	$_SESSION['terms']['shop'] = $_POST['search'];
	$stmt .= " AND name      LIKE '%".$_SESSION['terms']['shop']."%' 
							OR price     LIKE '%".$_SESSION['terms']['shop']."%'
							OR cond      LIKE '%".$_SESSION['terms']['shop']."%'
							OR category  LIKE '%".$_SESSION['terms']['shop']."%' ";							
}

if (isset($_SESSION['terms']['shop'])) {
	$stmt = "SELECT * FROM products 
					 WHERE qty > 0 
					 AND name     LIKE '%".$_SESSION['terms']['shop']."%' 
					 OR price     LIKE '%".$_SESSION['terms']['shop']."%' 
					 OR cond      LIKE '%".$_SESSION['terms']['shop']."%'
					 OR category  LIKE '%".$_SESSION['terms']['shop']."%' ";
}

// Sorting. Holds the value of search terms to enable sorting the search results
if ($_GET['sort'] == 'name_up') 
{
	$stmt .= " ORDER BY name DESC";	
} 
elseif ($_GET['sort'] == 'name_down') 
{
	$stmt .= " ORDER BY name ASC";
} 
elseif ($_GET['sort'] == 'price_up') 
{
	$stmt .= " ORDER BY price DESC";
} 
elseif ($_GET['sort'] == 'price_down') 
{
	$stmt .= " ORDER BY price ASC";
}

$result = $db->prepare($stmt);
$result->execute();

?>

<h1 class="content-header">Welcome to our Store</h1>

<?php
if (!isset($_SESSION['user'])) {
	echo "<center><h2>You must be <a href='?page=login'>logged in</a> to purchase.</h2></center>";
}
?>

<!-- Browse by category -->
<center>
<div class="dropdown">
  <button class="btn btn-default btn-lg dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    Browse By Category
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li><a href="?page=shop&category=Recently Added">Recently added comics</a></li>
    <li><a href="?page=shop&category=New">New comics</a></li>
    <li><a href="?page=shop&category=Used">Used comics</a></li>
    <li><a href="?page=shop&category=All">All comics</a></li>
  </ul>
</div>
</center>

<!-- Search bar -->
<form action="" method="post">
<div class="row">
  <div class="col-lg-6 col-lg-offset-3">
    <div class="input-group input-group-lg" style="background-color:#333333;padding:10px;border-radius:10px;">

    	<!-- Search box -->
      <input type="text" class="form-control" name="search" placeholder="Search for..." >

      <!-- Search button 'Go!' -->
      <span class="input-group-btn">
        <button class="btn btn-default" type="submit" name="go">Go!</button>
      </span>
    </div>
  </div>
</div>

<!-- Displays 'Searching for' below search bar -->
<center>
<?php 
echo (isset($_SESSION['terms']['shop'])) ? "<h3>Showing results for: <strong>".$_SESSION['terms']['shop']."</strong><button class='btn btn-warning' type='submit' name='clearSearch' style='margin-left:30px;'>Clear Search Terms</button></h3>" : "" ;
echo (isset($_GET['category'])) ? "<h3>Browsing by Category: <strong>".$_GET['category']."</strong></h3>" : "";
?>

</center>
<br>
</form>

<!-- List products in the table -->
<table class="table table-bordered table-striped" id="shopProducts">
	<!-- Table headers -->
	<thead>
		<tr>
			<!-- Image -->
			<th>Image<br>&nbsp;</th>

			<!-- Name -->
			<th>Name<br>
				<small>
					<!-- Sort Up/Down icons -->
					<a href="index.php?page=shop&sort=name_up"><span class="glyphicon glyphicon-chevron-up"></span></a>
					<a href="index.php?page=shop&sort=name_down"><span class="glyphicon glyphicon-chevron-down"></span></a>
				</small>
			</th>

			<!-- Description -->
			<th width="40%">Description<br>&nbsp;</th>

			<!-- Price -->
			<th>Price<br>
				<small>
					<!-- Sort Up/Down icons -->
					<a href="index.php?page=shop&sort=price_up"><span class="glyphicon glyphicon-chevron-up"></span></a>
					<a href="index.php?page=shop&sort=price_down"><span class="glyphicon glyphicon-chevron-down"></span></a>
				</small>
			</th>

<?php
//Only display the 'Add to cart' buttons if a user is signed in
if (isset($_SESSION['user'])) 
{
?>			
			<th>Buy<br>&nbsp;</th>
<?php 
} //End if (isset($_SESSION['user']))
?>
		</tr>
	</thead>
	<tbody>

	<!-- Start table body -->

<?php
//Loop through products in DB to poppulate the table
for ($i=0; $row = $result->fetch(); $i++) 
{ 
?>
		<tr>
			<!-- Image -->
			<td><center><img width="80" src="upload/<?php echo $row['filename']; ?>"></center></td>

			<!-- Name -->
			<td width="25%"><span style="font-weight: 600; font-size: 1.2em;"><?php echo $row['name']; ?></span></td>

			<!-- Description. Includes Condition and Quantity available -->
			<td style="text-align:left;">
				<?php echo $row['description']; ?>
				<br><br>
				<!-- Condition -->
				<span style="font-weight:600;">Condition: </span><?php echo $row['cond']; ?>
				<!-- In stock -->
				<span class="pull-right" style="font-weight:600;">In stock: <?php echo $row['qty'] ?></span>
			</td>

			<!-- Price -->
			<td><strong>$ </strong><?php echo number_format($row['price'],2); ?></td>

<?php
//Only display the 'Add to cart' buttons if a user is signed in
if (isset($_SESSION['user'])) {
?>
			<td>
				<a class="btn btn-primary" style="margin-bottom:5px;" 
					href="index.php?page=cart&qty=1&id=<?php echo $row['id']; ?>">Add to Cart<br>
					<span class="glyphicon glyphicon-shopping-cart"></span>
				</a>
			</td>

<?php
} //End if (isset($_SESSION['user']))
} //End for loop
?>

		</tr>
	</tbody>
</table>