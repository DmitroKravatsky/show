<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use common\models\review\ReviewEntity;

/** @var \yii\web\View $this */
/** @var ReviewEntity $review */

$this->title = Yii::t('app', 'Review') . ': ' . $review->id;
?>

<div class="notification-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Html::a('<i class="glyphicon glyphicon-chevron-left"></i>', Url::to('/admin/review/index'), [
                        'title' => Yii::t('app', 'Back'),
                        'class' => 'btn btn-light btn-lg',
                        'style' => 'margin-left:0'
                    ]) . Yii::t('app', 'Review'),
            ]) ?>
            <?= DetailView::widget([
                'model' => $review,
                'attributes' => [
                    [
                        'attribute' => 'created_by',
                        'value' => function (ReviewEntity $review) {
                            return $review->createdBy->profile->getUserFullName() ?? null;
                        }
                    ],
                    'text:ntext',
                    'created_at:date',
                    'updated_at:date',
                ],
            ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
