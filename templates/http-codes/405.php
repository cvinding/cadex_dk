<?php http_response_code(405) ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>405: Request method not allowed</title>
        <link href="/public_html/design/css/http-codes-style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="http-code-error-message">
            <h1>Oops!</h1>
            <h3>405: Request method not allowed</h3>
            <p>Only GET and POST requests are allowed on this website.</p>
            <a href="<?php echo $defaultPage; ?>">Back to home page</a>
        </div>
    </body>
</html>