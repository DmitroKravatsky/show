<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Bid Entities';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php Pjax::begin(); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    /*'columns' => [
        'created_by',
        'from_payment_system',
        'to_payment_system',
        'from_wallet',
        'to_wallet',
        'from_currency',
        'to_currency',
        'from_sum',
        'to_sum', 'created_at',
        'updated_at',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{delete} {reInvite}',
            'buttons' => [
                'delete' => function($url, $model) {
                    $customUrl = \Yii::$app->urlManager->createUrl([
                        'admin/admin/delete-manager',
                        'user_id' => $model['user_id']
                    ]);
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                        'title' => Yii::t('app', 'lead-delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure?'),
                    ]);
                },
                'reInvite' => function($url, $model) {
                    $deleteUrl = \Yii::$app->urlManager->createUrl([
                        '/admin/admin/re-invite',
                        'user_id' => $model['user_id'],
                    ]);
                    return Html::a('<span class="glyphicon glyphicon-envelope"></span>', false, ['deleteUrl' => $deleteUrl,
                        'title' => Yii::t('app', 'reInvite'),
                        'class' => 'ajaxDelete',
                        'method' => 'post'
                    ]);
                }
            ]
        ]
    ]*/

])
?>
<?php Pjax::end(); ?>

