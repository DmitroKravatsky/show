<?php
use rest\modules\api\v1\authorization\models\RestUserEntity;
use yii\helpers\Html;

    /* @var $this yii\web\View */
    /* @var $user common\models\user\UserEntity */
?>
<div class="recovery-password">
    <?php $user = RestUserEntity::findOne(['email' => Yii::$app->getRequest()->getBodyParams()['email']]); ?>

    <p>Уважаемый клиент <?= Html::encode($user->email) ?>,</p>

    <p>Чтобы закончить процедуру восстановления пароля Вам необходимо ввести данный код
        <?= $recoveryCode ?>
        в форму "Восстановления пароля":<br>Данный код будет действителен только один час.</p>
</div>

