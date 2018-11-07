<?php
/** @var \yii\web\View $this */

use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yiister\gentelella\widgets\Panel;

$this->title = Yii::t('app', 'Reviews') ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="reserve-update">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>

        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Review'),
            ]) ?>
            <?php $form = ActiveForm::begin() ?>

            <?= $form->field($review, 'text')->textInput(['maxlength' => true]) ?>


            <?= Html::submitButton($review->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), [
                'class' => 'btn btn-success'
            ]) ?>
            <?php ActiveForm::end() ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
