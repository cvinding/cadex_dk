<?php http_response_code(501) ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>501: Internal Server Error</title>
        <link href="/public_html/design/css/http-codes-style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="http-code-error-message">
            <h1>Oops!</h1>
            <h3>501: Internal Server Error</h3>
            <p>This View has been configured incorrectly.</p>
            <p>Please contact administrator or webmaster to report this incident.</p>
            <a href="<?php echo $defaultPage; ?>">Back to home page</a>
        </div>
    </body>
</html>