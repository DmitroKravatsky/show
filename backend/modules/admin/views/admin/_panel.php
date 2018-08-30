<?php

use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
use kartik\grid\GridView;
use common\models\{
    bid\BidEntity,
    userNotifications\UserNotificationsEntity as Notification
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
/* @var $notificationsSearch Notification */
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
                        'value' => function (BidEntity $bid) {
                            return round($bid->from_sum, 2) . ' ' . $bid->from_currency;
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
        'expandable' => true,
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
                        'attribute' => 'status',
                        'filter' => Notification::getStatusLabels(),
                        'value' => function (Notification $notification) {
                            return Notification::getStatusValue($notification->status);
                        }
                    ],
                    [
                        'attribute' => 'text',
                        'value' => function (Notification $notification) {
                            if ($notification->type = Notification::TYPE_NEW_USER) {
                                return StringHelper::truncate(Yii::t('app', $notification->text, [
                                    'phone_number' => $notification->custom_data->phone_number ?? null
                                ]), 40);
                            }
                            return null;
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
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/notification/view/' . $model->id]),
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
