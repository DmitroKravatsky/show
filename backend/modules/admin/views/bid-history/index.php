<?php

use yiister\gentelella\widgets\Panel;
use yiister\gentelella\widgets\grid\GridView;
use yii\widgets\Pjax;
use common\models\bidHistory\BidHistory;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

/** @var \yii\web\View $this */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\bidHistory\BidHistorySearch $searchModel */
?>

<div class="bid-history-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Bid History'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'hover' => true,
                'columns' => [
                    'id',
                    [
                        'attribute' => 'bid',
                        'format' => 'html',
                        'value' => function (BidHistory $bidHistory) {
                            return Html::a($bidHistory->bid->id, Url::to(['bid/view', 'id' => $bidHistory->bid_id]));
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
                        'attribute' => 'time',
                        'format' => 'datetime',
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'name' => 'time',
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
