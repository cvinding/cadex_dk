<form method="POST" action="/administrate/product/add/submit" enctype="multipart/form-data">
  <h3>Create a product</h3>
  <?php echo (isset($msg)) ? $msg : ""; ?>
  <?php echo $CSRF_FIELD; ?>
  <div class="form-group">
    <label for="name">Name*</label>
    <input type="text" class="form-control" id="name" placeholder="Name" name="name" required="required">
  </div>
  <div class="form-group">
    <label for="content">Description*</label>
    <textarea class="form-control" id="description" placeholder="Description" name="description" required="required"></textarea>
  </div>
  <div class="form-group">
    <label for="price">Price*</label>
    <input type="text" class="form-control" id="price" placeholder="Price" name="price" required="required">
  </div>
  <div class="form-group">
    <label for="thumbnail">Upload thumbnail*</label>
    <input type="file" class="form-control" id="thumbnail" name="thumbnail" required="required">
  </div>
  <div class="form-group">
    <label for="images">Upload images</label>
    <input type="file" class="form-control" id="images" name="images[]" multiple="multiple">
  </div>
  <input type="submit" class="btn btn-success" name="add" value="Add product">
</form>
