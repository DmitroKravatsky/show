<?php

use yiister\gentelella\widgets\Panel;
use yii\widgets\DetailView;
use backend\models\BackendUser;
use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var BackendUser $manager */

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Managers'), 'url' => ['index']];
$this->title = Yii::t('app', 'Manager') . ': ' . $manager->id;
$this->params['breadcrumbs']['title'] = $this->title;
?>

<style>
    .collapse-link {
        margin-left: 46px;
    }
</style>

<div class="manager-view">
    <div class="row">
        <label class="control-label col-md-3 col-sm-3 col-xs-12">
        </label>
        <div class="col-md-6">
            <?php Panel::begin([
                'header' => Yii::t('app', 'Manager'),
                'collapsable' => true,
            ]) ?>
                <?= DetailView::widget([
                    'model' => $manager,
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
                        'source',
                        [
                            'attribute' => 'status_online',
                            'label' => Yii::t('app', 'Status Online'),
                            'value' => function (BackendUser $user) {
                                return BackendUser::getStatusOnlineValue($user->status_online);
                            }
                        ],
                        [
                            'attribute' => 'full_name',
                            'label' => Yii::t('app', 'Full Name'),
                            'value' => function (BackendUser $user) {
                                return $user->profile->getUserFullName() ?? null;
                            }
                        ],
                        'created_at:date:' . Yii::t('app', 'Created At'),
                        'last_login:datetime:' . Yii::t('app', 'Last Login'),
                    ],
                ]) ?>
            <?php Panel::end() ?>
        </div>
    </div>
</div>
