<h1 class="content-header">Add Product</h1>
<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">
<?php

$tooLarge = FALSE;
$dupImg = FALSE;

if (isset($_REQUEST['add'])) { //Check if 'add' button has been clicked

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
  if (isset($_POST['qty'])) {
    $qty_value = $_POST['qty'];
  }
  if (isset($_POST['iname'])) {
    $iname_value = $_POST['iname'];
  }
  if (isset($_POST['condition'])) {
    $condition_value = $_POST['condition'];
  }
  if (isset($_POST['category'])) {
    $category_value = $_POST['category'];
  }

  //Start validation
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
  function validSize($data) {
    global $errCount;
    global $tooLarge;
    global $dupImg;
    if ($data > 500000) {
      $errCount++;
      $tooLarge = TRUE;
    } elseif (file_exists("upload/".$_FILES['file']['name'])) {
      $errCount++;
      $dupImg = TRUE;
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
  validSize($_FILES['file']['size']); //This will also check for duplicates
  $desc = validDescription($_POST['description']);

  /* *** IMAGE ADD *** */
  $iname = $_POST['iname'];
  $file = $_FILES['file']['name'];
  $size = $_FILES['file']['size'];

  $allowedExts = array("gif", "jpeg", "jpg", "png");
  $temp = explode(".", $_FILES["file"]["name"]); 
  $extension = end($temp);

  //Check to see if file has allowed extension. If yes, add image to uploads folder.
  if ((($_FILES["file"]["type"] == "image/gif")
  || ($_FILES["file"]["type"] == "image/jpeg")
  || ($_FILES["file"]["type"] == "image/jpg")
  || ($_FILES["file"]["type"] == "image/pjpeg")
  || ($_FILES["file"]["type"] == "image/x-png")
  || ($_FILES["file"]["type"] == "image/png"))
  && in_array($extension, $allowedExts)) 
  {
    //Check for file errors
    if ($_FILES["file"]["error"] > 0) 
    {
      echo "<div class='alert alert-danger' role='alert'>";
      echo "<h4 style='margin:0;'>Return Code: ".$_FILES['file']['error']."</h4>";
      echo "</div>";
    }
    //If no errors, move the file into the upload folder  
    elseif ($errCount == 0) 
    {
      move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $_FILES["file"]["name"]);
    }
  } //End check for extension if chain

  //If no errors exist, add everything to the database
  if ($errCount > 0) 
  {
  	echo "<div class='alert alert-danger' role='alert'>";
  	echo "<h4 style='margin:0;'>Please fix your ".$errCount." error(s) in the form below.</h4>";
  	echo "</div>";
  } else {

    try {

      $dateModified = time();

      include('inc_connect.php');
      $sql = "INSERT INTO products (name,
                                    price,
                                    description,
                                    qty,
                                    cond,
                                    iname,
                                    filename,
                                    category,
                                    dateModified)
                                    VALUES
                                   (:name,
                                    :price,
                                    :description,
                                    :qty,
                                    :condition,
                                    :iname,
                                    :filename,
                                    :category,
                                    :dateModified)";
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':name',$_POST['name']);
      $stmt->bindParam(':price',$_POST['price']);
      $stmt->bindParam(':description',$_POST['description']);
      $stmt->bindParam(':condition',$_POST['condition']);
      $stmt->bindParam(':qty',$_POST['qty']);
      $stmt->bindParam(':iname',$_POST['iname']);
      $stmt->bindParam(':filename',$file);
      $stmt->bindParam(':category',$_POST['category']);
      $stmt->bindParam(':dateModified',$dateModified);

      if ($stmt->execute()) 
      {
        $success = TRUE;
        echo "<div class='alert alert-success' role='alert' style='line-height:34px;overflow:auto;'>";
        echo "<h4 style='margin:0;'>Product added successfully! <a href='?subpage=add'>Add another product</a> or <a href='?subpage=inventory'>View Inventory</a></h4>";
        echo "</div>";
      } 
      else 
      {
        echo "<div class='alert alert-danger' role='alert' style='line-height:34px;overflow:auto;'>";
        echo "<h4 style='margin:0;'>There was an error adding the product to the database. Please contact the web administrator.</h4>";
        echo "</div>";        
      }
    }//End try

    catch(PDOException $e) {
      echo "<div class='alert alert-danger' role='alert'>";
      echo "<h4 style='margin:0;'>Something went wrong.</h4>";
      echo "Error: ".$e->getMessage();
      echo "</div>";      
    }
  }//End if
}//End if
?>

</div>
</div>

<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">

  <form action="index.php?page=other&subpage=add" method="POST" enctype="multipart/form-data">

    <h3>Product Information</h3>

    <!-- Name -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Product Name</span>
      <input type="text" name="name" class="form-control" value="<?php echo $name_value ?>" required>
    </div>

    <!-- Price -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Product Price</span>
      <input type="text" name="price" class="form-control" value="<?php echo $price_value ?>" required>
    </div>
    <!-- Invalid price warning label -->
    <?php 
    if (isset($_POST['add']) && (!validPrice($_POST['price']))) 
      echo "<p class='wrong bg-danger text-danger'>Please enter a valid Price (##.##)</p>"; 
    ?>

    <!-- Quantity -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Quantity</span>
      <input type="text" name="qty" class="form-control" value="<?php echo $qty_value ?>" required>
    </div>
    <!-- Invalid quantity warning label -->
    <?php 
    if (isset($_POST['add']) && (!validQty($_POST['qty']))) 
      echo "<p class='wrong bg-danger text-danger'>Please enter a valid Quantity (Whole integer)</p>"; 
    ?>

    <!-- Category -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Category Tags</span>
      <input type="text" 
             name="category" 
             class="form-control" 
             value="<?php echo $category_value ?>" 
             placeholder="Batman, TPB, Single, Used ..."required>
    </div>

    <!-- Condition dropdown menu. Populates the field with the _POST value after update has been clicked. -->
    <select class="form-control input-lg" name="condition" style="margin-top:10px;" required>
      <option value="" disabled selected>Condition - Please select one</option>
      <option <?php if($condition_value=="New") echo "selected='selected'"; ?> value="New">New</option>
      <option <?php if($condition_value=="Excellent") echo "selected='selected'"; ?> value="Excellent">Excellent</option>
      <option <?php if($condition_value=="Good") echo "selected='selected'"; ?> value="Good">Good</option>
      <option <?php if($condition_value=="Fair") echo "selected='selected'"; ?> value="Fair">Fair</option>
      <option <?php if($condition_value=="Poor") echo "selected='selected'"; ?> value="Poor">Poor</option>
    </select>

    <!-- Description label and text area -->
    <label for="description" style="margin-top:5px;">
      <span style="font-family:'Hind',sans-serif;font-size:1.4em;font-weight:200;margin-left:15px;">Description:</span>
    </label>
    <textarea name="description" class="form-control" rows="5" col="100" required><?php echo $description_value ?></textarea>

    <!-- Image upload -->
    <h3>Product Image Upload</h3>

    <!-- Image name -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Image Name</span>
      <input type="text" name="iname" class="form-control" value="<?php echo $iname_value ?>" required>
    </div>

    <!-- Picture upload -->
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Upload Picture</span>
      <input type="file" name="file" id="file" class="form-control" required>
    </div>
    <!-- Picture upload warning labels -->
    <?php 
    if (isset($_POST['add']) && ($tooLarge)) 
    {
      echo "<p class='wrong bg-danger text-danger'>File size cannot exceed 500kb</p>"; 
    }
    if (isset($_POST['add']) && ($dupImg)) 
    {
      echo "<p class='wrong bg-danger text-danger'>Image is already on file. Choose another image.</p>"; 
    }
    ?> 

    <!-- Add button -->
    <button type="submit" name="add" class="btn btn-lg btn-success pull-right" style="margin-top:10px;">Add Product</button>

  </form>
</div>
</div>