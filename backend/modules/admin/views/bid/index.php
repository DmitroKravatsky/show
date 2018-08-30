<?php

use kartik\daterange\DateRangePicker;
use yii\grid\GridView;
use yii\helpers\Html;
use common\models\bid\BidEntity;
use yii\helpers\Url;
use yii\widgets\Pjax;
use common\models\user\User;
use common\helpers\UrlHelper;

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
<?php Pjax::begin()?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'filterUrl' => UrlHelper::getFilterUrl(),
    'columns' => [
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
        'from_sum:raw:' . Yii::t('app', 'Amount From Customer'),
        'from_wallet:raw:' . Yii::t('app', 'Where Did The Money Come From'),
        'to_wallet:raw:' . Yii::t('app', 'Need To Transfer Money Here'),
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
<div id="loader">
</div>


