<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use common\models\review\ReviewEntity;

/** @var \yii\web\View $this */
/** @var ReviewEntity $review */

$this->title = Yii::t('app', 'Review') . ': ' . $review->id;
$this->params['breadcrumbs']['title'] = $this->title;
?>

<div class="notification-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Review'),
                'collapsable' => true,
                'removable' => true,
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
