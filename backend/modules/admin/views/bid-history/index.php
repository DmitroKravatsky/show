<?php

use yiister\gentelella\widgets\Panel;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use common\models\bidHistory\BidHistory;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;
use common\helpers\UrlHelper;
use common\helpers\Toolbar;

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\bidHistory\BidHistorySearch $searchModel */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids History'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bid-history-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Bids History'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
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
                        'attribute' => 'created_by',
                        'label' => Yii::t('app', 'Created By'),
                        'format' => 'html',
                        'value' => function (BidHistory $bidHistory) {
                            return Html::a(
                                $bidHistory->bid->author->fullName ?? null,
                                Url::to(['bid/view', 'id' => $bidHistory->bid_id])
                            );
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'filter' => BidHistory::statusLabels(),
                        'value' => function (BidHistory $bidHistory) {
                            return BidHistory::getStatusValue($bidHistory->status);
                        }
                    ],
                    [
                        'attribute' => 'processed_by',
                        'value' => function (BidHistory $bidHistory) {
                            return $bidHistory->processedBy->fullName ?? null;
                        }
                    ],
                    [
                        'attribute' => 'time',
                        'format' => 'datetime',
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'time_range',
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'timePicker' => true,
                                'locale' => [
                                    'format' => 'Y-m-d H:i:s',
                                ]
                            ]
                        ]),
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>
