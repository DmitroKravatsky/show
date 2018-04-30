<?php

use common\models\bid\BidEntity;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['breadcrumbs'][] = [
    'label' => 'Bid Entities',
    'url' => ['bid/index']
];
$this->params['breadcrumbs'][] = ['label' => 'Bid Details'];

?>
<section class="product">
    <div class="container">
        <div class="breadcrumb-box">
            <div class="nav-wrapper">
                <?php $form = ActiveForm::begin(); ?>

                <?php /** @var BidEntity $modelBid */?>
                <?=$form->field($modelBid, 'created_by')->textInput() ?>
                <?= $form->field($modelBid, 'name')->textInput() ?>
                <?= $form->field($modelBid, 'last_name')->textInput() ?>
                <?= $form->field($modelBid, 'email')->textInput() ?>
                <?= $form->field($modelBid, 'phone_number')->textInput() ?>
                <?= $form->field($modelBid, 'from_payment_system')->textInput() ?>
                <?= $form->field($modelBid, 'to_payment_system')->textInput() ?>
                <?= $form->field($modelBid, 'from_wallet')->textInput() ?>
                <?= $form->field($modelBid, 'to_wallet')->textInput() ?>
                <?= $form->field($modelBid, 'from_sum')->textInput() ?>
                <?= $form->field($modelBid, 'to_sum')->textInput() ?>

                <?php ActiveForm::end(); ?>

                <?php $form = ActiveForm::begin(['action' => ['bid/update-bid-status', 'id' => $modelBid->id], 'id' => 'update-status-form']); ?>
                <?= $form->field($modelBid, 'status')->radioList([
                        'paid'     => BidEntity::STATUS_PAID,
                        'accepted' => BidEntity::STATUS_ACCEPTED,
                        'done'     => BidEntity::STATUS_DONE,
                        'rejected' => BidEntity::STATUS_REJECTED,
                    ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('UpdateBidStatus', ['class' => 'btn submit', 'name' => 'update-status']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>
