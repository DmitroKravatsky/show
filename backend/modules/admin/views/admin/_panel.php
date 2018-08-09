<?php

use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
use yiister\gentelella\widgets\grid\GridView;
use common\models\bid\BidEntity;
use common\models\userNotifications\UserNotificationsEntity;
use yii\helpers\StringHelper;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use kartik\daterange\DateRangePicker;

/** @var \yii\web\View $this */
/* @var $bidSearch \common\models\bid\BidSearch */
/* @var $bidProvider ActiveDataProvider */
/* @var $reviewSearch \common\models\review\ReviewSearch */
/* @var $reviewProvider ActiveDataProvider */
/* @var $userSearch \common\models\user\UserSearch */
/* @var $userProvider ActiveDataProvider */
/* @var $notificationsSearch UserNotificationsEntity */
/* @var $notificationsProvider ActiveDataProvider */
?>

<div class="col-md-6">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Bids'),
        'collapsable' => true,
        'expandable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $bidProvider,
                'filterModel' => $bidSearch,
                'hover' => true,
                'summary' => '',
                'columns' => [
                    'id',
                    'email:email',
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
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>

<div class="col-md-6">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Reviews'),
        'collapsable' => true,
        'expandable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $reviewProvider,
                'filterModel' => $reviewSearch,
                'hover' => true,
                'summary' => '',
                'columns' => [
                    'id',
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
                    'hover' => true,
                    'summary' => '',
                    'columns' => [
                        'id',
                        'email:email',
                        [
                            'attribute' => 'created_at',
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
                'hover' => true,
                'summary' => '',
                'columns' => [
                    'id',
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
                            return StringHelper::truncate(Html::encode($userNotifications->text), 40);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
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
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>
