<div class="row">
    <div class="small-offset-2 small-8">
        <h3><?= $user->pseudo ?></h3>
        <img src="<?= $user->photo ?>" alt="">
        <div class="lead">Compte crée le <?= $user->created_at ?></div>
        <hr>
    </div>
</div>

<?= App::$response->requireView('posts.index', ['posts'=>$posts]) ?>