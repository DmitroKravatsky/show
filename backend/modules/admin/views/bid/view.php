<?php

use yii\widgets\DetailView;
use yiister\gentelella\widgets\Panel;
use common\models\bid\BidEntity;
use yii\{ helpers\Html, web\View, widgets\Pjax, helpers\Url };
use backend\models\BackendUser;
use common\models\bidHistory\BidHistorySearch;
use kartik\{ grid\GridView, select2\Select2, daterange\DateRangePicker };
use common\helpers\UrlHelper;
use common\models\bidHistory\BidHistory;

/* @var $this yii\web\View */
/* @var $model BidEntity */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var BidHistorySearch $searchModel */

$this->title = Yii::t('app', 'Bid') . ' №' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Bids'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<?php $this->registerJs('var language = "' . Yii::$app->language . '"', View::POS_HEAD) ?>

<div class="bid-entity-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>

            <div class="col-md-6">
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
                            'name:raw:' . Yii::t('app', 'Client First Name'),
                            'last_name:raw:' . Yii::t('app', 'Client Last Name'),
                            'phone_number',
                            'email:email:E-mail',
                            [
                                'attribute'     => 'status',
                                'filter'        => BidEntity::statusLabels(),
                                'value'         => function (BidEntity $bid) {
                                    return BidEntity::getStatusValue($bid->status);
                                },
                                'contentOptions' => ['id' => 'status'],
                            ],
                            [
                                'attribute' => 'change_status',
                                'label'     => Yii::t('app', 'Change Status'),
                                'value'     => function (BidEntity $bid) {
                                    return Select2::widget([
                                        'name'    => 'status',
                                        'data'    => BidEntity::getManagerAllowedStatuses(),
                                        'options' => [
                                            'class'       => 'status',
                                            'disabled'    => !BidEntity::canUpdateStatus($bid->status),
                                            'placeholder' => Yii::t('app', 'Select status')
                                        ],
                                    ]);
                                },
                                'format'    => 'raw',
                                'filter'    => BidEntity::statusLabels()
                            ],
                            [
                                'attribute'      => 'processed_by',
                                'filter'         => BackendUser::getManagerNames(),
                                'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                                'value'          => function (BidEntity $bid) {
                                    return $bid->perfomer->fullName ?? null;
                                },
                                'contentOptions' => ['id' => 'processed-by'],
                            ],
                            [
                                'attribute'      => 'in_progress_by_manager',
                                'filter'         => BackendUser::getManagerNames(),
                                'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                                'value'          => function (BidEntity $bid) {
                                    return $bid->inProgressByManager->fullName ?? null;
                                },
                                'contentOptions' => ['id' => 'in-progress-by-column'],
                            ],
                            [
                                'attribute' => 'from_payment_system',
                                'label'     => Yii::t('app', 'Where Did The Money Come From'),
                                'value'     => function (BidEntity $bid) {
                                    return BidEntity::getPaymentSystemValue($bid->from_payment_system) . ' ' . $bid->from_wallet;
                                }
                            ],
                            [
                                'attribute' => 'to_payment_system',
                                'label'     => Yii::t('app', 'Need To Transfer Money Here'),
                                'value'     => function (BidEntity $bid) {
                                    return BidEntity::getPaymentSystemValue($bid->to_payment_system) . ' ' . $bid->to_wallet;
                                }
                            ],
                            [
                                'attribute' => 'from_sum',
                                'label'     => Yii::t('app', 'Amount From Customer'),
                                'value'     => function (BidEntity $bid) {
                                    return round($bid->from_sum, 2) . ' ' . $bid->from_currency;
                                }
                            ],
                            [
                                'attribute' => 'to_sum',
                                'label'     => Yii::t('app', 'Amount To Be Transferred'),
                                'value'     => function (BidEntity $bid) {
                                    return round($bid->to_sum, 2) . ' ' . $bid->to_currency;
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
                            'filterUrl'    => UrlHelper::getFilterUrl(),
                            'hover'        => true,
                            'toolbar'      =>  [
                                ['content' =>
                                    Html::a(
                                        '<i class="glyphicon glyphicon-repeat"></i>',
                                        Url::to(['/bid/view/' . $model->id]),
                                        ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')]
                                    ),
                                ],
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
                                    'attribute' => 'created_by',
                                    'label' => Yii::t('app', 'Created By'),
                                    'format' => 'html',
                                    'value' => function (BidHistory $bidHistory) {
                                        return $bidHistory->bid->author->fullName ?? null;
                                    }
                                ],
                                [
                                    'attribute' => 'status',
                                    'filter'    => BidHistory::statusLabels(),
                                    'value'     => function (BidHistory $bidHistory) {
                                        return BidHistory::getStatusValue($bidHistory->status);
                                    }
                                ],
                                [
                                    'attribute' => 'processed_by',
                                    'filter'    => BackendUser::getManagerNames(),
                                    'visible'   => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                                    'value'     => function (BidHistory $bidHistory) {
                                        return $bidHistory->processedByProfile->userFullName ?? null;
                                    }
                                ],
                                [
                                    'attribute'      => 'in_progress_by_manager',
                                    'filter'         => BackendUser::getManagerNames(),
                                    'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                                    'value'          => function (BidHistory $bidHistory) {
                                        return $bidHistory->inProgressByManager->fullName ?? null;
                                    },
                                    'contentOptions' => ['class' => 'in-progress-by-column'],
                                ],
                                [
                                    'attribute' => 'time',
                                    'format'    => 'datetime',
                                    'filter'    => DateRangePicker::widget([
                                        'model'          => $searchModel,
                                        'attribute'      => 'time_range',
                                        'convertFormat'  => true,
                                        'pluginOptions'  => [
                                            'timePicker' => true,
                                            'locale'     => [
                                                'format' => 'Y-m-d H:i:s',
                                            ]
                                        ]
                                    ]),
                                ],
                            ],
                        ]) ?>
                    <?php Pjax::end() ?>
                <?php Panel::end() ?>
        </div>
    </div>
    <div id="loader"></div>
</div>
