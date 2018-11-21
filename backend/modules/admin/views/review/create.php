<?php
/** @var \yii\web\View $this */
/** @var ReviewEntity $review */

use common\models\review\ReviewEntity;
use kartik\file\FileInput;
use yii\bootstrap\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use yiister\gentelella\widgets\Panel;

$this->title = Yii::t('app', 'New review') ;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="profile-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'New review'),
        'collapsable' => true,
    ]) ?>
        <?php $form = ActiveForm::begin([
            'action' => Url::to(['review/create']),
            'options' => [
                'enctype' => 'multipart/form-data',
            ],
        ]) ?>
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-5 profile_left">
                <?= $form->field($review, 'avatar')->widget(FileInput::class, [
                    'name' => 'attachment_53',
                    'pluginOptions' => [
                        'showCaption' => false,
                        'showRemove' => false,
                        'showUpload' => false,
                        'browseClass' => 'btn btn-primary btn-block',
                        'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                        'browseLabel' =>  Yii::t('app', 'Select Photo'),
                        'fileActionSettings' => [
                            'showRemove' => false,
                        ],
                    'options' => ['accept' => 'image/*'],

                    ]
                ])->label(false) ?>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12 col-lg-7">

                <?= $form->field($review, 'text')->textarea(['maxlength' => true]) ?>

                <?= $form->field($review, 'name')->input(['maxlength' => true]) ?>

                <?= $form->field($review, 'last_name')->input(['maxlength' => true]) ?>

                <?= Html::submitButton(Yii::t('app', 'Create'), ['class' => 'btn btn-success']) ?>
            </div>
        <?php ActiveForm::end() ?>
    <?php Panel::end() ?>
</div>

