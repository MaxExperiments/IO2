<div class="row">
    <div class="small-offset-2 small-8">
    <?= $Form->createForm($method, ['class'=>'form']) ?>
        <?= $Form->input('title', 'Titre',['id'=>['unID']]) ?>
        <?= $Form->input('content', 'Contenu') ?>
        <?= $Form->submit('Valider') ?>
    <?= $Form->endForm() ?>
    </div>
</div>
