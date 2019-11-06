<form method="POST" action="/administrate/<?php echo $type ?>/<?php echo $action ?>/<?php echo ($action === "edit") ? "form" : "confirm" ?>">
  <h3><?php echo $title ?></h3>
  <?php echo (isset($msg)) ? $msg : ""; ?>
  <?php echo $CSRF_FIELD; ?>
  <div class="form-group">
    <label for="select"><?php echo $title; ?></label>
    <select class="form-control" name="id" required="required" id="select">
        <option value="false" selected="selected" disabled="disabled"><?php echo $title; ?></option>
        <?php echo $options; ?>
    </select>
  </div>
  <input class="btn btn-danger" type="submit" name="<?php echo $action ?>" value="<?php echo ucfirst($action) ?> <?php echo $type ?>">
</form>