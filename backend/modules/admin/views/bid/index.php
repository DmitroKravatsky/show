<?php

use kartik\daterange\DateRangePicker;
use kartik\grid\GridView;
use yii\helpers\Html;
use common\models\bid\BidEntity;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\user\User;
use common\helpers\UrlHelper;
use yiister\gentelella\widgets\Panel;
use common\helpers\Toolbar;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \backend\modules\admin\models\BidEntitySearch */

$this->title = Yii::t('app', 'Bids');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if ($message = Yii::$app->session->getFlash('delete-success')): ?>
    <div class="alert alert-success">
        <?= $message ?>
    </div>
<?php endif;?>

<div class="bid-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Bids'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin()?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'hover' => true,
                'toolbar' =>  [
                    ['content' =>
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
                        'attribute' => 'status',
                        'value' => function($model) {
                            return Html::activeDropDownList($model, 'status', BidEntity::statusLabels(),
                                [
                                    'class' => 'status',
                                ]
                            );
                        },
                        'contentOptions' => ['style' => 'width:11%;'],
                        'format' => 'raw',
                        'filter' => BidEntity::statusLabels()
                    ],
                    [
                        'attribute' => 'full_name',
                        'label' => Yii::t('app', 'Full Name'),
                        'format' => 'raw',
                        'value' => function($model) {
                            return $model->last_name . ' ' . $model->name;
                        }
                    ],
                    'email:email:E-mail',
                    'phone_number',
                    [
                        'attribute' => 'processed',
                        'visible' => Yii::$app->user->can(User::ROLE_ADMIN),
                        'format' => 'raw',
                        'filter' => BidEntity::getProcessedStatusList(),
                        'value'  => 'processedStatus'
                    ],
                    [
                        'attribute' => 'processed_by',
                        'visible' => Yii::$app->user->can(User::ROLE_ADMIN),
                        'value' => function (BidEntity $bid) {
                            return $bid->perfomer->fullName ?? null;
                        }
                    ],
                    [
                        'attribute' => 'from_sum',
                        'format' => 'raw',
                        'header' => Yii::t('app', 'Amount From Customer'),
                        'value' => function($model) {
                            return $model->from_sum . ' ' . $model->from_currency;
                        },
                    ],
                    [
                        'attribute' => 'to_sum',
                        'format' => 'raw',
                        'header' => Yii::t('app', 'Amount To Be Transferred'),
                        'value' => function($model) {
                            return $model->to_sum . ' ' . $model->to_currency;
                        },
                    ],
                    [
                        'attribute' => 'from_wallet',
                        'format' => 'raw',
                        'header' => Yii::t('app', 'Where Did The Money Come From'),
                        'value' => function($model) {
                            return $model->from_wallet . ' (' . $model->from_payment_system . ')';
                        },
                    ],
                    [
                        'attribute' => 'to_wallet',
                        'format' => 'raw',
                        'header' => Yii::t('app', 'Need To Transfer Money Here'),
                        'value' => function($model) {
                            return $model->to_wallet . ' (' . $model->to_payment_system . ')';
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format' => 'date',
                        'value' => 'created_at',
                        'filter'    => DateRangePicker::widget([
                            'model'          => $searchModel,
                            'attribute'      => 'dateRange',
                            'convertFormat'  => true,
                            'pluginOptions'  => [
                                'timePicker' => true,
                                'locale' => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/bid/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            },
                            'delete' => function($url, $model) {
                                $customUrl = Url::to([
                                    'bid/delete',
                                    'id' => $model['id']
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                    'title' => \Yii::t('app', 'Delete'),
                                    'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                                ]);
                            },
                        ]
                    ]
                ]

            ])?>
        <?php Pjax::end()?>
    <?php Panel::end() ?>
</div>
