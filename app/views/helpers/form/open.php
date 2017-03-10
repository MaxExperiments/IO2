<form method="<?= ($method==='get') ? 'get' : 'post' ?>" 
<?= Helper::insertAsAttr($attributes) ?>>
    <input type="hidden" name="__method" value="<?= $method ?>">