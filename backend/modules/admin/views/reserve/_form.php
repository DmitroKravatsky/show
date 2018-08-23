<?php

use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var \common\models\reserve\ReserveEntity $reserve */
?>

<div class="reserve-update">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>

        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Reserve'),
                'collapsable' => true,
                'removable' => true,
            ]) ?>
                <?php $form = ActiveForm::begin() ?>
                    <?= $form->field($reserve, 'sum')->textInput(['maxlength' => true]) ?>

                    <?= Html::submitButton($reserve->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                        'class' => 'btn btn-success'
                    ]) ?>
                <?php ActiveForm::end() ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
