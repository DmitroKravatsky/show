<?php

use common\models\user\User;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
use common\helpers\UrlHelper;
use common\helpers\Toolbar;
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\web\View;

/* @var $this yii\web\View */
/* @var \common\models\user\UserSearch $searchModel */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>

<?php $this->registerJs('var language = "' . Yii::$app->language . '"', View::POS_HEAD) ?>

<div id="user-status-error"></div>
<div id="user-status-success"></div>

<div class="user-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Users'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'dataProvider' => $dataProvider,
                'toolbar' =>  [
                    ['content' =>
                        Toolbar::resetButton()
                    ],
                    '{export}',
                    '{toggleData}',
                ],
                'export' => [
                    'fontAwesome' => true
                ],
                'panel' => [
                    'type' => GridView::TYPE_DEFAULT,
                    'heading' => '<i class="glyphicon glyphicon-list"></i>&nbsp;' . Yii::t('app', 'List')
                ],
                'columns' => [
                    [
                        'class' => 'kartik\grid\SerialColumn',
                        'contentOptions' => ['class' => 'kartik-sheet-style'],
                        'width' => '36px',
                        'header' => '',
                        'headerOptions' => ['class' => 'kartik-sheet-style']
                    ],
                    [
                        'attribute' => 'full_name',
                        'label' => Yii::t('app', 'Full Name'),
                        'value' => function (User $user) {
                            if (isset($user->fullName) && !empty(trim($user->fullName))) {
                                return $user->fullName;
                            }
                            return null;
                        },
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
                    'email:email:E-mail',
                    'phone_number:raw:' . Yii::t('app', 'Phone Number'),
                    [
                        'attribute' => 'created_at',
                        'label' => Yii::t('app', 'Created At'),
                        'format' => 'date',
                        'filter' => DateRangePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'dateRange',
                            'convertFormat' => true,
                            'pluginOptions' => [
                                'timePicker' => true,
                                'locale' => [
                                    'format' => 'Y-m-d',
                                ]
                            ]
                        ]),
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{view} {bid-list}',
                        'buttons' => [
                            'view' => function($url, User $user) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/user/view/', 'id' => $user->id]),
                                    ['title' => Yii::t('app', 'View'), 'onclick' => 'location.reload()']
                                );
                            },
                            'bid-list' => function($url, User $user) {
                                $url = Url::to(['/bid/index', 'BidSearch' => ['created_by' => $user->id]]);
                                return Html::a('<span class="glyphicon glyphicon-list"></span>', $url, [
                                    'title' => Yii::t('app', 'Bids'), 'onclick' => 'location.reload()'
                                ]);
                            },
                        ]
                    ]
                ]
            ]) ?>
        <?php Pjax::end(); ?>
    <?php Panel::end() ?>

    <div id="loader"></div>
</div>
