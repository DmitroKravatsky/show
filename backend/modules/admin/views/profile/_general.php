<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var ActiveForm $form */
/** @var \common\models\userProfile\UserProfileEntity $profile */
/** @var \backend\models\BackendUser $user */
?>

<?php $form = ActiveForm::begin([
    'action' => Url::to(['profile/update']),
    'method' => 'POST',
]) ?>
    <?= $form->field($profile, 'name')->textInput(['maxlength' => true,]) ?>

    <?= $form->field($profile, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($user, 'email', ['enableAjaxValidation' => true,])->textInput(['maxlength' => true,])->label('E-mail') ?>

    <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>

