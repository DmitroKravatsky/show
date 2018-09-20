<?php

use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use yii\helpers\Html;
use common\models\paymentSystem\PaymentSystem;
use common\models\reserve\ReserveEntity as Reserve;

/** @var \yii\web\View $this */
/** @var Reserve $reserve */
?>

<div class="reserve-update">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>

        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Reserve'),
            ]) ?>
                <?php $form = ActiveForm::begin() ?>
                    <?php if ($reserve->isNewRecord): ?>
                        <?= $form->field($reserve, 'payment_system_id')->dropDownList(Reserve::getPaymentSystems(), ['prompt' => '']) ?>
                    <?php else: ?>
                        <?= $form->field($reserve, 'payment_system_id')->dropDownList([$reserve->paymentSystem->name], ['disabled' => true,]) ?>
                    <?php endif; ?>

                    <?= $form->field($reserve, 'sum')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($reserve, 'visible')->dropDownList(PaymentSystem::getVisibleStatuses()) ?>

                    <?= Html::submitButton($reserve->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                        'class' => 'btn btn-success'
                    ]) ?>
                <?php ActiveForm::end() ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
