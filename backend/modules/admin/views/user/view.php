<?php

use yiister\gentelella\widgets\Panel;
use yii\{ widgets\DetailView, helpers\Url, widgets\Pjax, helpers\Html, web\View };
use backend\models\BackendUser;
use kartik\{ grid\GridView, select2\Select2 };
use common\helpers\UrlHelper;
use common\models\{ bid\BidEntity as Bid, paymentSystem\PaymentSystem };
use common\models\user\User;

/** @var \yii\web\View $this */
/** @var BackendUser $user */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\bid\BidSearch */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->title = Yii::t('app', 'User') . ': ' . $user->profile->getUserFullName() ?? null;
$this->params['breadcrumbs']['title'] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;} td span {line-height: 20px}') ?>

<?php $this->registerJs('var language = "' . Yii::$app->language . '"; addEventListener("popstate",function(e){location.reload();},false);', View::POS_HEAD) ?>

<div class="manager-view">
    <div class="row">
        <div id="user-status-error"></div>
        <div id="user-status-success"></div>

        <?php Panel::begin([
            'header' => Yii::t('app', 'User'),
            'collapsable' => true,
        ]) ?>
            <?= DetailView::widget([
                'model' => $user,
                'template'   => '<tr data-key="' . $user->id . '"><th{captionOptions}>{label}</th><td{contentOptions}>{value}</td></tr>',
                'attributes' => [
                    [
                        'attribute' => 'avatar',
                        'label' => Yii::t('app', 'Avatar'),
                        'format' => 'html',
                        'value' => function (BackendUser $user) {
                            return (isset($user->profile)) && $user->profile->avatar !== null
                                ? Html::img($user->profile->getImageUrl(), ['style' => 'height:150px;'])
                                : Html::img(Yii::getAlias('@image.default.user.avatar'), ['style' => 'height:150px;']);
                        }
                    ],
                    'email:email:E-mail',
                    'phone_number',
                    [
                        'attribute' => 'sourse',
                        'label' => Yii::t('app', 'Registration Method'),
                        'value' => function (BackendUser $user) {
                            return BackendUser::getRegistrationMethodLabel($user->source);
                        }
                    ],
                    [
                        'attribute' => 'full_name',
                        'label' => Yii::t('app', 'Full Name'),
                        'value' => function (BackendUser $user) {
                            return $user->profile->getUserFullName() ?? null;
                        }
                    ],
                    [
                        'attribute' => 'status',
                        'label' => Yii::t('app', 'Status'),
                        'filter' => User::statusLabels(),
                        'value' => function (User $user) {
                            return User::getStatusValue($user->status);
                        },
                        'contentOptions' => ['class' => 'status-column'],
                    ],
                    [
                        'attribute' => 'change_status',
                        'label' => Yii::t('app', 'Change Status'),
                        'value' => function (User $user) {
                            $statuses = User::getManagerAllowedToUpdateStatusesWithOutCurrentStatus($user->status);
                            if (Yii::$app->user->can(User::ROLE_ADMIN)) {
                                $statuses = User::getAllUserStatusesWithOutCurrentStatus($user->status);
                            }
                            return Select2::widget([
                                'name' => 'status',
                                'data' => $statuses,
                                'options' => [
                                    'class' => 'user-status',
                                    'disabled' => !User::canUpdateStatus($user->status),
                                    'placeholder' => Yii::t('app', 'Select status'),
                                ],
                            ]);
                        },
                        'format' => 'raw',
                        'filter' => User::statusLabels(),
                    ],
                    'created_at:datetime:' . Yii::t('app', 'Created At'),
                ],
            ]) ?>
        <?php Panel::end() ?>

        <hr>

        <div id="bid-status-error"></div>
        <div id="bid-status-success"></div>

        <?php Panel::begin([
            'header' => Yii::t('app', 'User bids'),
            'collapsable' => true,
        ]) ?>
            <?php Pjax::begin()?>
                <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'filterUrl'    => UrlHelper::getFilterUrl(),
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
                'rowOptions'   => function (Bid $bid) {
                    return $bid->status === Bid::STATUS_NEW ? ['class' => 'success'] : [];
                },
                'columns'      => [
                    [
                        'class'          => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width'          => '36px',
                        'header'         => '',
                        'headerOptions'  => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'attribute'      => 'status',
                        'filter'         => false,
                        'value'          => function (Bid $bid) {
                            return Bid::getStatusValue($bid->status);
                        },
                        'contentOptions' => ['class' => 'status-column'],
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
                                    'disabled'    => !Bid::canUpdateStatus($bid),
                                    'placeholder' => Yii::t('app', 'Select status'),
                                ],
                            ]);
                        },
                        'format'          => 'raw',
                        'filter'          => false,
                    ],
                    [
                        'attribute'      => 'processed',
                        'label'          => Yii::t('app', 'Bid Closed'),
                        'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                        'format'         => 'raw',
                        'filter'         => false,
                        'value'          => 'processedStatus',
                        'contentOptions' => ['class' => 'processed-column'],
                    ],
                    [
                        'attribute'      => 'processed_by',
                        'filter'         => false,
                        'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                        'format'         => 'raw',
                        'value'          => function (Bid $bid) {
                            return Html::a($bid->perfomer->fullName ?? null, [Url::to('/admin/profile/view'), 'id' => $bid->processed_by]);
                        },
                        'contentOptions' => ['class' => 'processed-by-column'],
                    ],
                    [
                        'attribute'      => 'in_progress_by_manager',
                        'filter'         => false,
                        'visible'        => Yii::$app->user->can(BackendUser::ROLE_ADMIN),
                        'value'          => function (Bid $bid) {
                            return $bid->inProgressByManager->fullName ?? null;
                        },
                        'contentOptions' => ['class' => 'in-progress-by-column'],
                    ],
                    [
                        'attribute' => 'from_sum',
                        'filter'    => false,
                        'format'    => 'raw',
                        'header'    => Yii::t('app', 'Amount From Customer'),
                        'value'     => function (Bid $bid) {
                            return $bid->from_sum . ' ' . PaymentSystem::getCurrencyValue($bid->fromPaymentSystem->currency);
                        },
                    ],
                    [
                        'attribute' => 'to_sum',
                        'filter'    => false,
                        'format'    => 'raw',
                        'header'    => Yii::t('app', 'Amount To Be Transferred'),
                        'value'     => function(Bid $bid) {
                            return $bid->to_sum . ' ' . PaymentSystem::getCurrencyValue($bid->toPaymentSystem->currency);
                        },
                    ],
                    [
                        'attribute' => 'created_at',
                        'format'    => 'date',
                        'value'     => 'created_at',
                        'filter'    => false,
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/bid/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View'), 'onclick' => 'location.reload()']
                                );
                            },
                            'delete' => function($url, $model) {
                                $customUrl = Url::to([
                                    'bid/delete',
                                    'id' => $model['id']
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                    'title' => \Yii::t('app', 'Delete'),
                                    'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
                                ]);
                            },
                        ]
                    ]
                ]

            ])?>
            <?php Pjax::end()?>
        <?php Panel::end() ?>
    </div>
    <div id="loader"></div>
</div>
