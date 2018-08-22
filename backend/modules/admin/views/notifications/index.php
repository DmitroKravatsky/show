<?php

use yiister\gentelella\widgets\Panel;
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;
use common\models\userNotifications\UserNotificationsEntity;
use kartik\daterange\DateRangePicker;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use backend\models\BackendUser;

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
                'hover' => true,
                'columns' => [
                    [
                        'attribute' => 'recipient_id',
                        'value' => function (UserNotificationsEntity $userNotifications) {
                            return $userNotifications->recipient->profile->name ?? null;
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => UserNotificationsEntity::getStatusLabels(),
                        'value' => function (UserNotificationsEntity $userNotifications) {
                            return UserNotificationsEntity::getStatusValue($userNotifications->status);
                        }
                    ],
                    [
                        'attribute' => 'text',
                        'value' => function (UserNotificationsEntity $userNotifications) {
                            return StringHelper::truncate(Html::encode($userNotifications->text), 180);
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

