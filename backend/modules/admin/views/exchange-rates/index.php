<?php

use kartik\{ daterange\DateRangePicker, grid\GridView };
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\helpers\UrlHelper;
use yiister\gentelella\widgets\Panel;
use common\helpers\Toolbar;
use common\models\exchangeRates\ExchangeRates;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\exchangeRates\ExchangeRatesSearch */

$this->title = Yii::t('app', 'Exchange Rates');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::style('td span {line-height: 20px}') ?>


<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<div class="exchange-rates-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Exchange Rates'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin()?>
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
                'columns'      => [
                    [
                        'class'          => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width'          => '36px',
                        'header'         => '',
                        'headerOptions'  => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'attribute' => 'from_payment_system_id',
                        'value'     => function (ExchangeRates $exchangeRates) {
                            return $exchangeRates->fromPaymentSystem->name;
                        }
                    ],
                    [
                        'attribute' => 'to_payment_system_id',
                        'value'     => function (ExchangeRates $exchangeRates) {
                            return $exchangeRates->toPaymentSystem->name;
                        }
                    ],
                    [
                        'attribute'       => 'value',
                        'class'           => 'kartik\grid\EditableColumn',
                        'value'           => function (ExchangeRates $exchangeRates) {
                            return round($exchangeRates->value, 2);
                        },
                        'editableOptions' => [
                            'header'    => Yii::t('app', 'Value'),
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
