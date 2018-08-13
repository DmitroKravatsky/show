<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use backend\models\BackendUser;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var BackendUser $manager */

$this->title = Yii::t('app', 'Manager') . ': ' . $manager->id;
$this->params['breadcrumbs']['title'] = $this->title;
?>

<div class="manager-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Manager'),
                'collapsable' => true,
                'removable' => true,
            ]) ?>
                <?= DetailView::widget([
                    'model' => $manager,
                    'attributes' => [
                        [
                            'attribute' => 'avatar',
                            'format' => 'html',
                            'value' => function (BackendUser $user) {
                                return (isset($user->profile)) && $user->profile->avatar !== null
                                    ? Html::img($user->profile->getImageUrl(), ['style' => 'height:150px;'])
                                    : Html::img(Yii::getAlias('@image.default.user.avatar'), ['style' => 'height:150px;']);
                            }
                        ],
                        'email:email',
                        'phone_number',
                        'source',
                        [
                            'attribute' => 'full_name',
                            'value' => function (BackendUser $user) {
                                return $user->profile->getUserFullName() ?? null;
                            }
                        ],
                        'created_at:date',
                    ],
                ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
