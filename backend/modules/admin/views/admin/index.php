<?php

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yiister\gentelella\widgets\StatsTile;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\grid\GridView;
use yii\data\ActiveDataProvider;
use yiister\gentelella\widgets\Panel;
use common\models\user\User;

/* @var $this yii\web\View */
/* @var $passwordUpdateModel \backend\modules\authorization\models\RegistrationForm */
/* @var $bidSearch \common\models\bid\BidSearch */
/* @var $bidProvider ActiveDataProvider */
/* @var $reviewSearch \common\models\review\ReviewSearch */
/* @var $reviewProvider ActiveDataProvider */
/* @var $userSearch \common\models\user\UserSearch */
/* @var $userProvider ActiveDataProvider */
/* @var $countBids integer */
/* @var $countManagers integer */
/* @var $countReviews integer */

$this->title = Yii::t('app', 'My Yii Application');
?>

<?php if (isset($passwordUpdateModel)) : ?>
    <div class="modal" tabindex="-1"  id="password-reset" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Creation password </h5>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12">
                    <?php $formRegistration = ActiveForm::begin([
                        'action' => 'update-manager-password',
                        'id' => 'password-reset-form'
                    ]); ?>
                        <p>Hello <strong><?= \Yii::$app->user->identity->email ?></strong>. You have been registered and get specific rights</p>
                        <p>In a terms of high security we advise you to change your password for account</p>
                        <div class="input-field col s12">
                            <?= $formRegistration->field($passwordUpdateModel, 'password', [
                                'template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"
                            ])
                                ->passwordInput(['placeholder' => 'Пароль'])
                                ->label('Пароль') ?>
                        </div>

                        <div class="input-field col s12">
                            <?= $formRegistration->field($passwordUpdateModel, 'confirm_password', [
                                'template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"
                            ])
                                ->passwordInput(['placeholder' => 'Повторите пароль'])
                                ->label('Подтверждение пароля') ?>
                        </div>

                        <div class="result"></div>
                    </div>

                    <div class="modal-footer">
                        <input type="submit" id="submit" class="btn btn-primary">
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="site-index">
    <div class="body-content">
        <div class="row">
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

            <div class="col-xs-12 col-md-3">
                <?= StatsTile::widget(
                    [
                        'icon'   => 'user',
                        'header' => Yii::t('app', 'Managers'),
                        'text'   => Html::a(Yii::t('app', 'View all'), Url::to(['managers-list']), ['title' => Yii::t('app', 'Managers')]),
                        'number' => $countManagers,
                    ]
                ) ?>
            </div>

            <div class="col-xs-12 col-md-3">
                <?= StatsTile::widget(
                    [
                        'icon'   => 'comments-o',
                        'header' => Yii::t('app', 'Reviews'),
                        'text'   => Html::a(Yii::t('app', 'View all'), Url::to(['']), ['title' => Yii::t('app', 'Reviews')]),
                        'number' => $countReviews,
                    ]
                ) ?>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php Panel::begin([
                    'header' => Yii::t('app', 'Bids'),
                    'collapsable' => true,
                    'expandable' => true,
                    'removable' => true,
                ]) ?>
                    <?php Pjax::begin() ?>
                        <?= GridView::widget([
                            'dataProvider' => $bidProvider,
                            'filterModel' => $bidSearch,
                            'hover' => true,
                            'summary' => '',
                            'columns' => [
                                'id',
                                'email:email',
                                'status',
                                'created_at:datetime',
                            ],
                        ]) ?>
                    <?php Pjax::end() ?>
                <?php Panel::end() ?>
            </div>

            <div class="col-md-6">
                <?php Panel::begin([
                    'header' => Yii::t('app', 'Reviews'),
                    'collapsable' => true,
                    'expandable' => true,
                    'removable' => true,
                ]) ?>
                    <?php Pjax::begin() ?>
                        <?= GridView::widget([
                            'dataProvider' => $reviewProvider,
                            'filterModel' => $reviewSearch,
                            'hover' => true,
                            'summary' => '',
                            'columns' => [
                                'id',
                                'created_by',
                                'text:ntext',
                                'created_at:datetime',
                            ],
                        ]) ?>
                    <?php Pjax::end() ?>
                <?php Panel::end() ?>
            </div>

            <div class="clearfix"></div>

            <div class="col-md-6">
                <?php Panel::begin([
                    'header' => Yii::t('app', 'Managers'),
                    'collapsable' => true,
                    'removable' => true,
                ]) ?>
                    <?php Pjax::begin() ?>
                    <?= GridView::widget([
                        'dataProvider' => $userProvider,
                        'filterModel' => $userSearch,
                        'hover' => true,
                        'summary' => '',
                        'columns' => [
                            'id',
                            [
                                'attribute' => 'fullName',
                                'value' => function (User $user) {
                                    return $user->fullName;
                                }
                            ],
                            'created_at:datetime',
                        ],
                    ]) ?>
                    <?php Pjax::end() ?>
                <?php Panel::end() ?>
            </div>
        </div>
    </div>
</div>
