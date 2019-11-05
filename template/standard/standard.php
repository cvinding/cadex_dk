<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $title; ?></title>
        <?php echo $css; ?>
    </head>
    <body>
        <?php echo $navbar; ?>
        
        <?php echo (isset($imageHeader)) ? $imageHeader : ""; ?>

        <div class="main">
            <div class="container">
                <?php echo $content; ?>
            </div>
        </div>
        <?php echo $footer; ?>
    </body>
</html>