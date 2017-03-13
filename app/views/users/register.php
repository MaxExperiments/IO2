<div class="row">
    <div class="small-offset-2 small-8">
        <?= $Form->createForm('post',['class'=>'form']) ?>
            <?= $Form->input('email','Email',['placeholder'=>'Ex: prenom@nom.fr']) ?>
            <?= $Form->input('password', 'Mot de passe', ['placeholder'=>'Ex:123456']) ?>
            <?= $Form->submit('S\'inscrire') ?>
        <?= $Form->endForm() ?>
    </div>
</div>
