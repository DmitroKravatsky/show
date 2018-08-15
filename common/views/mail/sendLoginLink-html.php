<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\user\User */
/* @var string $loginLink */
/* @var string $email */
/* @var string $password */
/* @var string $phone_number */
?>
<div class="invite-link">
    <p>Уважаемый клиент <?= Html::encode($email) ?>,</p>

    <p>Чтобы закончить процедуру регистрации Вам необходимо перейти по ссылке <?= Html::a(Html::encode($loginLink), $loginLink) ?></p>

    <p>Ваш текущий пароль: <?= $password ?>. После перехода по ссылке у вас будет возможность сменить его </p>

    <p>Ваш текущий логин: <?= $phone_number ?>. Ссылка доступна для использования один раз </p>
</div>
