<?php if (isset($messages)): ?>
    <?php foreach ($messages as $message): ?>
        <div class="alert-box">
            <?= $message ?>
        </div>
    <?php endforeach ?>
<?php endif ?>

<label for="<?= $attributes['name'] ?>">
<?= $label ?>
<?php if ($attributes['type']=='textarea'): ?>

    <textarea <?php foreach ($attributes as $k => $v): ?>
    <?= $k ?>="<?php if (is_array($v)) {
        foreach ($v as $l) echo $l . ' ';
    } else echo $v; ?>"
    <?php endforeach ?>><?= (isset($attributes['value'])) ? $attributes['value'] : ''?></textarea>    

<?php else: ?>

    <input <?php foreach ($attributes as $k => $v): ?>
    <?= $k ?>="<?php if (is_array($v)) {
        foreach ($v as $l) echo $l . ' ';
    } else echo $v; ?>"
    <?php endforeach ?>>

<?php endif ?>
</label>
