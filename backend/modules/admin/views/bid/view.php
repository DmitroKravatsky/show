<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var $model common\models\bid\BidEntity */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Bid Entities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-entity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Bids'),
                'collapsable' => true,
            ]) ?>
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'created_by',
                        'name',
                        'last_name',
                        'phone_number',
                        'email:email',
                        'status',
                        'from_payment_system',
                        'to_payment_system',
                        'from_wallet',
                        'to_wallet',
                        'from_currency',
                        'to_currency',
                        'from_sum',
                        'to_sum',
                        'created_at',
                        'updated_at',
                    ],
                ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
