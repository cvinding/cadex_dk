<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <?php echo $css; ?>
    </head>
    <body>
        <?php echo $navbar; ?>
        <img src="/design/assets/cadex_auto_hq.svg" width="100%">
        <div class='main container'>
            <?php echo $content; ?>
        </div>
        <?php //echo $footer; ?>
    </body>
</html>