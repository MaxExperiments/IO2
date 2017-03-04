<?php if (array_key_exists($attributes['name'], App::$request->session->getMessages())): ?>
    <div data-alert class="alert-box success radius">
        <?= App::$request->session->getMessage($attributes['name']) ?>
    </div>
<?php endif ?>

<label for="<?= $attributes['name'] ?>">
<?= $label ?>
<<?= ($attributes['type']=='textarea') ? 'textarea' : 'input' ?> <?php foreach ($attributes as $k => $v): ?>
<?= $k ?>="<?php if (is_array($v)) {
    foreach ($v as $l) {
        echo $l . ' ';
    }
} else echo $v; ?>"
<?php endforeach ?>>
<?= ($attributes['type']=='textarea') ? '</textarea>' : '' ?>
</label>
