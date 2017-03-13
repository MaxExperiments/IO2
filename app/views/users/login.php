<div class="row">
    <div class="small-offset-2 small-8">
        <h1>Se connecter</h1>
        <hr>
        <?= $Form->createForm('post',['class'=>'form']) ?>
            <?= $Form->input('email','Email') ?>
            <?= $Form->input('password','Mot de passe') ?>
            <?= $Form->submit('Se connecter') ?>
        <?= $Form->endForm() ?>
    </div>
</div>
