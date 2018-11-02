<?php
use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $user common\models\user\UserEntity */
?>
<div class="recovery-password">
    <p><?= Yii::t('app', 'Dear customer') . ' ' . Html::encode($email); ?>,</p>

    <p> <?= Yii::t('app', 'your verification code is:') . ': ' . $verificationCode ?>
</div>

