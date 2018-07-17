<?php

use common\models\bid\BidEntity;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bid Entities';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php \yii\widgets\Pjax::begin()?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        [
            'attribute' => 'status',
            'value' => function($model, $key, $index, $column){
                return Html::activeDropDownList($model, 'status', BidEntity::getAllAvailableStatuses(),
                    [
                        'class' => 'status',
                    ]
                    );
            },
            'format' => 'raw'
        ],
        'created_by',
       /* 'from_payment_system',
        'to_payment_system',
        'from_wallet',
        'to_wallet',
        'from_currency',
        'to_currency',*/
        'from_sum',
        'to_sum',
        'created_at',
        'updated_at',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{delete}',
            'buttons' => [
                'delete' => function($url, $model) {
                    $customUrl = \Yii::$app->urlManager->createUrl([
                        'admin/bid/delete',
                        'id' => $model['id']
                    ]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                        'title' => \Yii::t('app', 'lead-delete'),
                        'data-confirm' => \Yii::t('yii', 'Are you sure?'),
                    ]);
                },
            ]
        ]
    ]

])?>
<?php \yii\widgets\Pjax::end()?>
<div id="loader">
</div>