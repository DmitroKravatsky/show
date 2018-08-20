<?php

use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;
use common\models\bid\BidEntity;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model BidEntity */

$this->title = Yii::t('app', 'View') . ': ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="bid-entity-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Bids'),
                'collapsable' => true,
            ]) ?>
                <?= DetailView::widget([
                    'model' => $model,
                    'template' => '<tr data-key="' . $model->id . '"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
                    'attributes' => [
                        [
                            'attribute' => 'name',
                            'label' => Yii::t('app', 'Client First Name')
                        ],
                        [
                            'attribute' => 'last_name',
                            'label' => Yii::t('app', 'Client Last Name')
                        ],
                        'phone_number',
                        'email:email',
                        [
                            'attribute' => 'status',
                            'format' => 'raw',
                            'label' => Yii::t('app', 'Bid Status'),
                            'value' => function (BidEntity $bid) {
                                return Html::activeDropDownList($bid, 'status', BidEntity::statusLabels(), ['class' => 'status']);
                            }
                        ],
                        [
                            'attribute' => 'from_payment_system',
                            'label' => Yii::t('app', 'Where Did The Money Come From'),
                            'value' => function (BidEntity $bid) {
                                return BidEntity::getPaymentSystemValue($bid->from_payment_system) . ' ' . $bid->from_wallet;
                            }
                        ],
                        [
                            'attribute' => 'to_payment_system',
                            'label' => Yii::t('app', 'Need To Transfer Money Here'),
                            'value' => function (BidEntity $bid) {
                                return BidEntity::getPaymentSystemValue($bid->to_payment_system) . ' ' . $bid->to_wallet;
                            }
                        ],
                        [
                            'attribute' => 'from_sum',
                            'label' => Yii::t('app', 'Amount From Customer'),
                            'value' => function (BidEntity $bid) {
                                return round($bid->from_sum, 2) . ' ' . $bid->from_currency;
                            }
                        ],
                        [
                            'attribute' => 'to_sum',
                            'label' => Yii::t('app', 'Amount To Be Transferred'),
                            'value' => function (BidEntity $bid) {
                                return round($bid->to_sum, 2) . ' ' . $bid->to_currency;
                            }
                        ],
                        'created_at:datetime',
                        'updated_at:datetime',
                    ],
                ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
