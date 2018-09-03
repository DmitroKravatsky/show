<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user \common\models\user\User */
/* @var string $loginLink */
/* @var string $email */
?>
<div class="invite-link">
    <p>Уважаемый оператор <?= Html::encode($email) ?>,</p>

    <p>Чтобы закончить процедуру регистрации Вам необходимо перейти по ссылке <?= Html::a(Html::encode($loginLink), $loginLink) ?></p>

    <p>Ваш текущий логин: <?= $email ?>.</p>

    <p>Ссылка доступна для использования один раз.</p>

    <p>После перехода по ссылке у Вас будет возможность сменить пароль.</p>
</div>
