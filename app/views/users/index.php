<div class="row">
    <div class="small-offset-2 small-8">
    <?= $Form->createForm('post', ['class'=>'form','enctype'=>'multipart/form-data']) ?>
        <?= $Form->input('pseudo', 'Pseudo') ?>
        <?= $Form->input('email', 'Email') ?>
        <?= $Form->input('password','Nouveau mot de passe',['value'=>''])?>
        <?= $Form->input('photo', 'Changer ma photo de profil') ?>
        <?= $Form->submit('Valider') ?>
    <?= $Form->endForm() ?>
    </div>
</div>
