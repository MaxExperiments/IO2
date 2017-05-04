<form method="<?= ($method==='get') ? 'get' : 'post' ?>"
<?= Helper::insertAsAttr($attributes) ?>>
    <?php if (App::$session->isMessageWithName('formValidation')): ?>
        <div class="callout alert" data-closable>
            <p><?= App::$session->getMessage('formValidation') ?></p>
            <button class="close-button" aria-label="Dismiss alert" type="button" data-close>
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
    <input type="hidden" name="__method" value="<?= $method ?>">
   