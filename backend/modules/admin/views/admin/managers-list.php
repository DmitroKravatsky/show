<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use common\models\user\User;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<?= Html::a('Invite new manager',['/invite-manager'], ['class' => 'btn btn-primary'])?>
<div class="site-index">
<?php Pjax::begin(); ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'name',
                    'value' => function (User $user) {
                        return $user->profile->name ?? null;
                    }
                ],
                [
                    'attribute' => 'last_name',
                    'value' => function (User $user) {
                        return $user->profile->last_name ?? null;
                    }
                ],
                'email',
                'phone_number',
                [
                    'attribute' => 'status',
                    'value' => function (User $user) {
                        return User::getInviteStatusValue($user->invite_code_status);
                    }
                ],
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'template' => '{delete} {reInvite}',
                    'buttons' => [
                        'delete' => function($url, User $model) {
                            $customUrl = \Yii::$app->urlManager->createUrl([
                                'admin/admin/delete-manager',
                                'user_id' => $model->id
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                'title' => Yii::t('app', 'lead-delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure?'),
                                ]);
                        },
                        'reInvite' => function($url, User $model) {
                            $reInviteUrl = \Yii::$app->urlManager->createUrl([
                                '/admin/admin/re-invite',
                                'user_id' => $model->id,
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-envelope"></span>', false, [
                                'reInviteUrl' => $reInviteUrl,
                                'title' => Yii::t('app', 'reInvite'),
                                'class' => 'ajaxReInviteMessage',
                                'method' => 'post'
                            ]);
                        }
                    ]
                ]

            ]

        ])
    ?>
<?php Pjax::end(); ?>
    <div id="loader">
    </div>
</div>
