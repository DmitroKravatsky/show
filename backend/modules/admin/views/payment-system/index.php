<?php

use kartik\grid\GridView;
use yiister\gentelella\widgets\Panel;
use kartik\daterange\DateRangePicker;
use yii\{ helpers\Html, widgets\Pjax, helpers\Url };
use yii\grid\ActionColumn;
use common\helpers\{ UrlHelper, Toolbar };
use common\models\paymentSystem\PaymentSystem;

/** @var \yii\web\View $this */
/** @var \common\models\paymentSystem\PaymentSystemSearch $searchModel */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Payment Systems');
$this->params['breadcrumbs']['title'] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<div class="reserve-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Payment Systems'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'filterModel'  => $searchModel,
                'filterUrl'    => UrlHelper::getFilterUrl(),
                'dataProvider' => $dataProvider,
                'toolbar'      =>  [
                    ['content' =>
                        Toolbar::createButton(Url::to('/payment-system/create'), Yii::t('app', 'Create Payment System')) .
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
                    'name',
                    [
                        'attribute' => 'payment_system_type',
                        'filter'    => PaymentSystem::paymentSystemTypeLabel(),
                        'value'     => function (PaymentSystem $reserve) {
                            return PaymentSystem::getPaymentSystemTypeValue($reserve->payment_system_type);
                        }
                    ],
                    [
                        'attribute' => 'currency',
                        'filter'    => PaymentSystem::currencyLabels(),
                        'value'     => function (PaymentSystem $reserve) {
                            return PaymentSystem::getCurrencyValue($reserve->currency);
                        }
                    ],
                    [
                        'attribute' => 'visible',
                        'filter'    => PaymentSystem::getVisibleStatuses(),
                        'value'     => function (PaymentSystem $paymentSystem) {
                            return PaymentSystem::getVisibleValue($paymentSystem->visible);
                        }
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
                    [
                        'class'    => ActionColumn::class,
                        'template' => '{view} {visible} {update}',
                        'buttons'  => [
                            'view' => function ($url, PaymentSystem $paymentSystem) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/payment-system/view/' . $paymentSystem->id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            },
                            'visible' => function ($url, PaymentSystem $paymentSystem) {
                                if ($paymentSystem->visible) {
                                    $options = ['title' => Yii::t('app', 'Invisible')];
                                    $iconClass = 'glyphicon-check';
                                } else {
                                    $options = ['title' => Yii::t('app', 'Visible')];
                                    $iconClass = 'glyphicon-unchecked';
                                }
                                return Html::a(
                                    '<span class="glyphicon ' . $iconClass . '"></span>',
                                    Url::to(['/payment-system/toggle-visible/' . $paymentSystem->id]),
                                    $options
                                );
                            },
                            'update' => function ($url, PaymentSystem $paymentSystem) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>',
                                    Url::to(['/payment-system/update/' . $paymentSystem->id]),
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
