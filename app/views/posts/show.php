<div class="row">
    <div class="small-offset-2 small-8">
        <h3><?= $post->title ?></h3>
        <hr>
        <?= $post->content ?>
    </div>
    <?php if (Session::isAuthenticate()): ?>
        <hr>
        <div class="small-offset-2 small-8">
            <?php $Html->addScript('/assets/js/app.js')?>
            <?= $Form->createForm('put', ['class'=>'form','id'=>'reply-form','action'=>'/replies']) ?>
                <h5>Poster une réponse</h5>
                <input type="hidden" name="post_id" value="<?= $post->id ?>">
                <?= $Form->input('content')?>
                <?= $Form->submit('Poster') ?>
            <?= $Form->endForm() ?>
        </div>
    <?php endif; ?>
    <hr>
    <div class="small-offset-2 small-8">
        <?php foreach ($replies as $reply): ?>
            <article class="js-transition" id="reply-<?= $reply->id ?>">
                <div class="lead">
                    Un message de <?= $reply->pseudo ?> le <?= $reply->created_at ?>
                    <?php if (Session::isAuthenticate() && Session::Auth()->id == $reply->user_id): ?>
                        <?= $Html->route('Supprimer', 'RepliesController@destroy',
                                ['id'=>$reply->id],
                                ['class'=>'button alert','onclick'=>'return destroyReply('.$reply->id.')']) ?>
                    <?php endif; ?>
                </div>
                <?= $reply->content ?>
                <hr>
            </article>
        <?php endforeach; ?>
    </div>
</div>
