
<h1 class="content-header">Edit Product Image</h1>
<div class="row">
<div class="col-lg-8 col-lg-offset-2" style="padding:0;">
<?php
$tooLarge = FALSE;
$dupImg = FALSE;

include('includes/inc_connect.php');
$query = $db->prepare("SELECT * FROM products WHERE id = :id");
$query->bindParam(':id',$_GET['id']);
$query->execute();
$row = $query->fetch();

if (isset($_REQUEST['update'])) { //Check if 'update' button has been clicked

  /*** Set placeholders for all $_POST values ***/
  if (isset($_POST['iname'])) {
    $iname_value = $_POST['iname'];
  }

  /*** Start validation ***/
  function validSize($data) {
    global $errCount;
    global $tooLarge;
    global $dupImg;
    if ($data > 500000) {
      $errCount++;
      $tooLarge = TRUE;
    } 
    elseif (file_exists("upload/".$_FILES['file']['name'])) {
      $errCount++;
      $dupImg = TRUE;
    } 
    else {
      return $data;
    }
  }  

  /* *** IMAGE ADD *** */
  $iname = $_POST['iname'];
  $file = $_FILES['file']['name'];
  $size = $_FILES['file']['size'];


  //If no erros exist, add everything to the database
  if ($errCount > 0) {
  	echo "<div class='alert alert-danger' role='alert'>";
  	echo "<h4 style='margin:0;'>Please fix your ".$errCount." error(s) in the form below.</h4>";
  	echo "</div>";
  } else {

    try {

      include('includes/inc_connect.php');
      if (!isset($_POST['option'])) {
        $sql = "UPDATE products SET iname = :iname   
                                    WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':iname',$_POST['iname']);
        $stmt->bindParam(':id',$_GET['id']);
      } else {
        //Run validations
        validSize($size);

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
          if ($_FILES["file"]["error"] > 0) {
            echo "<div class='alert alert-danger' role='alert'>";
            echo "<h4 style='margin:0;'>Return Code: ".$_FILES['file']['error']."</h4>";
            echo "</div>";
          }  
          elseif ($errCount == 0) {
            unlink("upload/".$row['filename']);
            move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $_FILES["file"]["name"]);
          }
        } //End check for extension if chain

        $sql = "UPDATE products SET iname = :iname,
                                    filename = :filename  
                                    WHERE id = :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':iname',$_POST['iname']);
        $stmt->bindParam(':filename',$file);
        $stmt->bindParam(':id',$_GET['id']);
      }

      if ($stmt->execute()) {
        $success = TRUE;
        echo "<div class='alert alert-success' role='alert' style='line-height:34px;overflow:auto;'>";
        echo "<h4 style='margin:0;'>Image information updated successfully!</h4>";
        echo "</div>";
      } else {
        echo "<div class='alert alert-danger' role='alert' style='line-height:34px;overflow:auto;'>";
        echo "<h4 style='margin:0;'>There was an error adding the product to the database. Please contact the web administrator.</h4>";
        echo "</div>";        
      } //End else
    }//End try
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
  <form action="" method="POST" enctype="multipart/form-data" onsubmit="return checkForm(this);">
    <h3>Product Image Information</h3>
    <div class="checkbox">
      <label><input type="checkbox" name="option" id="option" value="2"><span style="font-family:'Hind',sans-serif;font-size:1.2em;">Upload a different image</span></label>
    </div>
    <script type="text/javascript">
      document.getElementById('option').onchange = function() {
        document.getElementById('picUpload').style.display = this.checked ? 'block' : 'none';
        document.getElementById('file').required = this.checked ? true : false;
      };
    </script>
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Image Name</span>
      <input type="text" name="iname" class="form-control" value="<?php echo (isset($_POST['update']) ? $iname_value : $row['iname']) ?>" required>
    </div>
    <div id="picUpload" style="display:none">
    <div class="input-group input-group-lg">
      <span class="input-group-addon" id="basic-addon3">Upload Picture</span>
      <input type="file" name="file" id="file" class="form-control">
    </div>
    </div>
    <?php 
    if (isset($_POST['update']) && ($tooLarge)) {
      echo "<p class='wrong bg-danger text-danger'>File size cannot exceed 500kb</p>"; 
    }
    if (isset($_POST['update']) && ($dupImg)) {
      echo "<p class='wrong bg-danger text-danger'>Image is already on file. Choose another image.</p>"; 
    }
    ?> 
    <button type="submit" name="update" class="btn btn-lg btn-success pull-right" style="margin-top:10px;">Update</button>
  </form>
</div>
</div>