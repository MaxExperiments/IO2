<div class="row">
    <div class="small-offset-2 small-8">
        <h3><?= $post->title ?></h3>
        <hr>
        <?= $post->content ?>
    </div>
    <?php if (Session::isAuthenticate()): ?>
        <hr>
        <div class="small-offset-2 small-8">
            <?= $Form->createForm('put', ['class'=>'form','id'=>'reply-form','action'=>'/reply']) ?>
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
            <article>
                <?= $reply->content ?>
            </article>
            <hr>
        <?php endforeach; ?>
    </div>
</div>
