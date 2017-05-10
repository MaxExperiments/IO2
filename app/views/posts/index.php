<main id="post-list">
    <div>
        <h2><?= $headline ?></h2>
        <?php if (Session::isAuthenticate()): ?>
            <?= $Html->route('Ajouter un post','PostsController@create',['id'],['class'=>'button primary']) ?>
        <?php endif; ?>
        <p><?= $total ?> posts</p>
        <hr>
    </div>
    <?php foreach($posts as $post): ?>
        <article class="post" data-id="<?= $post->id ?>" data-ressource-type="posts">
            <div class="post-photo">
                <img src="<?= ($post->photo!==null) ? $post->photo : '/assets/imgs/default-post-img.gif' ?>" alt="">
            </div>
            <div class="post-content">
                <div class="lead">
                    <?= $Html->route('<h2>'. $post->title .'</h2>', 'PostsController@show',['id'=>$post->id]) ?>
                    <p>Un post de <strong><a href="/users/<?= $post->user_id ?>"><?= $post->pseudo ?></a></strong> le <?= date('j/m/Y', strtotime(($post->updated_at) ? $post->updated_at : $post->created_at)) ?></p>
                </div>
                <p><?= $Html->shortCut($post->content) ?>...</p>
                <div class="link">
                    <?= $Html->route('Voir la suite', 'PostsController@show',['id'=>$post->id]) ?>
                    <?php if (Session::isAuthenticate()): ?>
                        <?php $Html->addScript('/assets/js/app.js') ?>
                        <?php if (Session::Auth()->id == $post->user_id) echo $Html->route('Détruire', 'PostsController@destroy',
                                        ['id'=>$post->id],
                                        ['class'=>'button alert del-button']); ?>
                    <?php endif ?>
                </div>
            </div>
        </article>
    <?php endforeach ?>
    <p><?= $Html->previousPage() ?> <?= $Html->nextPage($total) ?></p>
</main>
