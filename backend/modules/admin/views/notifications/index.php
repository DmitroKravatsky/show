<?php

use yiister\gentelella\widgets\Panel;
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;
use common\models\userNotifications\UserNotificationsEntity as Notification;
use kartik\daterange\DateRangePicker;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use common\helpers\UrlHelper;

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\userNotifications\UserNotificationsSearch $searchModel */

$this->title = Yii::t('app', 'Notifications');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="notifications-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Notifications'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'hover' => true,
                'columns' => [
                    [
                        'attribute' => 'recipient_id',
                        'value' => function (Notification $notification) {
                            return $notification->recipient->profile->name ?? null;
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => Notification::getStatusLabels(),
                        'value' => function (Notification $notification) {
                            return Notification::getStatusValue($notification->status);
                        }
                    ],
                    [
                        'attribute' => 'text',
                        'value' => function (Notification $notification) {
                            if ($notification->type == Notification::TYPE_NEW_USER) {
                                return Yii::t('app', $notification->text, [
                                    'phone_number' => $notification->custom_data->phone_number ?? null
                                ]);
                            }
                            return StringHelper::truncate(Html::encode($notification->text), 180);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
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
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view}&nbsp{delete}',
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>

