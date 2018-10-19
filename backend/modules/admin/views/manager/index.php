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
use yii\web\View;

/* @var $this yii\web\View */
/* @var \common\models\user\UserSearch $searchModel */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Managers');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<?php $this->registerJs('var language = "' . Yii::$app->language . '"', View::POS_HEAD) ?>

<div class="manager-index">
    <div id="re-invite-success"></div>
    <div id="re-invite-error"></div>

    <?php Panel::begin([
        'header' => Yii::t('app', 'Managers'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'dataProvider' => $dataProvider,
                'toolbar' =>  [
                    ['content' =>
                        Toolbar::createButton(Url::to('/manager/invite'), Yii::t('app', 'Invite new manager')) .
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
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view} {delete} {reInvite}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/manager/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            },
                            'delete' => function($url, User $model) {
                                $customUrl = Url::to(['/manager/delete', 'userId' => $model->id]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                ]);
                            },
                            'reInvite' => function($url, User $model) {
                                return Html::a('<span class="glyphicon glyphicon-envelope"></span>', false, [
                                    'reInviteUrl' => Url::to(['/manager/re-invite', 'userId' => $model->id,]),
                                    'title' => Yii::t('app', 'Re-invite'),
                                    'class' => 'ajaxReInviteMessage',
                                    'method' => 'post'
                                ]);
                            }
                        ]
                    ],
                    'id:raw:#',
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
                        'attribute' => 'accept_invite',
                        'label' => Yii::t('app', 'Accept Invite'),
                        'filter' => User::getAcceptInviteLabels(),
                        'value' => function (User $user) {
                            return User::getAcceptInviteStatusValue($user->accept_invite);
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
                        'attribute' => 'created_at',
                        'label' => Yii::t('app', 'Created At'),
                        'format' => 'date',
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'dateRange',
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'timePicker' => true,
                                'locale' => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                ]
            ]) ?>
        <?php Pjax::end(); ?>
    <?php Panel::end() ?>

    <div id="loader"></div>
</div>
