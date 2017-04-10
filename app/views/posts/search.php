<div class="row">
    <div class="small-offset-2 small-8">
        <form action="search" method="get">
            <div class="lead">
                <h1>Rechercher</h1>
            </div>
            <div class="input-group">
                <input type="search" name="q" value="<?= (isset(App::$request->get['q'])) ? App::$request->get['q'] : ''?>" placeholder="Search" class="input-group-field">
                <div class="input-group-button">
                    <button class="button">Rechercher</button>
                </div>
            </div>
        </form>
        <hr>
        <p><?= count($results) ?> résultats</p>
        <?php foreach ($results as $post): ?>
            <article id="post-<?= $post->id ?>">
                <h3><?= $post->title ?></h3>
                <p><?= $post->content ?></p>
                <?= $Html->route('Voir la suite', 'PostsController@show',['id'=>$post->id]) ?>
                <?php if (Session::isAuthenticate()): ?>
                    <?php $Html->addScript('/assets/js/app.js') ?>
                    <?php if (Session::Auth()->id == $post->user_id) echo $Html->route('Détruire', 'PostsController@destroy',
                                    ['id'=>$post->id],
                                    ['class'=>'button alert','onclick'=>'return destroyPost('.$post->id.')']); ?>
                <?php endif ?>
            </article>
        <?php endforeach; ?>
    </div>
</div>
