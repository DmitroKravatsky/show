<?php

use yiister\gentelella\widgets\StatsTile;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \yii\web\View $this */
/* @var $countBids integer */
/* @var $countManagers integer */
/* @var $countReviews integer */
/* @var $countNotifications integer */
?>

<div class="col-xs-12 col-md-3">
    <?= StatsTile::widget(
        [
            'icon'   => 'list-alt',
            'header' => Yii::t('app', 'Bids'),
            'text'   => Html::a(Yii::t('app', 'View all'), Url::to(['bid/index']), ['title' => Yii::t('app', 'Bids')]),
            'number' => $countBids,
        ]
    ) ?>
</div>

<?php if (Yii::$app->user->can('admin')): ?>
    <div class="col-xs-12 col-md-3">
        <?= StatsTile::widget(
            [
                'icon'   => 'user',
                'header' => Yii::t('app', 'Managers'),
                'text'   => Html::a(Yii::t('app', 'View all'), Url::to(['/manager/index']), ['title' => Yii::t('app', 'Managers')]),
                'number' => $countManagers,
            ]
        ) ?>
    </div>
<?php endif; ?>

<div class="col-xs-12 col-md-3">
    <?= StatsTile::widget(
        [
            'icon'   => 'comments-o',
            'header' => Yii::t('app', 'Reviews'),
            'text'   => Html::a(Yii::t('app', 'View all'), Url::to(['review/index']), ['title' => Yii::t('app', 'Reviews')]),
            'number' => $countReviews,
        ]
    ) ?>
</div>

<div class="col-xs-12 col-md-3">
    <?= StatsTile::widget(
        [
            'icon'   => 'bell',
            'header' => Yii::t('app', 'Notifications'),
            'text'   => Html::a(Yii::t('app', 'View all'), Url::to(['notifications/index']), ['title' => Yii::t('app', 'Bids')]),
            'number' => $countNotifications,
        ]
    ) ?>
</div>

