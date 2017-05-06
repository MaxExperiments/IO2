<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Un titre</title>
        <link rel="stylesheet" href="/assets/css/foundation.min.css">
    </head>
    <body>
        <div>
            <div>
                <ul>
                    <li>Titre</li>
                    <li><a href="/posts">Posts</a></li>
                </ul>
            </div>
        </div>

        <?= App::$response->render() ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.3.1/js/foundation.min.js"></script>
    </body>
</html>
