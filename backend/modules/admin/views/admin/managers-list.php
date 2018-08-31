<?php

use common\models\user\User;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
use common\helpers\UrlHelper;
use common\helpers\Toolbar;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var \common\models\user\UserSearch $searchModel */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Managers');
$this->params['breadcrumbs'][] = $this->title;
?>

<div id="re-invite-success" class="alert alert-success alert-dismissible fade in" role="alert" style="display: none">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <?= Yii::t('app', 'Message was successfully send.') ?>
</div>

<div id="re-invite-error" class="alert alert-error alert-dismissible fade in" role="alert" style="display: none">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <?= Yii::t('app', 'Something wrong, please try again later.') ?>
</div>

<div class="site-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Managers'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'dataProvider' => $dataProvider,
                'toolbar' =>  [
                    ['content' =>
                        Toolbar::createButton('/invite-manager', Yii::t('app', 'Invite new manager')) .
                        Toolbar::resetButton()
                    ],
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
                            return ($user->profile->name . ' ' . $user->profile->last_name) ?? null;
                        },
                    ],
                    'email:email:E-mail',
                    'phone_number:raw:' . Yii::t('app', 'Phone Number'),
                    [
                        'attribute' => 'status_online',
                        'label' => Yii::t('app', 'Status Online'),
                        'filter' => User::getStatusOnlineLabels(),
                        'value' => function (User $user) {
                            return User::getStatusOnlineValue($user->status_online);
                        }
                    ],
                    [
                        'attribute' => 'invite_code_status',
                        'label' => Yii::t('app', 'Invite Code Status'),
                        'filter' => User::getInviteStatuses(),
                        'value' => function (User $user) {
                            return User::getInviteStatusValue($user->invite_code_status);
                        }
                    ],
                    [
                        'attribute' => 'last_login',
                        'label' => Yii::t('app', 'Last Login'),
                        'format' => 'date',
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'lastLoginRange',
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'timePicker' => true,
                                'locale' => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{delete} {reInvite}',
                        'buttons' => [
                            'delete' => function($url, User $model) {
                                $customUrl = Url::to([
                                    '/admin/admin/delete-manager',
                                    'user_id' => $model->id
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    ]);
                            },
                            'reInvite' => function($url, User $model) {
                                $reInviteUrl = Url::to([
                                    '/admin/admin/re-invite',
                                    'user_id' => $model->id,
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-envelope"></span>', false, [
                                    'reInviteUrl' => $reInviteUrl,
                                    'title' => Yii::t('app', 'Re-invite'),
                                    'class' => 'ajaxReInviteMessage',
                                    'method' => 'post'
                                ]);
                            }
                        ]
                    ]
                ]
            ]) ?>
        <?php Pjax::end(); ?>
    <?php Panel::end() ?>

    <div id="loader"></div>
</div>
