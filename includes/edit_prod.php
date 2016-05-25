<h1 class="content-header">Edit Product Info</h1>
<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">
<?php

//Open DB connection
include('includes/inc_connect.php');

//Query for the selected product
$query = $db->prepare("SELECT * FROM products WHERE id = :id");
$query->bindParam(':id',$_GET['id']);
$query->execute();
$row = $query->fetch();

if (isset($_REQUEST['update'])) { //Check if 'update' button has been clicked

  /*** Set placeholders for all $_POST values ***/
  if (isset($_POST['name'])) {
    $name_value = $_POST['name'];
  }
  if (isset($_POST['price'])) {
    $price_value = $_POST['price'];
  }
  if (isset($_POST['description'])) {
    $description_value = $_POST['description'];
  }
  if (isset($_POST['condition'])) {
    $condition_value = $_POST['condition'];
  }
  if (isset($_POST['qty'])) {
    $qty_value = $_POST['qty'];
  }
  if (isset($_POST['iname'])) {
    $iname_value = $_POST['iname'];
  }
  if (isset($_POST['category'])) {
    $category_value = $_POST['category'];
  }

  // Start validation
  function validPrice($data) {
    global $errCount;
    $data = trim($data);
    $pattern = "/\d{1,10}\.\d{2}/";
    if (!preg_match($pattern, $data)) {
      $errCount++;
    } else {
      return $data;
    }   
  }
  function validQty($data) {
    global $errCount;
    $data = trim($data);
    $pattern = "/\d{1,5}/";
    if (!preg_match($pattern, $data)) {
      $errCount++;
    } else {
      return $data;
    }   
  } 
  function validDescription($data) {
    $data = trim($data);
    return $data;  
  } 

  //Run validations
  validPrice($_POST['price']);
  validQty($_POST['qty']);
  validDescription($_POST['description']);

  //If no erros exist, add everything to the database
  if ($errCount > 0) {
  	echo "<div class='alert alert-danger' role='alert'>";
  	echo "<h4 style='margin:0;'>Please fix your ".$errCount." error(s) in the form below.</h4>";
  	echo "</div>";
  } else {

    try {

      //Get the current time and save it as $dateModified
      $dateModified = time();

      include('includes/inc_connect.php');
      $sql = "UPDATE products SET name = :name,
                                  price = :price,
                                  description = :description,
                                  qty = :qty,
                                  cond = :condition,
                                  category = :category,
                                  dateModified = :dateModified   
                                  WHERE id = :id";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':name',$_POST['name']);
      $stmt->bindParam(':price',$_POST['price']);
      $stmt->bindParam(':description',$_POST['description']);
      $stmt->bindParam(':condition',$_POST['condition']);
      $stmt->bindParam(':qty',$_POST['qty']);
      $stmt->bindParam(':id',$_GET['id']);
      $stmt->bindParam(':category',$_POST['category']);
      $stmt->bindParam(':dateModified',$dateModified);

      if ($stmt->execute()) 
      { 
        //If successful...
        $success = TRUE;
        //Success notification
        echo "<div class='alert alert-success' role='alert' style='line-height:34px;overflow:auto;'>";
        echo "<h4 style='margin:0;'>Product updated successfully! <a href='?subpage=inventory'>Back to Inventory View</a></h4>";
        echo "</div>";
      } 
      else 
      {
        //Failure notification
        echo "<div class='alert alert-danger' role='alert' style='line-height:34px;overflow:auto;'>";
        echo "<h4 style='margin:0;'>There was an error adding the product to the database. Please contact the web administrator.</h4>";
        echo "</div>";        
      } //End else
    }//End try

    //Catch PDO errors
    catch(PDOException $e) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo "<h4 style='margin:0;'>Something went wrong.</h4>";
      echo "Error: ".$e->getMessage();
      echo "</div>";      
    }
  }//End else
}
?>
</div>
</div>

<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">

  <form action="" method="POST" enctype="multipart/form-data">

    <!-- Display product image -->
    <center><img width="200" src="<?php echo 'upload/'.$row['filename']; ?>">
    <h3>Product Information</h3></center>

    <!-- Name -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Product Name</span>
      <input type="text" name="name" class="form-control" value="<?php echo (isset($_POST['update']) ? $_POST['name'] : $row['name']) ?>" required>
    </div>

    <!-- Price -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Product Price</span>
      <input type="text" name="price" class="form-control" value="<?php echo (isset($_POST['update']) ? $price_value : $row['price']) ?>" required>
    </div>
    <!-- Invalid price warning label -->
    <?php 
    if (isset($_POST['update']) && (!validPrice($_POST['price']))) 
      echo "<p class='wrong bg-danger text-danger'>Please enter a valid Price (##.##)</p>"; 
    ?>

    <!-- Quantity -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Quantity</span>
      <input type="text" name="qty" class="form-control" value="<?php echo (isset($_POST['update']) ? $qty_value : $row['qty']) ?>" required>
    </div>
    <!-- Invalid quantity warning label -->
    <?php 
    if (isset($_POST['update']) && (!validQty($_POST['qty']))) 
      echo "<p class='wrong bg-danger text-danger'>Please enter a valid Quantity (Whole integer)</p>"; 
    ?> 

    <!-- Category -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Category Tags</span>
      <input type="text" 
             name="category" 
             class="form-control" 
             value="<?php echo (isset($_POST['update']) ? $category_value : $row['category']) ?>" 
             placeholder="Batman, TPB, Single, Used ..."required>
    </div>

    <!-- Condition dropdown menu. Populates the field with either the value from the database or the _POST value after update has been clicked. -->
    <select class="form-control input-lg" name="condition" style="margin-top:10px;" required>
      <option value="" disabled selected>Condition - Please select one</option>
      <option <?php echo (($condition_value=="New") || $row['cond'] == "New") ? "selected='selected'" : ""; ?> value="New">New</option>
      <option <?php echo (($condition_value=="Excellent") || $row['cond'] == "Excellent") ? "selected='selected'" : ""; ?> value="Excellent">Excellent</option>
      <option <?php echo (($condition_value=="Good") || $row['cond'] == "Good") ? "selected='selected'" : ""; ?> value="Good">Good</option>
      <option <?php echo (($condition_value=="Fair") || $row['cond'] == "Fair") ? "selected='selected'" : ""; ?> value="Fair">Fair</option>
      <option <?php echo (($condition_value=="Poor") || $row['cond'] == "Poor") ? "selected='selected'" : ""; ?> value="Poor">Poor</option>
    </select>

    <!-- Description label and text area -->
    <label for="description" style="margin-top:5px;">
      <span style="font-family:'Hind',sans-serif; font-size: 1.4em; font-weight: 200; margin-left: 15px;">Description:</span>
    </label>
    <textarea name="description" class="form-control" rows="5" col="100" required><?php echo (isset($_POST['update']) ? $description_value : $row['description']) ?></textarea>

    <!-- Update button -->
    <button type="submit" name="update" class="btn btn-lg btn-success pull-right" style="margin-top:10px;">Update</button>

  </form>
</div>
</div>