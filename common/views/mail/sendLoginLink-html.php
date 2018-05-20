<?php
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $user common\models\user\UserEntity */
?>
<div class="recovery-password">
    <p>Уважаемый клиент <?= Html::encode($email) ?>,</p>

    <p>Чтобы закончить процедуру регистрации Вам необходимо перейти по ссылке
        <?= $loginLink ?>
        :<br>Ваш текущий пароль <?= $password ?>. После перехода по ссылке у вас будет возможность сменить его </p>
        :<br>Ваш текущий логин <?= $phone_number ?>. Ссылка доступна для использования один раз </p>
</div>

