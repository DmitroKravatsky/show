<?php

use yiister\gentelella\widgets\Panel;
use common\models\{ bid\BidEntity as Bid, bidHistory\BidHistory, bidHistory\BidHistorySearch };
use yii\{
    helpers\Html, web\View, widgets\Pjax, widgets\DetailView, helpers\Url
};
use backend\models\BackendUser;
use kartik\{ grid\GridView, select2\Select2 };

/* @var $this yii\web\View */
/* @var $model Bid */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var BidHistorySearch $searchModel */

$this->title = Yii::t('app', 'Bid') . ' №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;} td span {line-height: 20px}') ?>

<?php $this->registerJs('var language = "' . Yii::$app->language . '";  addEventListener("popstate",function(e){location.reload();},false);', View::POS_HEAD) ?>

<div class="bid-entity-view">
    <div class="row">
        <div id="bid-status-error"></div>
        <div id="bid-status-success"></div>

        <?php Panel::begin([
            'header' => Yii::t('app', 'Bid') . ' №' . $model->id,
            'collapsable' => true,
        ]) ?>
            <?= DetailView::widget([
                'model'      => $model,
                'template'   => '<tr data-key="' . $model->id . '"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
                'attributes' => [
                    [
                        'attribute' => 'name',
                        'label'     => Yii::t('app', 'Client First Name'),
                        'value'     => function (Bid $bid) {
                            return $bid->author->profile->name;
                        }
                    ],
                    [
                        'attribute' => 'name',
                        'label'     => Yii::t('app', Yii::t('app', 'Client Last Name')),
                        'value'     => function (Bid $bid) {
                            return $bid->author->profile->last_name;
                        }
                    ],
                    [
                        'attribute' => 'email',
                        'label'     => 'E-mail',
                        'value'     => function (Bid $bid) {
                            return $bid->author->email;
                        }
                    ],
                    [
                        'attribute' => 'phone_number',
                        'value'     => function (Bid $bid) {
                            return $bid->author->phone_number;
                        }
                    ],
                    [
                        'attribute'     => 'status',
                        'filter'        => Bid::statusLabels(),
                        'value'         => function (Bid $bid) {
                            return Bid::getStatusValue($bid->status);
                        },
                        'contentOptions' => ['id' => 'status'],
                    ],
                    [
                        'attribute' => 'change_status',
                        'label'     => Yii::t('app', 'Change Status'),
                        'value'     => function (Bid $bid) {
                            return Select2::widget([
                                'name'    => 'status',
                                'data'    => Bid::getManagerAllowedStatusesWithOutCurrentStatus($bid->status),
                                'options' => [
                                    'class'       => 'status',
                                    'disabled'    => !Bid::canUpdateStatus($bid->status) || (Yii::$app->user->can(BackendUser::ROLE_MANAGER) && Bid::isProcessedByAnotherManager($bid)),
                                    'placeholder' => Yii::t('app', 'Select status')
                                ],
                            ]);
                        },
                        'format'    => 'raw',
                        'filter'    => Bid::statusLabels()
                    ],
                    [
                        'attribute'      => 'processed_by',
                        'filter'         => BackendUser::getManagerNames(),
                        'format'         => 'raw',
                        'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                        'value'          => function (Bid $bid) {
                            return Html::a($bid->perfomer->fullName ?? null,
                                Yii::$app->urlManager->createUrl("manager/view/{$bid->processed_by}")
                            );
                        },
                        'contentOptions' => ['id' => 'processed-by'],
                    ],
                    [
                        'attribute'      => 'in_progress_by_manager',
                        'filter'         => BackendUser::getManagerNames(),
                        'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                        'value'          => function (Bid $bid) {
                            return $bid->inProgressByManager->fullName ?? null;
                        },
                        'contentOptions' => ['id' => 'in-progress-by-column'],
                    ],
                    [
                        'attribute' => 'from_payment_system',
                        'label'     => Yii::t('app', 'Where Did The Money Come From'),
                        'value'     => function (Bid $bid) {
                            return $bid->fromPaymentSystem->name . ' ' . $bid->from_wallet;
                        }
                    ],
                    [
                        'attribute' => 'to_payment_system',
                        'label'     => Yii::t('app', 'Need To Transfer Money Here'),
                        'value'     => function (Bid $bid) {
                            return $bid->toPaymentSystem->name . ' ' . $bid->to_wallet;
                        }
                    ],
                    [
                        'attribute' => 'from_sum',
                        'label'     => Yii::t('app', 'Amount From Customer'),
                        'value'     => function (Bid $bid) {
                            return round($bid->from_sum, 2) . ' ' . $bid->fromPaymentSystem->currency;
                        }
                    ],
                    [
                        'attribute' => 'to_sum',
                        'label'     => Yii::t('app', 'Amount To Be Transferred'),
                        'value'     => function (Bid $bid) {
                            return round($bid->to_sum, 2) . ' ' . $bid->toPaymentSystem->currency;
                        }
                    ],
                    'created_at:datetime',
                    'updated_at:datetime',
                ],
            ]) ?>
        <?php Panel::end() ?>

        <hr>

        <?php Panel::begin([
            'header' => Yii::t('app', 'Logs'),
            'collapsable' => true,
        ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'hover'        => true,
                'toolbar'      =>  [
                    '{export}',
                    '{toggleData}',
                ],
                'export'       => [
                    'fontAwesome' => true
                ],
                'panel'        => [
                    'type'    => GridView::TYPE_DEFAULT,
                    'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                ],
                'columns'      => [
                    [
                        'class'          => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width'          => '36px',
                        'header'         => '',
                        'headerOptions'  => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'attribute' => 'status',
                        'filter'    => false,
                        'value'     => function (BidHistory $bidHistory) {
                            return BidHistory::getStatusValue($bidHistory->status);
                        }
                    ],
                    [
                        'attribute' => 'processed_by',
                        'filter'    => false,
                        'visible'   => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                        'format'    => 'raw',
                        'value'     => function (BidHistory $bidHistory) {
                            if (isset($bidHistory->processedBy)) {
                                return Html::a($bidHistory->processedBy->fullName, Url::to(['/manager/view', 'id' => $bidHistory->processedBy->id]), ['title' => Yii::t('yii', 'View'), 'onclick' => 'location.reload()']);
                            }
                            return null;
                        }
                    ],
                    [
                        'attribute'      => 'in_progress_by_manager',
                        'filter'         => false,
                        'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                        'format'    => 'raw',
                        'value'          => function (BidHistory $bidHistory) {
                            if (isset($bidHistory->inProgressByManager)) {
                                return Html::a($bidHistory->inProgressByManager->fullName, Url::to(['/manager/view', 'id' => $bidHistory->inProgressByManager->id]), ['title' => Yii::t('yii', 'View'), 'onclick' => 'location.reload()']);
                            }
                            return null;
                        },
                        'contentOptions' => ['class' => 'in-progress-by-column'],
                    ],
                    [
                        'attribute' => 'message',
                        'label' => Yii::t('app', 'Message'),
                        'value' => function (BidHistory $bidHistory) {
                            return BidHistory::getMessageByBid($bidHistory);
                        }
                    ],
                    [
                        'attribute' => 'time',
                        'filter'    => false,
                        'format'    => 'datetime',
                    ],
                    [
                        'attribute' => 'created_at',
                        'label'     => Yii::t('app', 'Bid Created At'),
                        'value'     => function (BidHistory $bidHistory) {
                            return Yii::$app->formatter->asDatetime($bidHistory->bid->created_at);
                        },
                    ],
                ],
            ]) ?>
            <?php Pjax::end() ?>
        <?php Panel::end() ?>
    </div>
    <div id="loader"></div>
</div>
