<?php
use common\models\user\User;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;

/* @var $this yii\web\View */
/* @var \common\models\user\UserSearch $searchModel */
/* @var \yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Managers');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::a(Yii::t('app', 'Invite new manager'), ['/invite-manager'], ['class' => 'btn btn-primary'])?>
<div class="site-index">
    <?php Panel::begin([
        'header' => Yii::t('app', 'Managers'),
        'collapsable' => true,
        'removable' => true,
    ]) ?>
        <?php Pjax::begin(); ?>
            <?= GridView::widget([
                'filterModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'attribute' => 'full_name',
                        'label' => Yii::t('app', 'Full Name'),
                        'value' => function (User $user) {
                            return ($user->profile->name . ' ' . $user->profile->last_name) ?? null;
                        }
                    ],
                    'email:email:E-mail',
                    'phone_number:raw:' . Yii::t('app', 'Phone Number'),
                    [
                        'attribute' => 'invite_code_status',
                        'label' => Yii::t('app', 'Invite Code Status'),
                        'filter' => User::getInviteStatuses(),
                        'value' => function (User $user) {
                            return User::getInviteStatusValue($user->invite_code_status);
                        }
                    ],
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'template' => '{delete} {reInvite}',
                        'buttons' => [
                            'delete' => function($url, User $model) {
                                $customUrl = Url::to([
                                    '/admin/admin/delete-manager',
                                    'user_id' => $model->id
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                    'title' => Yii::t('app', 'Delete'),
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    ]);
                            },
                            'reInvite' => function($url, User $model) {
                                $reInviteUrl = Url::to([
                                    '/admin/admin/re-invite',
                                    'user_id' => $model->id,
                                ]);
                                return Html::a('<span class="glyphicon glyphicon-envelope"></span>', false, [
                                    'reInviteUrl' => $reInviteUrl,
                                    'title' => Yii::t('app', 'Re-invite'),
                                    'class' => 'ajaxReInviteMessage',
                                    'method' => 'post'
                                ]);
                            }
                        ]
                    ]
                ]
            ]) ?>
        <?php Pjax::end(); ?>
    <?php Panel::end() ?>

    <div id="loader"></div>
</div>
