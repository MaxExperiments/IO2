<h1>Tous les posts</h1>
<?php foreach($posts as $post): ?>
<article>
    <h3><?= $post->name ?></h3>
    <p><?= $post->content ?></p>
</article>
<?php endforeach ?>