<form method="<?= ($method==='get') ? 'get' : 'post' ?>"
<?= Helper::insertAsAttr($attributes) ?>>
    <?php if (App::$request->session->isMessageWithName('formValidation')): ?>
        <div class="alert-box">
            <?= App::$request->session->getMessage('formValidation') ?>
        </div>
    <?php endif; ?>
    <input type="hidden" name="__method" value="<?= $method ?>">
