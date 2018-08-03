<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var ActiveForm $form */
/** @var \common\models\userProfile\UserProfileEntity $profile */
?>

<?php $form = ActiveForm::begin([
    'action' => Url::to(['profile/update']),
    'method' => 'POST',
]) ?>
    <?= $form->field($profile, 'name')->textInput(['maxlength' => true,]) ?>

    <?= $form->field($profile, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end() ?>

