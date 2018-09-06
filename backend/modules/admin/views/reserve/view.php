<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use common\models\reserve\ReserveEntity;

/** @var \yii\web\View $this */
/** @var ReserveEntity $reserve */

$this->title = Yii::t('app', 'Reserve') . ': ' . ReserveEntity::getPaymentSystemValue($reserve->payment_system);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reserves'), 'url' => ['index']];
$this->params['breadcrumbs']['title'] = $this->title;
?>

<div class="reserve-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Reserve'),
                'collapsable' => true,
                'removable' => true,
            ]) ?>
            <?= DetailView::widget([
                'model' => $reserve,
                'attributes' => [
                    [
                        'attribute' => 'payment_system',
                        'filter' => ReserveEntity::paymentSystemLabels(),
                        'value' => function (ReserveEntity $reserve) {
                            return ReserveEntity::getPaymentSystemValue($reserve->payment_system);
                        }
                    ],
                    [
                        'attribute' => 'currency',
                        'filter' => ReserveEntity::currencyLabels(),
                        'value' => function (ReserveEntity $reserve) {
                            return ReserveEntity::getCurrencyValue($reserve->currency);
                        }
                    ],
                    [
                        'attribute' => 'sum',
                        'value' => function (ReserveEntity $reserve) {
                            return round($reserve->sum, 2);
                        }
                    ],
                    'created_at:date',
                    'updated_at:date',
                ],
            ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
