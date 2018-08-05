<?php

use yii\grid\GridView;
use yii\helpers\Html;
use common\models\bid\BidEntity;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
            'format' => 'raw'
        ],
        [
            'attribute' => 'created_by',
            'value' => function($model) {
                return $model->created_by;
            },
            'contentOptions' => ['style' => 'width:7%;'],
        ],
        [
            'attribute' => 'name',
            'contentOptions' => ['style' => 'width:10%;'],
        ],
        [
            'attribute' => 'last_name',
            'contentOptions' => ['style' => 'width:10%;'],
        ],
        [
            'attribute' => 'email',
        ],
        [
            'attribute' => 'phone_number',
        ],
        [
            'attribute' => 'updated_at',
            'value' => function($model) {
                return date('d.m.H', $model->updated_at);

            },
            'contentOptions' => ['style' => 'width:7%;'],
        ],
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{delete}',
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


