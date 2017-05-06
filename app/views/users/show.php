<div class="row">
    <div class="small-offset-2 small-8">
        <h2><?= $user->pseudo ?></h2>
        <img src="<?= ($user->photo) ? $user->photo : '/assets/imgs/default-user-img.gif' ?>" alt="" class="user-img">
        <div class="lead">
            <?php if ($user->updated_at == null): ?>
                <p>Compte crée le <?= date('j/m/Y', strtotime($user->created_at)) ?></p>
            <?php else: ?>
                <p>Dernière modification le <?= date('j/m/Y', strtotime($user->updated_at)) ?></p>
            <?php endif ?>
            <?php if (Session::isAuthenticate() && Session::Auth()->id == $user->id): ?>
                <p><a href="/users/">Modifier mon compte</a></p>
            <?php endif ?>
        </div>
        <hr>
    </div>
</div>

<?= App::$response->requireView('posts.index', [
    'posts'=>$posts,
    'headline'=> (Session::isAuthenticate() && Session::Auth()->id == $user->id) ? 'Tous mes posts' : 'Tous les posts de ' . $user->pseudo
]) ?>