<div class="row">
    <div class="small-offset-2 small-8">
    <?= $Form->createForm('post', ['class'=>'form']) ?>
        <?= $Form->input('pseudo', 'Pseudo') ?>
        <?= $Form->input('email', 'Email') ?>
        <?= $Form->input('password','Nouveau mot de passe',['value'=>''])?>
        <?= $Form->submit('Valider') ?>
    <?= $Form->endForm() ?>
    </div>
</div>
