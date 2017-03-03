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
