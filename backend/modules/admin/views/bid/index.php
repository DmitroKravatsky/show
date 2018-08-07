<?php

use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use common\models\bid\BidEntity;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \backend\modules\admin\models\BidEntitySearch */

$this->title = 'Bid Entities';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php if ($message = Yii::$app->session->getFlash('delete-success')): ?>
    <div class="alert alert-success">
        <?= $message ?>
    </div>
<?php endif;?>
<?php Pjax::begin()?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => \yii\grid\SerialColumn::class],
        [
            'attribute' => 'id',
            'contentOptions' => ['style' => 'width:7%;'],

        ],
        [
            'attribute' => 'status',
            'value' => function($model) {
                return Html::activeDropDownList($model, 'status', BidEntity::getAllAvailableStatuses(),
                    [
                        'class' => 'status',
                    ]
                );
            },
            'contentOptions' => ['style' => 'width:11%;'],
            'format' => 'raw',
            'filter' => BidEntity::getAllAvailableStatuses()
        ],
        [
            'attribute' => 'full_name',
            'format' => 'raw',
            'value' => function($model) {
                return $model->last_name . ' ' . $model->name;
            }
        ],
        'email:email',
        'phone_number',
        [
            'attribute' => 'updated_at',
            'format' => 'date',
            'value' => 'updated_at',
            'filter'    => DateRangePicker::widget([
                'model'          => $searchModel,
                'attribute'      => 'updated_at',
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
                'delete' => function($url, $model) {
                    $customUrl = Url::to([
                        'bid/delete',
                        'id' => $model['id']
                    ]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                        'title' => \Yii::t('app', 'lead-delete'),
                        'data-confirm' => \Yii::t('app', 'Are you sure?'),
                    ]);
                },
            ]
        ]
    ]

])?>
<?php Pjax::end()?>
<div id="loader">
</div>


