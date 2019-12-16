<link href="/design/css/edit-product.css" rel="stylesheet" type="text/css">
<form method="POST" action="/administrate/product/edit/submit" enctype="multipart/form-data">
  <h3>Edit a product</h3>
  <?php echo (isset($msg)) ? $msg : ""; ?>
  <?php echo $CSRF_FIELD; ?>
  <input type="hidden" name="id" value="<?php echo $_POST["id"] ?>">
  <div class="form-group">
    <label for="name">Name*</label>
    <input type="text" class="form-control" id="name" placeholder="Name" name="name" required="required" value="<?php echo $data["name"] ?>">
  </div>
  <div class="form-group">
    <label for="description">Description*</label>
    <textarea class="form-control" id="description" placeholder="Description" name="description" required="required"><?php echo $data["description"] ?></textarea>
  </div>
  <div class="form-group">
    <label for="price">Price*</label>
    <input type="text" class="form-control" id="price" placeholder="Price" name="price" required="required" value="<?php echo $data["price"] ?>">
  </div>
  <div class="form-group" id="thumbnail-output">
    <label for="thumbnail">Thumbnail*</label><br>
    <?php 
    echo '<div class="image-with-cross" id="thumbnail-image">';
      echo '<input type="hidden" value="'.$data["images"][0]["id"].'" name="imagesToDelete[]" disabled="disabled">'; 
      echo '<img src="data:'.$data["images"][0]["type"].';base64,'.$data["images"][0]["image"].'">';
    echo '</div>';
    ?>
  </div>
  <div class="form-group" style="display:none" id="hidden-thumbnail-upload">
    <input type="file" name="imageUpload[]" class="instant-image-upload">
  </div>
  <div class="form-group" id="image-output">
    <label for="other-images">Images</label><br>
    <?php
      for($i = 1; $i < sizeof($data["images"]); $i++) {     
        echo '<div class="image-with-cross">';
          echo '<input type="hidden" value="'. $data["images"][$i]["id"] .'" name="imagesToDelete[]" disabled="disabled">';
          echo '<img src="data:'. $data["images"][$i]["type"] .';base64,' . $data["images"][$i]["image"] . '">';
        echo '</div>';
      }
    ?>
  </div>
  <div class="form-group">
    <input type="file" name="imageUpload[]" class="instant-image-upload">
  </div>
  <input type="button" class="btn btn-warning" name="edit" value="Edit product" id="submit-form">
  <a class="btn btn-secondary" href="/administrate/product/edit">Cancel</a>
</form>
<script src="/design/js/edit-product.js"></script>