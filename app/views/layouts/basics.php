<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Un titre</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.3.1/css/foundation.min.css">
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div class="top-bar">
            <div class="top-bar-left">
                <ul class="dropdown menu">
                    <li class="menu-text">Titre</li>
                    <li><a href="/posts">Posts</a></li>
                </ul>
            </div>
            <div class="top-bar-right">
                <ul class="dropdow menu">
                    <?php if (!Session::isAuthenticate()): ?>
                        <li><a class="primary button" href="/login">Connection</a></li>
                        <li><a class="primary button" href="/register">Inscription</a></li>
                    <?php else: ?>
                        <li>Bonjour <?= Session::Auth()->pseudo;?></li>
                        <li><a class="primary button" href="/logout">Se déconnecter</a></li>
                    <?php endif ?>
                </ul>
            </div>
        </div>

        <?= App::$response->render() ?>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.3.1/js/foundation.min.js"></script>
        <?php foreach (Html::$scripts as $url): ?>
            <script src="<?= $url ?>"></script>
        <?php endforeach; ?>
    </body>
</html>
