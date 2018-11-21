<?php

use kartik\{ daterange\DateRangePicker, grid\GridView };
use yii\helpers\{ Html, Url };
use common\models\{ bid\BidEntity as Bid, user\User };
use yii\widgets\Pjax;
use common\helpers\UrlHelper;
use yiister\gentelella\widgets\Panel;
use common\helpers\Toolbar;
use backend\models\BackendUser;
use yii\web\View;
use kartik\select2\Select2;
use common\models\paymentSystem\PaymentSystem;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\bid\BidSearch */

$this->title = Yii::t('app', 'Bids');
?>

<?= Html::style('td span {line-height: 20px}') ?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<?php $this->registerJs(
        'var language = "' . Yii::$app->language . '";  addEventListener("popstate",function(e){location.reload();},false);',
        View::POS_HEAD
) ?>

<div id="bid-status-error"></div>
<div id="bid-status-success"></div>

<div class="bid-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Bids'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin(['id' => 'pjax-container'])?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'filterUrl'    => UrlHelper::getFilterUrl(),
                'hover'        => true,
                'toolbar'      =>  [
                    ['content' =>
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
                'rowOptions'   => function (Bid $bid) {
                    return $bid->status === Bid::STATUS_NEW ? ['class' => 'success'] : [];
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
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view}' . (Yii::$app->user->can('admin') ? ' {delete}' : ''),
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/bid/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View'), 'data-pjax' => 0]
                                );
                            },
                            'delete' => function($url, $model) {
                                $customUrl = Url::to(['bid/delete', 'id' => $model['id']]);

                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-message' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'class' => 'delete-button',
                                ]);
                            },
                        ]
                    ],
                    [
                        'attribute'      => 'status',
                        'filter'         => Bid::statusLabels(),
                        'value'          => function (Bid $bid) {
                            return Bid::getStatusValue($bid->status);
                        },
                        'contentOptions' => ['class' => 'status-column'],
                    ],
                    [
                        'attribute' => 'change_status',
                        'label'     => Yii::t('app', 'Change Status'),
                        'value'     => function (Bid $bid) {
                            return Select2::widget([
                                'name'    => 'status',
                                'data'    => Bid::getManagerAllowedStatusesWithOutCurrentStatus($bid->status),
                                'options' => [
                                    'class'       => 'status',
                                    'disabled'    => !Bid::canUpdateStatus($bid),
                                    'placeholder' => Yii::t('app', 'Select status'),
                                ],
                            ]);
                        },
                        'format'          => 'raw',
                        'filter'          => Bid::statusLabels(),
                    ],
                    [
                        'attribute' => 'myBid',
                        'label'     => Yii::t('app', 'My Bid'),
                        'filter'    => Bid::getManagerBidStatuses(),
                        'value'     => function (Bid $bid) {
                            if ($bid->processed_by == Yii::$app->user->id || $bid->in_progress_by_manager == Yii::$app->user->id) {
                                return Yii::t('app', 'Yes');
                            }
                            return Yii::t('app', 'No');
                        }
                    ],
                    [
                        'attribute' => 'full_name',
                        'label'     => Yii::t('app', 'Full Name'),
                        'format'    => 'raw',
                        'value'     => function (Bid $bid) {
                            return $bid->author->getFullName();
                        }
                    ],
                    [
                        'attribute' => 'email',
                        'label'     => 'E-mail',
                        'value'     => function (Bid $bid) {
                            return $bid->author->email;
                        }
                    ],
                    [
                        'attribute' => 'phone_number',
                        'value'     => function (Bid $bid) {
                            return $bid->author->phone_number;
                        }
                    ],
                    [
                        'attribute'      => 'processed',
                        'label'          => Yii::t('app', 'Bid Closed'),
                        'visible'        => Yii::$app->user->can(User::ROLE_ADMIN),
                        'format'         => 'raw',
                        'filter'         => Bid::getProcessedStatusList(),
                        'value'          => 'processedStatus',
                        'contentOptions' => ['class' => 'processed-column'],
                    ],
                    [
                        'attribute'      => 'processed_by',
                        'filter'         => BackendUser::getManagerNames(),
                        'visible'        => Yii::$app->user->can(User::ROLE_ADMIN),
                        'format'         => 'html',
                        'value'          => function (Bid $bid) {
                            if (isset($bid->perfomer)) {
                                return Html::a(
                                    $bid->perfomer->fullName,
                                    Url::to(["/manager/view/{$bid->processed_by}"]),
                                    ['title' => Yii::t('yii', 'View')]
                                );
                            }
                            return null;
                        },
                        'contentOptions' => ['class' => 'processed-by-column'],
                    ],
                    [
                        'attribute'      => 'in_progress_by_manager',
                        'filter'         => BackendUser::getManagerNames(),
                        'format'         => 'html',
                        'value'          => function (Bid $bid) {
                            if (isset($bid->inProgressByManager)) {
                                return Html::a(
                                    $bid->inProgressByManager->fullName,
                                    Url::to(["/manager/view/{$bid->in_progress_by_manager}"]),
                                    ['title' => Yii::t('yii', 'View')]
                                );
                            }
                            return null;
                        },
                        'contentOptions' => ['class' => 'in-progress-by-column'],
                    ],
                    [
                        'attribute' => 'from_sum',
                        'filter'    => false,
                        'format'    => 'raw',
                        'header'    => Yii::t('app', 'Amount From Customer'),
                        'value'     => function (Bid $bid) {
                            return $bid->from_sum . ' ' . PaymentSystem::getCurrencyValue($bid->fromPaymentSystem->currency);
                        },
                    ],
                    [
                        'attribute' => 'to_sum',
                        'filter'    => false,
                        'format'    => 'raw',
                        'header'    => Yii::t('app', 'Amount To Be Transferred'),
                        'value'     => function(Bid $bid) {
                            return $bid->to_sum . ' ' . PaymentSystem::getCurrencyValue($bid->toPaymentSystem->currency);
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format'    => 'date',
                        'value'     => 'created_at',
                        'filter'    => DateRangePicker::widget([
                            'model'          => $searchModel,
                            'attribute'      => 'dateRange',
                            'convertFormat'  => true,
                            'pluginOptions'  => [
                                'timePicker' => true,
                                'locale'     => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                ]

            ])?>
        <?php Pjax::end()?>
    <?php Panel::end() ?>
    <div id="loader"></div>
</div>
