<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>diderot.club</title>
        <link rel="stylesheet" href="/assets/css/main.css">
    </head>
    <body>

      <?php if (Session::isAuthenticate()): ?>
        <input type="hidden" id="__token" value="<?= Session::token() ?>">
      <?php endif ?>

      <header id="top">
        <h1><a class="titre" href="/">Diderot Café ici tout est bon !…</a></h1>
        <nav>
          <ul id="top-menu">
            <?php if (!Session::isAuthenticate()): ?>
              <li><a class="dropdown" href="/posts">anonyme</a>
                <ul>
                  <li><a href="/login">Se connecter →</a></li>
                  <li><a href="/register">S'inscrire</a></li>
                </ul>
              </li>
              <li><a href="/posts">Voir tous les posts »</a></li>
            <?php else: ?>
                <li><a class="dropdown" href="/posts"><?= Session::Auth()->pseudo;?></a>
                  <ul>
                    <li><a href="/users/<?= Session::Auth()->pseudo ?>">Ma Page</a></li>
                    <li><a href="/logout">× Se déconnecter ×</a></li>
                  </ul>
                </li>
                <li><a href="/posts/create">Créer une nouvelle conversation »</a></li>
            <?php endif ?>

          </ul>
        </nav>
      </header>
        
        
        <?php if (App::$session->isMessageWithName('success')): ?>
            <div class="callout success">
                <?= App::$session->getMessage('success') ?>
                <button class="close-button" type="button">&times;</button>
            </div>
        <?php endif ?>

        <?php if (App::$request->func != 'search'): ?>
            <div class="callout">
                <form action="/search" method="get">
                    <div class="input-group">
                        <input type="search" name="q" placeholder="Search" class="input-group-field">
                        <div class="input-group-button">
                            <button class="button">Rechercher</button>
                        </div>
                    </div>
                </form>
          </div>
        <?php endif ?>

        <main id="content">
          <?= App::$response->render() ?>
        </main>

        <script type="text/javascript" src="/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="/assets/js/app.js"></script>
        <?php foreach (Html::$scripts as $url): ?>
            <script src="<?= $url ?>"></script>
        <?php endforeach; ?>
    </body>
</html>
