<form method="POST" action="/administrate/news/edit/confirm">
  <h3>Edit a news post</h3>
  <?php echo (isset($msg)) ? $msg : ""; ?>
  <?php echo $CSRF_FIELD; ?>
  <input type="hidden" name="id" value="<?php echo $_POST["id"] ?>">
  <div class="form-group">
    <label for="title">Title*</label>
    <input type="text" class="form-control" id="title" placeholder="Title" name="title" required="required" value="<?php echo $data["title"] ?>">
  </div>
  <div class="form-group">
    <label for="content">Content*</label>
    <textarea class="form-control" id="content" placeholder="Content" name="content" required="required"><?php echo $data["content"] ?></textarea>
  </div>
  <input type="submit" class="btn btn-warning" name="edit" value="Edit news">
  <a class="btn btn-secondary" href="/administrate/news/edit">Cancel</a>
</form>