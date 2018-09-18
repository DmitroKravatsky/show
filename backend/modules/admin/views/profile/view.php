<?php

use yiister\gentelella\widgets\Panel;
use common\models\{
    bid\BidEntity as Bid, bidHistory\BidHistory, bidHistory\BidHistorySearch, userProfile\UserProfileEntity
};
use yii\{ helpers\Html, web\View, widgets\Pjax, helpers\Url, widgets\DetailView };
use backend\models\BackendUser;
use kartik\{ grid\GridView, select2\Select2, daterange\DateRangePicker };

/* @var $this yii\web\View */
/* @var $profileModel UserProfileEntity */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var BidHistorySearch $searchModel */

$this->title = Yii::t('app', 'Bid') . ' №' . $profileModel->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids'), 'url' => ['index']];
?>

<?= Html::style('.collapse-link {margin-left: 46px;} td span {line-height: 20px}') ?>

<?php $this->registerJs('var language = "' . Yii::$app->language . '"', View::POS_HEAD) ?>

<div class="bid-entity-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>

            <div class="col-md-6">
                <div id="bid-status-error"></div>
                <div id="bid-status-success"></div>

                <?php Panel::begin([
                    'collapsable' => true,
                ]) ?>
                    <?= DetailView::widget([
                        'model'      => $profileModel,
                        'template'   => '<tr data-key="' . $profileModel->id . '"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
                        'attributes' => [
                            'name:raw:' . Yii::t('app', 'Client First Name'),
                            'last_name:raw:' . Yii::t('app', 'Client Last Name'),

                            'created_at:datetime',
                            'updated_at:datetime',
                        ],
                    ]) ?>
                <?php Panel::end() ?>

                <hr>
        </div>
    </div>
    <div id="loader"></div>
</div>
