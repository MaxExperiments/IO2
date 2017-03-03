<form method="<?= ($method==='get') ? 'get' : 'post' ?>" 
<?php foreach ($attributes as $k => $v): ?>
    <?= $k ?>="<?= $v ?>"
<?php endforeach ?>
>
    <input type="hidden" name="__method" value="<?= $method ?>">