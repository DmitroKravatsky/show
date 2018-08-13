<?php

use common\models\review\ReviewEntity;
use yiister\gentelella\widgets\grid\GridView;
use yiister\gentelella\widgets\Panel;
use yii\widgets\Pjax;
use kartik\daterange\DateRangePicker;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use backend\models\BackendUser;

/** @var \yii\web\View $this */
/** @var \common\models\review\ReviewSearch $searchModel */
/** @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Reviews');
$this->params['breadcrumbs']['title'] = $this->title;
?>

<div class="review-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Reviews'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'created_by',
                        'filter' => BackendUser::getUsernames(),
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
                    [
                        'class' => ActionColumn::class,
                        'template' => '{view}',
                        'buttons' => [
                            'view' => function($url, $model) {
                                return Html::a(
                                    '<span class="glyphicon glyphicon-eye-open"></span>',
                                    Url::to(['/review/view/' . $model->id]),
                                    ['title' => Yii::t('app', 'View')]
                                );
                            }
                        ],
                    ],
                ],
            ]) ?>
        <?php Pjax::end() ?>
    <?php Panel::end() ?>
</div>
