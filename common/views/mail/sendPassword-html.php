<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\user\UserEntity */
?>
<div class="recovery-password">
    <p>Уважаемый клиент <?= Html::encode($email) ?>,</p>

    <p>Ваш пароль созданный при логировании через внешний сервис
        <?= $password ?> </p>
</div>
