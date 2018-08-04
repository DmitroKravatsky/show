<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var ActiveForm $form */
/** @var \backend\models\BackendUser $user */
?>

<?php $form = ActiveForm::begin([
    'action' => Url::to(['profile/update-password']),
    'method' => 'POST',
]) ?>
    <?= $form->field($user, 'currentPassword', ['enableAjaxValidation' => true,])->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'repeatPassword')->passwordInput(['maxlength' => true]) ?>

    <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>
