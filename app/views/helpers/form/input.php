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

    <textarea <?= Helper::insertAsAttr($attributes) ?>><?= (isset($attributes['value'])) ? $attributes['value'] : ''?></textarea>    

<?php else: ?>

    <input <?= Helper::insertAsAttr($attributes) ?>>

<?php endif ?>
</label>
