<?php

use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use yii\helpers\Html;
use common\models\paymentSystem\PaymentSystem;

/** @var \yii\web\View $this */
/** @var PaymentSystem $paymentSystem */
?>

<div class="payment-system-update">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>

        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Payment System'),
            ]) ?>
                <?php $form = ActiveForm::begin() ?>
                    <?= $form->field($paymentSystem, 'name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($paymentSystem, 'currency')->dropDownList(PaymentSystem::currencyLabels()) ?>

                    <?= $form->field($paymentSystem, 'visible')->dropDownList(PaymentSystem::getVisibleStatuses()) ?>

                    <?= $form->field($paymentSystem, 'payment_system_type')
                        ->dropDownList(PaymentSystem::paymentSystemTypeLabel())
                    ?>

                    <?= Html::submitButton($paymentSystem->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                        'class' => 'btn btn-success'
                    ]) ?>
                <?php ActiveForm::end() ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
