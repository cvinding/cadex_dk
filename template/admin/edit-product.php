<form method="POST" action="/administrate/product/edit/confirm">
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
  <img src="">

  <input type="submit" class="btn btn-warning" name="edit" value="Edit product">
  <a class="btn btn-secondary" href="/administrate/product/edit">Cancel</a>
</form>
<script src="/design/js/edit-product.js"></script>