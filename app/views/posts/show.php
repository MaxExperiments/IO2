<section class="post">
    <h2><?= $post->title ?></h2>
    <div class="lead">Un post de <strong><a href="/users/<?= $post->pseudo ?>"><?= $post->pseudo ?></a></strong> 
    <?php if ($post->photo): ?>
        <img src="<?= $post->photo ?>" alt="" class="user-img"></div>
    <?php elseif ($post->user_photo): ?>
        <img src="<?= $post->user_photo ?>" alt="" class="user-img"></div>
    <?php endif ?>
    <?php if (Session::isAuthenticate() && Session::Auth()->id == $post->user_id): ?>
        <p><?= $Html->route('Modifier le post', 'PostsController@edit',['id'=>$post->id]) ?></p>
    <?php endif ?>
    <hr>
    <?= $Html->bind($post->content) ?>
    <?php if (Session::isAuthenticate()): ?>
        <hr>
        <div class="small-offset-2 small-8">
            <?= $Form->createForm('put', ['class'=>'form','id'=>'reply-form','action'=>'/replies']) ?>
                <h5>Poster une réponse</h5>
                <input type="hidden" name="post_id" id="post_id" value="<?= $post->id ?>">
                <?= $Form->input('content','',['id'=>'reply-content','required'=>'true'])?>
                <?= $Form->submit('Poster') ?>
            <?= $Form->endForm() ?>
        </div>
    <?php endif; ?>
    <hr>
    <div class="small-offset-2 small-8 replies-content">
        <div class="reply-template hide" style="height:0;">
            <article data-id="{{ id }}" data-ressource-type="replies">
                <div class="lead">
                    <strong><a href="/users/{{ user_id }}">{{ pseudo }}</a></strong> <span class="star">0</span>&#9734; le  {{ created_at }}
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
                    <strong><a href="/users/<?= $reply->pseudo ?>"><?= $reply->pseudo ?></a></strong> <span class="star"><?= $reply->stars ?></span>&#9734; le <?= date('j/m/Y', strtotime($reply->created_at)) ?>
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
                <?= $Html->bind($reply->content) ?>
                <hr>
            </article>
        <?php endforeach; ?>
    </div>
</section>
