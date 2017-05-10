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
        <?= App::$response->requireView('posts.index', [
            'posts'=>$results,
            'headline'=> 'Résultats'
        ]) ?>
    </div>
</div>
