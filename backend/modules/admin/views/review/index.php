<?php

use common\models\review\ReviewEntity;
use common\models\user\User;
use kartik\grid\GridView;
use yiister\gentelella\widgets\Panel;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use common\helpers\{
    UrlHelper,
    Toolbar
};

/** @var \yii\web\View $this */
/** @var \common\models\review\ReviewSearch $searchModel */
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\review\ReviewEntity $newReviewModel */

$this->title = Yii::t('app', 'Reviews');
?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>
<div class="review-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Reviews'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin(['id' => 'pjax-container']) ?>
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'dataProvider' => $dataProvider,
                'toolbar' =>  [
                    ['content' =>
                        (Yii::$app->user->can(User::ROLE_ADMIN)) ? Toolbar::createButtonWithProperties(
                            Url::to('/review/create'),
                            ['id' => 'new-review-button', 'type' => 'button', 'title' => Yii::t('app', 'Create new review'), 'class' => 'btn btn-success']
                        ) . Toolbar::resetButton() : ''
                        . Toolbar::resetButton()
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
                        'class' => ActionColumn::class,
                        'template' => '{view} {visible} {delete}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/review/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View'), 'data-pjax' => 0]
                                );
                            },
                            'visible' => function ($url, ReviewEntity $review) {
                                $options = ['data-pjax' => 0];
                                if ($review->visible) {
                                    $options['title'] = Yii::t('app', 'Invisible');
                                    $iconClass = 'glyphicon-check';
                                } else {
                                    $options['title'] = Yii::t('app', 'Visible');
                                    $iconClass = 'glyphicon-unchecked';
                                }
                                return Html::a(
                                    '<span class="glyphicon ' . $iconClass . '"></span>',
                                    Url::to(['/review/toggle-visible/' . $review->id]),
                                    $options
                                );
                            },
                            'delete' => function($url, ReviewEntity $model) {
                                $customUrl = Url::to(['/review/delete', 'id' => $model->id]);

                                return Yii::$app->user->can(User::ROLE_ADMIN) ? Html::a(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    $customUrl,
                                    [
                                        'title' => Yii::t('app', 'Delete'),
                                        'data-message' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'class' => 'delete-button',
                                    ]
                                ) : '';
                            },
                        ],
                    ],
                    [
                        'attribute' => 'created_by',
                        'value' => function (ReviewEntity $review) {
                            return $review->createdBy->profile->getUserFullName() ?? null;
                        }
                    ],
                    [
                        'attribute' => 'text',
                        'value' => function (ReviewEntity $review) {
                            return Html::encode(StringHelper::truncate($review->text, 180));
                        }
                    ],
                    'name',
                    [
                        'attribute' => 'avatar',
                        'format' => ['image',['width'=>'100','height'=>'100']],
                        'value' => function (ReviewEntity $review) {
                             if ($review->avatar) {
                                 return $review->getImageUrl();
                             } else {
                                 return $review->createdBy->profile->getImageUrl();
                             }
                        }
                    ],
                    [
                        'attribute' => 'visible',
                        'filter'    => ReviewEntity::getVisibleStatuses(),
                        'value'     => function (ReviewEntity $review) {
                            return ReviewEntity::getVisibleValue($review->visible);
                        }
                    ],
                    [
                        'attribute' => 'created_at',
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
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
    <div id="loader"></div>
</div>
