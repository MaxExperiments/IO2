<div>
    <div>
    <h2>Créer un post</h2>
    <hr>
    <?= $Form->createForm($method, ['class'=>'form']) ?>
        <?= $Form->input('title', 'Titre',['id'=>['unID']]) ?>
        <?= $Form->input('content', 'Contenu') ?>
        <?= $Form->submit('Valider') ?>
    <?= $Form->endForm() ?>
    </div>
</div>
