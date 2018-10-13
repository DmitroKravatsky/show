<?php

use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use backend\models\BackendUser;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var BackendUser $manager */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Managers'), 'url' => ['index']];
$this->title = Yii::t('app', 'Manager') . ': ' . $manager->id;
$this->params['breadcrumbs']['title'] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>
<div class="modal" tabindex="-1"  id="update-manager-password-form" role="dialog" hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'Change Password') ?></h5>
            </div>
            <div class="x_content">
                <div id="alerts"></div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?= Yii::t('app', 'Password'); ?></label>
                    <?php $form = ActiveForm::begin([
                        'action'  => '/admin/manager/update-password',
                    ]); ?>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?= $form->field($manager, 'email', ['enableClientValidation' => true])->hiddenInput([
                        ])->label(false) ?>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?= $form->field($manager, 'newPassword', ['enableClientValidation' => true])->passwordInput([
                            'autofocus'   => true,
                        ])->label(false) ?>
                    </div>
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"><?= Yii::t('app', 'Repeat Password'); ?></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <?= $form->field($manager, 'repeatPassword', ['enableClientValidation' => true])->passwordInput([
                        ])->label(false) ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('app', Yii::t('app', 'Save')), [
                    'class' => 'btn btn-primary',
                ]) ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
            </div>
            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div class="manager-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Manager'),
                'collapsable' => true,
            ]) ?>
                <?= DetailView::widget([
                    'model' => $manager,
                    'attributes' => [
                        [
                            'attribute' => 'avatar',
                            'label' => Yii::t('app', 'Avatar'),
                            'format' => 'html',
                            'value' => function (BackendUser $user) {
                                return (isset($user->profile)) && $user->profile->avatar !== null
                                    ? Html::img($user->profile->getImageUrl(), ['style' => 'height:150px;'])
                                    : Html::img(Yii::getAlias('@image.default.user.avatar'), ['style' => 'height:150px;']);
                            }
                        ],
                        'email:email:E-mail',
                        'phone_number',
                        'source',
                        [
                            'attribute' => 'status_online',
                            'label' => Yii::t('app', 'Status Online'),
                            'value' => function (BackendUser $user) {
                                return BackendUser::getStatusOnlineValue($user->status_online);
                            }
                        ],
                        [
                            'attribute' => 'full_name',
                            'label' => Yii::t('app', 'Full Name'),
                            'value' => function (BackendUser $user) {
                                return $user->profile->getUserFullName() ?? null;
                            }
                        ],
                        'created_at:date:' . Yii::t('app', 'Created At'),
                        'last_login:datetime:' . Yii::t('app', 'Last Login'),
                    ],
                ]) ?>
                <div class="form-group">
                    <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                        <button id="update-manager-password-button" type="submit" class="btn btn-success"><?=Yii::t('app', 'Change Password')?></button>
                    </div>
                </div>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
