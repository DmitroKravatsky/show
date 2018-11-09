<?php

use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use backend\models\BackendUser;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use kartik\grid\GridView;
use common\models\user\User;
use common\helpers\UrlHelper;
use common\helpers\Toolbar;

/** @var \yii\web\View $this */
/** @var BackendUser $manager */
/* @var \common\models\user\UserSearch $searchModel */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Managers'), 'url' => ['index']];
$this->title = Yii::t('app', 'Manager') . ': ' . $manager->id;
$this->params['breadcrumbs']['title'] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<?php $this->registerJs('var language = "' . Yii::$app->language . '"', View::POS_HEAD) ?>

<div class="modal" tabindex="-1"  id="update-manager-password-form" role="dialog" hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'Change Password') ?></h5>
            </div>

            <?php $form = ActiveForm::begin([
                'action'  => '/admin/manager/update-password',
            ]); ?>
                <div class="x_content">
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>

                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?= $form->field($manager, 'email', ['enableClientValidation' => true])->hiddenInput([
                            ])->label(false) ?>
                        </div>

                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?= Yii::t('app', 'Password'); ?></label>

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
        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>

        <div class="col-md-6">
            <div id="re-invite-success"></div>
            <div id="re-invite-error"></div>
            <?php Panel::begin([
                'header' => Yii::t('app', 'Manager'),
                'collapsable' => true,
            ]) ?>
                <div class="form-group">
                    <?= Html::a('<i class="glyphicon glyphicon-envelope"></i>', false, [
                        'reInviteUrl' => Url::to(['/manager/re-invite', 'userId' => $manager->id,]),
                        'title' => Yii::t('app', 'Re-invite'),
                        'class' => 'ajaxReInviteMessage btn btn-success',
                        'method' => 'post'
                    ]) ?>

                    <?= Html::a('<i class="glyphicon glyphicon-edit"></i>', false, [
                        'title' => Yii::t('app', 'Change Password'),
                        'class' => 'btn btn-warning',
                        'id' => 'update-manager-password-button'
                    ]) ?>

                    <?php if (!BackendUser::managerHasBidInProgress($manager->id)) :?>
                        <?= Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                Url::to(['/manager/delete', 'userId' => $manager->id]),
                                    [
                                        'title' => Yii::t('app', 'Delete'),
                                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'class' => 'btn btn-danger'
                                    ]
                        ); ?>
                    <?php endif; ?>
                </div>

                <?= DetailView::widget([
                    'model' => $manager,
                    'attributes' => [
                        [
                            'attribute' => 'avatar',
                            'label' => Yii::t('app', 'Avatar'),
                            'format' => 'html',
                            'value' => function (BackendUser $user) {
                                return (isset($user->profile)) && $user->profile->avatar !== null
                                    ? Html::img($user->profile->getImageUrl(), ['style' => 'height:100px;'])
                                    : Html::img(Yii::getAlias('@image.default.user.avatar'), ['style' => 'height:100px;']);
                            }
                        ],
                        'email:email:E-mail',
                        'phone_number',
                        [
                            'attribute' => 'sourse',
                            'label' => Yii::t('app', 'Registration Method'),
                            'value' => function (BackendUser $user) {
                                return BackendUser::getRegistrationMethodLabel($user->source);
                            }
                        ],
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
            <?php Panel::end() ?>
            <div id="loader"></div>

            <div class="manager-statistic-index">
                <?php Panel::begin([
                    'header' => Yii::t('app', 'Statistics'),
                    'collapsable' => true,
                ]) ?>
                <?= GridView::widget([
                    'filterModel' => $searchModel,
                    'filterUrl' => UrlHelper::getFilterUrl(),
                    'dataProvider' => $dataProvider,
                    'toolbar' =>  [
                        '{export}',
                        '{toggleData}',
                    ],
                    'export' => [
                        'fontAwesome' => true
                    ],
                    'panel' => [
                        'type' => GridView::TYPE_DEFAULT,
                        'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                    ],
                    'columns' => [
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'contentOptions' => ['class' => 'kartik-sheet-style'],
                            'width' => '36px',
                            'header' => '',
                            'headerOptions' => ['class' => 'kartik-sheet-style']
                        ],
                        [
                            'attribute' => 'full_name',
                            'label' => Yii::t('app', 'Full Name'),
                            'value' => function (User $user) {
                                return ($user->getFullName()) ?? null;
                            },
                        ],
                        'email:email:E-mail',
                        'phone_number:raw:' . Yii::t('app', 'Phone Number'),
                        [
                            'attribute' => 'status_online',
                            'label' => Yii::t('app', 'Status Online'),
                            'filter' => false,
                            'value' => function (User $user) {
                                return User::getStatusOnlineValue($user->status_online);
                            }
                        ],
                        [
                            'attribute' => 'last_login',
                            'label' => Yii::t('app', 'Last Login'),
                            'format' => 'date',
                            'filter' => false,
                        ],
                        [
                            'attribute' => 'created_at',
                            'label' => Yii::t('app', 'Created At'),
                            'format' => 'date',
                            'filter' => false,
                        ],
                    ]
                ]) ?>
                <?php Panel::end() ?>

                <div id="loader"></div>
            </div>
        </div>
    </div>
</div>
