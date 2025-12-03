<!DOCTYPE html>
<html>
    <head>
        <style>
            BODY {
                font-family: sans-serif;
                margin: 0 30px;
                font-size: 14px;
            }

            H1 {
                font-weight: 100;
            }
        </style>
    </head>
    <body>
        <h1><?= $exception->getMessage(); ?></h1>
        <p><?= $exception->getDescription(); ?></p>
    </body>
</html>