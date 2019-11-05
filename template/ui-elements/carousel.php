<div id="<?php echo $id; ?>" class="carousel slide" data-ride="carousel">
  <ol class="carousel-indicators">
    <?php echo $indicators; ?>
  </ol>
  <div class="carousel-inner" role="listbox">
    <?php echo $items; ?>
  </div>
  <a class="carousel-control-prev" href="#<?php echo $id; ?>" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Forrige</span>
  </a>
  <a class="carousel-control-next" href="#<?php echo $id; ?>" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Næste</span>
  </a>
</div>