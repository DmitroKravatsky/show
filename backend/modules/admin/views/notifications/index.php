<?php

use common\models\userNotifications\UserNotifications;
use yiister\gentelella\widgets\Panel;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use yii\helpers\{ Html, Url };
use common\helpers\{ UrlHelper, Toolbar };

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\userNotifications\UserNotificationsSearch $searchModel */

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<div class="notifications-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Notifications'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'filterUrl'    => UrlHelper::getFilterUrl(),
                'hover'        => true,
                'toolbar'      =>  [
                    ['content' =>
                        Toolbar::deleteButton('/notifications/delete-all', Yii::t('app', 'Delete all'), !UserNotifications::isUserHasMessages()) .
                        Toolbar::readAllButton('/notifications/read-all', !UserNotifications::isUserHasUnreadMessages()) .
                        Toolbar::resetButton()
                    ],
                    '{export}',
                    '{toggleData}',
                ],
                'export'       => [
                    'fontAwesome' => true
                ],
                'panel'        => [
                    'type'    => GridView::TYPE_DEFAULT,
                    'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                ],
                'rowOptions'   => function (UserNotifications $userNotifications) {
                    return $userNotifications->is_read === UserNotifications::STATUS_READ_NO ? ['class' => 'success'] : [];
                },
                'columns'      => [
                    [
                        'class'          => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width'          => '36px',
                        'header'         => '',
                        'headerOptions'  => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'class'    => \yii\grid\ActionColumn::class,
                        'template' => '{view}&nbsp{delete}',
                        'buttons'  => [
                            'view' => function($url, UserNotifications $notification) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/notifications/view', 'id' => $notification->notification_id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            },
                            'delete' => function($url, UserNotifications $notification) {
                                $url = Url::to(['/notifications/delete', 'id' => $notification->notification_id]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                ]);
                            },
                        ]
                    ],
                    [
                        'attribute' => 'full_name',
                        'label'     => Yii::t('app', 'Recipient'),
                        'value'     => function (UserNotifications $userNotification) {
                            return $userNotification->userProfile->userFullName ?? null;
                        }
                    ],
                    [
                        'attribute' => 'is_read',
                        'filter'    => UserNotifications::getIsReadStatuses(),
                        'value'     => function (UserNotifications $userNotification) {
                            return UserNotifications::getIsReadLabel($userNotification->is_read);
                        }
                    ],
                    [
                        'attribute' => 'text',
                        'value'     => function (UserNotifications $userNotification) {
                            return Yii::t('app', $userNotification->notification->text, [
                                'full_name'    => $userNotification->notification->custom_data->full_name ?? null,
                                'sum'          => $userNotification->notification->custom_data->sum ?? null,
                                'currency'     => $userNotification->notification->custom_data->currency ?? null,
                                'wallet'       => $userNotification->notification->custom_data->wallet ?? null,
                                'phone_number' => $userNotification->notification->custom_data->phone_number ?? null,
                            ]);
                        }
                    ],
                    [
                        'attribute' => 'dateRange',
                        'label'     => Yii::t('app', 'Created At'),
                        'format'    => 'date',
                        'value'     => 'notification.created_at',
                        'filter'    => DateRangePicker::widget([
                            'model'         => $searchModel,
                            'attribute'     => 'dateRange',
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'timePicker' => true,
                                'locale' => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>
