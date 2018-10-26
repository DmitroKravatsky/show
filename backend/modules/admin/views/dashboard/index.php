<?php

use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use common\models\userNotifications\UserNotificationsEntity;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $passwordUpdateModel \backend\modules\authorization\models\RegistrationForm */
/* @var $bidSearch \common\models\bid\PaymentSystemSearch */
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
                    <h5 class="modal-title"><?= Yii::t('app', 'Creation password') ?></h5>
                </div>

                <?php $formRegistration = ActiveForm::begin([
                    'action' => 'update-manager-password',
                    'id' => 'password-reset-form'
                ]); ?>
                    <div class="x_content">
                        <div class="col-xs-12">
                            <div class="input-field col-md-12">
                                <?= $formRegistration->field($passwordUpdateModel, 'password', [
                                    'template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"
                                ])
                                    ->passwordInput(['placeholder' => Yii::t('app', 'Password')])
                                    ->label(Yii::t('app', 'Password')) ?>
                            </div>

                            <div class="input-field col-md-12">
                                <?= $formRegistration->field($passwordUpdateModel, 'confirm_password', [
                                    'template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"
                                ])
                                    ->passwordInput(['placeholder' => Yii::t('app', 'Repeat Password')])
                                    ->label(Yii::t('app', 'Repeat Password')) ?>
                            </div>

                            <div class="result"></div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <?= Html::submitButton(Yii::t('app', Yii::t('app', 'Save')), [
                            'id' => 'submit',
                            'class' => 'btn btn-primary',
                        ]) ?>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                    </div>
                <?php ActiveForm::end(); ?>
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
