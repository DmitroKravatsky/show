<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use common\models\paymentSystem\PaymentSystem;

/** @var \yii\web\View $this */
/** @var PaymentSystem $paymentSystem */

$this->title = Yii::t('app', 'Payment System') . ': ' . $paymentSystem->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Systems'), 'url' => ['index']];
$this->params['breadcrumbs']['title'] = $this->title;
?>

<div class="payment-system-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Payment System'),
            ]) ?>
            <?= DetailView::widget([
                'model' => $paymentSystem,
                'attributes' => [
                    'name',
                    [
                        'attribute' => 'currency',
                        'filter'    => PaymentSystem::currencyLabels(),
                        'value'     => function (PaymentSystem $paymentSystem) {
                            return PaymentSystem::getCurrencyValue($paymentSystem->currency);
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
