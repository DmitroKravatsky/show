<?php

use common\models\review\ReviewEntity;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;
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
$this->params['breadcrumbs']['title'] = $this->title;
?>

<?php if ($message = Yii::$app->session->getFlash('fail')): ?>
    <div class="alert alert-danger">
        <?= $message ?>
    </div>
<?php endif;?>

<?= Html::style('.collapse-link {margin-left: 46px;}') ?>
<div class="modal" tabindex="-1"  id="new-review-form" role="dialog" hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= Yii::t('app', 'New review') ?></h5>
            </div>
                <div class="x_content">
                    <div id="alerts"></div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"><?= Yii::t('app', 'Create new review'); ?></label>
                        <?php $form = ActiveForm::begin([
                            'action'  => 'create',
                        ]); ?>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <?= $form->field($newReviewModel, 'text', ['enableClientValidation' => true])->textarea([
                                'autofocus'   => true,
                                'placeholder' => Yii::t('app', 'Review text')
                            ])->label(false) ?>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('app', Yii::t('app', 'Save')), [
                    'class' => 'btn btn-primary',
                ]) ?>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
            </div>
            <?php $form = ActiveForm::end(); ?>
        </div>
    </div>
</div>
<div class="review-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Reviews'),
        'collapsable' => true,
    ]) ?>
        <?php Pjax::begin() ?>
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'filterUrl' => UrlHelper::getFilterUrl(),
                'dataProvider' => $dataProvider,
                'toolbar' =>  [
                    ['content' =>
                        Toolbar::createButtonWithProperties(
                            Url::to('/review/create'),
                            Yii::t('app', 'Create new review'),
                            ['id' => 'new-review-button']
                        )
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
