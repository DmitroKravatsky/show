<?php

use yiister\gentelella\widgets\Panel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Tabs;

/** @var $this \yii\web\View */
/** @var \backend\models\BackendUser $user */
/** @var \common\models\userProfile\UserProfileEntity $profile */
/** @var string $activeTab */
?>

<div class="profile-index">
    <div class="title">
        <h3><?= Yii::t('app', 'User Profile') ?></h3>
    </div>

    <?php Panel::begin([
        'header' => Yii::t('app', 'User Report'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <div class="col-md-3 col-sm-3 col-xs-12 profile_left">
            <div class="profile_img">
                <?= Html::img(Yii::getAlias('@image.default.user.avatar'), ['style' => 'height:200px']) ?>
            </div>

            <h3><?= Html::encode($user->fullName) ?></h3>

            <?php $form = ActiveForm::begin([]) ?>
                <label class="btn btn-info" style="height: 34px">
                    <?= Yii::t('app', 'Upload') ?>
                    <?= $form->field($profile, 'avatar')->fileInput(['style' => 'display:none'])->label(false) ?>
                </label>

                <?= Html::submitButton(Yii::t('app', 'Update'), ['class' => 'btn btn-success']) ?>
            <?php ActiveForm::end() ?>
        </div>

        <div class="col-md-9 col-sm-9 col-xs-12">
            <?= Tabs::widget([
                'items' => [
                    [
                        'label' => Yii::t('app', 'General'),
                        'active' => $activeTab == 'general',
                        'options' => ['id' => 'general'],
                        'content' => $this->render('_general', ['profile' => $profile]),
                    ],
                    [
                        'label' => Yii::t('app', 'Password'),
                        'active' => $activeTab == 'password',
                        'options' => ['id' => 'password'],
                        'content' => $this->render('_password', ['user' => $user]),
                    ],
                ]
            ]) ?>
        </div>
    <?php Panel::end() ?>
</div>
