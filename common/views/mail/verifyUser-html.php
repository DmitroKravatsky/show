<?php

use yii\helpers\Html;

/* @var yii\web\View $this */
/* @var \backend\models\BackendUser $user */

$link = Yii::$app->urlManager->createAbsoluteUrl(['profile/verify', 'token' => $user->verification_token]);
?>

<div class="verify-user">
    <p><?= Yii::t('app', 'Hello') ?> <?= Html::encode($user->profile->getUserFullName()) ?>,</p>

    <p><?= Yii::t('app', 'Follow the link below to confirm your E-mail') ?>:</p>

    <p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
