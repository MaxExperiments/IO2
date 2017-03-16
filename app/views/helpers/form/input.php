<?php if (isset($messages)): ?>
    <?php foreach ($messages as $message): ?>
        <div class="callout alert" data-closable>
            <p><?= $message ?></p>
            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
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
