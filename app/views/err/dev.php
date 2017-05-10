<div class="row">
    <div class="small-offset-2 small-4 columns">
        <img src="https://placebear.com/<?= (rand(0,1)) ? 'g' : '' ?>/<?= rand(200, 800) + 1 ?>/<?= rand(300, 500) + 1 ?>" alt="">
    </div>
    <div class="small-6 columns">
        <h1>Ohh non ! une erreur est survenue</h1>
    </div>
</div>
<div class="row" style="margin-top: 50px;">
    <div class="small-offset-2 small-8">
        <strong>Erreur <?= $code . ': ' . $message . ' dans ' . $file . ' à la ligne ' . $line ?></strong>
    </div>
</div>
<div class="row">
    <table class="small-offset-2 small-8">
        <caption>Stack Trace</caption>
        <?php foreach (array_slice(explode('#',$trace),1) as $key => $value): ?>
            <tr>
                <td><?= $value ?></td>
            </tr>
        <?php endforeach ?>
    </table>
</div>