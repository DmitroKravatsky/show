<?php

use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
use kartik\grid\GridView;
use common\models\{
    bid\BidEntity, userNotifications\NotificationsEntity, userNotifications\UserNotifications, userNotifications\NotificationsSearch, user\User
};
use yii\helpers\{
    Html,
    Url,
    StringHelper
};
use yii\data\ActiveDataProvider;
use kartik\daterange\DateRangePicker;
use yii\grid\ActionColumn;
use common\helpers\UrlHelper;

/** @var \yii\web\View $this */
/* @var $bidSearch \common\models\bid\BidSearch */
/* @var $bidProvider ActiveDataProvider */
/* @var $reviewSearch \common\models\review\ReviewSearch */
/* @var $reviewProvider ActiveDataProvider */
/* @var $userSearch \common\models\user\UserSearch */
/* @var $userProvider ActiveDataProvider */
/* @var $notificationsSearch NotificationsSearch */
/* @var $notificationsProvider ActiveDataProvider */
?>

<div class="col-md-6">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Bids'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $bidProvider,
                'filterModel' => $bidSearch,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                    'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                ],
                'toolbar' => '',
                'hover' => true,
                'summary' => '',
                'columns' => [
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width' => '36px',
                        'header' => '',
                        'headerOptions' => ['class' => 'kartik-sheet-style']
                    ],
                    'email:email',
                    [
                        'attribute' => 'from_sum',
                        'label' => Yii::t('app', 'Amount From Customer'),
                        'value' => function (BidEntity $bid) {
                            return round($bid->from_sum, 2) . ' ' . $bid->from_currency;
                        }
                    ],
                    [
                        'attribute' => 'to_sum',
                        'label' => Yii::t('app', 'Amount To Be Transferred'),
                        'value' => function (BidEntity $bid) {
                            return round($bid->to_sum, 2) . ' ' . $bid->to_currency;
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => BidEntity::statusLabels(),
                        'value' => function (BidEntity $bid) {
                            return BidEntity::getStatusValue($bid->status);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                        'filter' => DateRangePicker::widget([
                            'model' => $bidSearch,
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
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/bid/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            }
                        ],
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>

<div class="col-md-6">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Reviews'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $reviewProvider,
                'filterModel' => $reviewSearch,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                    'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                ],
                'toolbar' => '',
                'hover' => true,
                'summary' => '',
                'columns' => [
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width' => '36px',
                        'header' => '',
                        'headerOptions' => ['class' => 'kartik-sheet-style']
                    ],
                    'created_by',
                    'text:ntext',
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                        'filter' => DateRangePicker::widget([
                            'model' => $reviewSearch,
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
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/review/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            }
                        ],
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>

<div class="clearfix"></div>

<?php if (Yii::$app->user->can('admin')): ?>
    <div class="col-md-6">
        <?php Panel::begin([
            'header' => Yii::t('app', 'Managers'),
            'collapsable' => true,
            'removable' => true,
        ]) ?>
            <?php Pjax::begin() ?>
                <?= GridView::widget([
                    'dataProvider' => $userProvider,
                    'filterModel' => $userSearch,
                    'filterUrl' => UrlHelper::getFilterUrl(),
                    'panel' => [
                        'type' => GridView::TYPE_DEFAULT,
                        'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                    ],
                    'toolbar' => '',
                    'hover' => true,
                    'summary' => '',
                    'columns' => [
                        [
                            'class' => 'kartik\grid\SerialColumn',
                            'contentOptions' => ['class' => 'kartik-sheet-style'],
                            'width' => '36px',
                            'header' => '',
                            'headerOptions' => ['class' => 'kartik-sheet-style']
                        ],
                        'email:email:E-mail',
                        [
                            'attribute' => 'status_online',
                            'label' => Yii::t('app', 'Status Online'),
                            'filter' => User::getStatusOnlineLabels(),
                            'value' => function (User $user) {
                                return User::getStatusOnlineValue($user->status_online);
                            }
                        ],
                        [
                            'attribute' => 'last_login',
                            'label' => Yii::t('app', 'Last Login'),
                            'format' => 'date',
                            'filter' => DateRangePicker::widget([
                                'model' => $userSearch,
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
                                'model' => $userSearch,
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
                        [
                            'class' => ActionColumn::class,
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function($url, $model) {
                                    return Html::a(
                                        '<span class="glyphicon glyphicon-eye-open"></span>',
                                        Url::to(['/manager/view/' . $model->id]),
                                        ['title' => Yii::t('app', 'View')]
                                    );
                                }
                            ],
                        ],
                    ],
                ]) ?>
            <?php Pjax::end() ?>
        <?php Panel::end() ?>
    </div>
<?php endif; ?>

<div class="col-md-6">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Notifications'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $notificationsProvider,
                'filterModel' => $notificationsSearch,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                    'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                ],
                'toolbar' => '',
                'hover' => true,
                'summary' => '',
                'columns' => [
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width' => '36px',
                        'header' => '',
                        'headerOptions' => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'attribute' => 'is_read',
                        'filter' => UserNotifications::getIsReadStatuses(),
                        'value' => function (UserNotifications $userNotification) {
                            return UserNotifications::getIsReadLabel($userNotification->is_read);
                        }
                    ],
                    [
                        'attribute' => 'text',
                        'value' => function (UserNotifications $userNotification) {
                            return StringHelper::truncate( Yii::t('app', $userNotification->notification->text, [
                                'full_name'=> $userNotification->notification->custom_data->full_name ?? null,
                                'sum'      => $userNotification->notification->custom_data->sum ?? null,
                                'currency' => $userNotification->notification->custom_data->currency ?? null,
                                'wallet'   => $userNotification->notification->custom_data->wallet ?? null,
                                'phone_number' => $userNotification->notification->custom_data->phone_number ?? null,
                            ]), 40);
                        }
                    ],
                    [
                        'attribute' => 'dateRange',
                        'label' => Yii::t('app', 'Created At'),
                        'format' => 'date',
                        'value' => 'created_at',
                        'filter' => DateRangePicker::widget([
                            'model' => $notificationsSearch,
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
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function($url, $userNotification) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/notifications/view/' , 'id' => $userNotification->notification_id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            }
                        ],
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>
