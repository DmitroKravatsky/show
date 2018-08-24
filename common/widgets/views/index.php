<?php

use yii\helpers\Url;

/** @var \yii\web\View $this */
/** @var array $languages[] */
/** @var string $currentLanguage */
/** @var string $url */
?>

<li>
    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        <div style="margin-top: 6px" class="pull-left flag flag-<?= $currentLanguage ?>"></div>
        &nbsp;
        <span class="fa fa-angle-down"></span>
    </a>

    <ul class="dropdown-menu">
        <?php foreach ($languages as $code => $name): ?>
            <li>
                <a href="<?= Url::to([$url, 'language' => $code]) ?>">
                    <div class="pull-left flag flag-<?= $code ?>"></div>&nbsp;<?= $name ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</li>
