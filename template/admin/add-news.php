<form method="POST" action="/administrate/news/add/submit">
  <h3>Create a news post</h3>
  <?php echo (isset($msg)) ? $msg : ""; ?>
  <?php echo $CSRF_FIELD; ?>
  <div class="form-group">
    <label for="title">Title*</label>
    <input type="text" class="form-control" id="title" placeholder="Title" name="title" required="required">
  </div>
  <div class="form-group">
    <label for="content">Content*</label>
    <textarea class="form-control" id="content" placeholder="Content" name="content" required="required"></textarea>
  </div>
  <input type="submit" class="btn btn-success" name="add" value="Add news">
</form>
