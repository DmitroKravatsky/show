<?php

use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use common\models\userNotifications\UserNotificationsEntity;

/* @var $this yii\web\View */
/* @var $passwordUpdateModel \backend\modules\authorization\models\RegistrationForm */
/* @var $bidSearch \common\models\bid\BidSearch */
/* @var $bidProvider ActiveDataProvider */
/* @var $reviewSearch \common\models\review\ReviewSearch */
/* @var $reviewProvider ActiveDataProvider */
/* @var $userSearch \common\models\user\UserSearch */
/* @var $userProvider ActiveDataProvider */
/* @var $notificationsSearch UserNotificationsEntity */
/* @var $notificationsProvider ActiveDataProvider */
/* @var $countBids integer */
/* @var $countManagers integer */
/* @var $countReviews integer */
/* @var $countNotifications integer */

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
<?php else: ?>
    <div class="site-index">
        <div class="body-content">
            <div class="row">
                <?= $this->render('_stats-tile', [
                    'countBids'          => $countBids,
                    'countManagers'      => $countManagers,
                    'countReviews'       => $countReviews,
                    'countNotifications' => $countNotifications,
                ]) ?>
            </div>

            <div class="row">
                <?= $this->render('_panel', [
                    'bidSearch'             => $bidSearch,
                    'bidProvider'           => $bidProvider,
                    'reviewSearch'          => $reviewSearch,
                    'reviewProvider'        => $reviewProvider,
                    'userSearch'            => $userSearch,
                    'userProvider'          => $userProvider,
                    'notificationsSearch'   => $notificationsSearch,
                    'notificationsProvider' => $notificationsProvider,
                ]) ?>
            </div>
        </div>
    </div>
<?php endif; ?>
