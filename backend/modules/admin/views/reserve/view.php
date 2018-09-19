<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use common\models\reserve\ReserveEntity as Reserve;
use common\models\paymentSystem\PaymentSystem;

/** @var \yii\web\View $this */
/** @var Reserve $reserve */

$this->title = Yii::t('app', 'Reserve') . ': ' . $reserve->paymentSystem->name;
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
            ]) ?>
            <?= DetailView::widget([
                'model' => $reserve,
                'attributes' => [
                    [
                        'attribute' => 'payment_system',
                        'value'     => function (Reserve $reserve) {
                            return $reserve->paymentSystem->name;
                        }
                    ],
                    [
                        'attribute' => 'sum',
                        'value' => function (Reserve $reserve) {
                            return round($reserve->sum, 2);
                        }
                    ],
                    [
                        'attribute' => 'currency',
                        'filter'    => PaymentSystem::currencyLabels(),
                        'value'     => function (Reserve $reserve) {
                            return PaymentSystem::getCurrencyValue($reserve->paymentSystem->currency);
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
