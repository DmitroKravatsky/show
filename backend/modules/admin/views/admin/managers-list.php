<?php
use backend\modules\authorization\models\RegistrationForm;
use yii\bootstrap\Modal;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<?= Html::a('Invite new manager',['/invite-manager'], ['class' => 'btn btn-primary'])?>
<div class="site-index">
<?php Pjax::begin(); ?>
    <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'name',
                'last_name',
                'email',
                'phone_number',
                [
                    'class' => \yii\grid\ActionColumn::class,
                    'template' => '{delete} {reInvite}',
                    'buttons' => [
                        'delete' => function($url, $model) {
                            $customUrl = \Yii::$app->urlManager->createUrl([
                                'admin/admin/delete-manager',
                                'user_id' => $model['user_id']
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>', $customUrl, [
                                'title' => Yii::t('app', 'lead-delete'),
                                'data-confirm' => Yii::t('yii', 'Are you sure?'),
                                ]);
                        },
                        'reInvite' => function($url, $model) {
                            $deleteUrl = \Yii::$app->urlManager->createUrl([
                                '/admin/admin/re-invite',
                                'user_id' => $model['user_id'],
                            ]);
                            return Html::a('<span class="glyphicon glyphicon-envelope"></span>', false, ['deleteUrl' => $deleteUrl,
                                'title' => Yii::t('app', 'reInvite'),
                                'class' => 'ajaxDelete',
                                'method' => 'post'
                            ]);
                        }
                    ]
                ]

            ]

        ])
    ?>
<?php Pjax::end(); ?>
</div>
