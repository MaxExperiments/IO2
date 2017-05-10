<div>
    <div>
    <h2>Créer un post</h2>
    <hr>
    <?= $Form->createForm($method, ['class'=>'form','enctype'=>'multipart/form-data']) ?>
        <?= $Form->input('title', 'Titre') ?>
        <?= $Form->input('content', 'Contenu') ?>
        <?= $Form->input('photo', 'Lier une photo au post'); ?>
        <?= $Form->submit('Valider') ?>
    <?= $Form->endForm() ?>
    </div>
</div>
