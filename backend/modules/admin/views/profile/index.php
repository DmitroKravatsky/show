<?php

use yiister\gentelella\widgets\Panel;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\bootstrap\Tabs;
use kartik\file\FileInput;

/** @var $this \yii\web\View */
/** @var \backend\models\BackendUser $user */
/** @var \common\models\userProfile\UserProfileEntity $profile */
/** @var string $activeTab */

$filePreviewClass = 'file-preview-image kv-preview-data';
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
            <?php $form = ActiveForm::begin([
                'action' => Url::to(['profile/update-avatar']),
                'options' => [
                    'enctype' => 'multipart/form-data',
                ],
            ]) ?>
                <?= $form->field($profile, 'image')->widget(FileInput::class, [
                    'pluginOptions' => [
                        'initialPreview' => [
                            $profile->avatar !== null
                                ? Html::img($profile->getImagePath(), ['class' => $filePreviewClass])
                                : Html::img(Yii::getAlias('@image.default.user.avatar'), ['class' => $filePreviewClass])
                        ],
                        'fileActionSettings' => [
                            'showRemove' => false,
                        ],
                    ]
                ])->label(false) ?>
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
