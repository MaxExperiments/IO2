<div class="row">
    <div class="small-offset-2 small-8">
        <h2>Tous les posts</h2>
        <?php if (Session::isAuthenticate()): ?>
            <?= $Html->route('Ajouter un post','PostsController@create',['id'],['class'=>'button primary']) ?>
        <?php endif; ?>
        <hr>
    </div>
    <?php foreach($posts as $post): ?>
        <article class="small-offset-2 small-8" id="post-<?= $post->id ?>">
            <h3><?= $post->name ?></h3>
            <p><?= $post->content ?></p>
            <?= $Html->route('Voir la suite', 'PostsController@show',['id'=>$post->id]) ?>
            <?php if (Session::isAuthenticate()): ?>
                <?php $Html->addScript('/assets/js/app.js') ?>
                <?= $Html->route('Détruire', 'PostsController@destroy',
                                ['id'=>$post->id],
                                ['class'=>'button alert','onclick'=>'return destroyPost('.$post->id.')']); ?>
            <?php endif ?>
        </article>
    <?php endforeach ?>
</div>
