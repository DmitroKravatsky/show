<?php

use kartik\grid\GridView;
use yiister\gentelella\widgets\Panel;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use common\models\reserve\ReserveEntity as Reserve;
use common\helpers\UrlHelper;
use common\helpers\Toolbar;

/** @var \yii\web\View $this */
/** @var \common\models\reserve\ReserveEntitySearch $searchModel */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Reserves');
$this->params['breadcrumbs']['title'] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<div class="reserve-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Reserves'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'filterModel'  => $searchModel,
                'filterUrl'    => UrlHelper::getFilterUrl(),
                'dataProvider' => $dataProvider,
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
                'columns'      => [
                    [
                        'class'          => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width'          => '36px',
                        'header'         => '',
                        'headerOptions'  => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'attribute' => 'payment_system',
                        'filter' => Reserve::paymentSystemLabels(),
                        'value' => function (Reserve $reserve) {
                            return Reserve::getPaymentSystemValue($reserve->payment_system);
                        }
                    ],
                    [
                        'attribute' => 'currency',
                        'filter'    => Reserve::currencyLabels(),
                        'value'     => function (Reserve $reserve) {
                            return Reserve::getCurrencyValue($reserve->currency);
                        }
                    ],
                    [
                        'attribute'       => 'sum',
                        'class'           => 'kartik\grid\EditableColumn',
                        'value'           => function (Reserve $reserve) {
                            return round($reserve->sum, 2);
                        },
                        'editableOptions' => [
                            'header'    => Yii::t('app', 'Sum'),
                            'options'   => [
                                'pluginOptions' => ['min' => 0, 'max' => 100000]
                            ]
                        ],
                    ],
                    [
                        'attribute' => 'created_at',
                        'format'    => 'date',
                        'value'     => 'created_at',
                        'filter'    => DateRangePicker::widget([
                            'model'          => $searchModel,
                            'attribute'      => 'createdDateRange',
                            'convertFormat'  => true,
                            'pluginOptions'  => [
                                'timePicker' => true,
                                'locale'     => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                    [
                        'attribute' => 'created_at',
                        'format'    => 'date',
                        'value'     => 'created_at',
                        'filter'    => DateRangePicker::widget([
                            'model'          => $searchModel,
                            'attribute'      => 'updatedDateRange',
                            'convertFormat'  => true,
                            'pluginOptions'  => [
                                'timePicker' => true,
                                'locale'     => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                    [
                        'class'    => ActionColumn::class,
                        'template' => '{view} {update}',
                        'buttons'  => [
                            'view' => function ($url, Reserve $reserve) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/reserve/view/' . $reserve->id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            },
                            'update' => function ($url, Reserve $reserve) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    Url::to(['/reserve/update/' . $reserve->id]),
                                    ['title' => Yii::t('app', 'Edit')]
                                );
                            }
                        ],
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>
