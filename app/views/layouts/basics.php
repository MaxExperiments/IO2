<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Un titre</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.3.1/css/foundation.min.css">
    </head>
    <body>
        <div class="top-bar">
            <div class="top-bar-left">
                <ul class="dropdown menu">
                    <li class="menu-text">Titre</li>
                    <li><a href="/posts">Posts</a></li>
                </ul>
            </div>
        </div>
        <?= App::$response->render() ?>
    </body>
</html>