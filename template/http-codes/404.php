<?php http_response_code(404); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>404: Page not found</title>
        <link href="/design/css/http-codes-style.css" rel="stylesheet" type="text/css">
    </head>
    <body>
        <div class="http-code-error-message">
            <h1>Oops!</h1>
            <h3>404: Page not found</h3>
            <p>The requested page was not found.</p>
            <a href="<?php echo $defaultPage; ?>">Back to home page</a>
        </div>
    </body>
</html>