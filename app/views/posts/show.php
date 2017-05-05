<div class="row">
    <div class="small-offset-2 small-8">
        <h3><?= $post->title ?></h3>
        <div class="lead">Un post de <a href="/users/<?= $post->user_id ?>"><?= $post->pseudo ?></a> <img src="<?= ($post->photo!=null) ? $post->photo : '/assets/imgs/default-user-img.png' ?>" alt=""></div>
        <hr>
        <?= $post->content ?>
    </div>
    <?php if (Session::isAuthenticate()): ?>
        <hr>
        <div class="small-offset-2 small-8">
            <?php $Html->addScript('/assets/js/app.js')?>
            <?= $Form->createForm('put', ['class'=>'form','id'=>'reply-form','action'=>'/replies']) ?>
                <h5>Poster une réponse</h5>
                <input type="hidden" name="post_id" id="post_id" value="<?= $post->id ?>">
                <?= $Form->input('content','',['id'=>'reply-content'])?>
                <?= $Form->submit('Poster') ?>
            <?= $Form->endForm() ?>
        </div>
    <?php endif; ?>
    <hr>
    <div class="small-offset-2 small-8 replies-content">
        <div class="reply-template hide" style="height:0;">
            <article data-id="{{ id }}" data-ressource-type="replies">
                <div class="lead">
                    0 étoiles pour un message de {{ pseudo }} le {{ created_at }}
                    <?= $Html->route('Star', 'RepliesController@star',
                                ['id'=>'{{ id }}'],
                                ['class'=>'button primary star-button']) ?>
                    <?= $Html->route('Supprimer', 'RepliesController@star',
                                ['id'=>'{{ id }}'],
                                ['class'=>'button alert del-button']) ?>
                </div>
                {{ content }}
                <hr>
            </article>
        </div>
        <?php foreach ($replies as $reply): ?>
            <article data-id="<?= $reply->id ?>" data-ressource-type="replies">
                <div class="lead">
                    <?= $reply->stars ?> étoiles pour un message de <?= $reply->pseudo ?> le <?= $reply->created_at ?>
                    <?php if (Session::isAuthenticate()): ?>
                        <?= $Html->route('Star', 'RepliesController@star',
                                ['id'=>$reply->id],
                                ['class'=>'button primary star-button']) ?>
                        <?php if(Session::Auth()->id == $reply->user_id): ?>
                            <?= $Html->route('Supprimer', 'RepliesController@destroy',
                                    ['id'=>$reply->id],
                                    ['class'=>'button alert del-button']) ?>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?= $reply->content ?>
                <hr>
            </article>
        <?php endforeach; ?>
    </div>
</div>
