<?php

/* @var $this yii\web\View */
/** @var \backend\modules\authorization\models\RegistrationForm $modelRegistration */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;

$this->title = Yii::t('app', 'Invite Manager');
?>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php Panel::begin([
            'header' => Yii::t('app', 'New manager creation form'),
            'collapsable' => true,
        ]) ?>
            <?php $formRegistration = ActiveForm::begin([
                'action' => ['/invite-manager'],
                'options' => ['class' => 'form-horizontal form-label-left'],
                'method' => 'post'
            ]) ?>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        <?= Yii::t('app', 'First Name') ?><span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">

                    <?= $formRegistration->field($modelRegistration, 'name')
                        ->textInput(['placeholder' => Yii::t('app', 'Name')])
                        ->label(false)
                    ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        <?= Yii::t('app', 'Last Name') ?><span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'last_name')
                        ->textInput(['placeholder' => Yii::t('app', 'Last Name')])
                        ->label(false); ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        <?= Yii::t('app', 'Email') ?><span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'email', ['enableAjaxValidation' => true])
                            ->textInput(['placeholder' => Yii::t('app', 'E-mail')])
                            ->label(false)
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        <?= Yii::t('app', 'Phone number') ?><span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, Yii::t('app', 'phone_number'))
                            ->textInput(['placeholder' => Yii::t('app', '+79788765123')])
                            ->label(false)
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        <?= Yii::t('app', 'Password') ?><span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'password')
                            ->passwordInput(['placeholder' => Yii::t('app', 'Password')])
                            ->label(false)
                        ?>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">
                        <?= Yii::t('app', 'Password again') ?><span class="required">*</span>
                    </label>

                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'confirm_password')
                            ->passwordInput(['placeholder' => Yii::t('app', 'Confirm Password')])
                            ->label(false)
                        ?>
                    </div>
                </div>

                <div class="ln_solid"></div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button id="reset" class="btn btn-primary" type="reset"><?= Yii::t('app', 'Reset') ?></button>
                        <?= Html::submitButton('Registry', [
                            'class' => 'btn btn-success',
                            'name'  => 'registration-button'
                        ]); ?>
                    </div>
                </div>
            <?php ActiveForm::end(); ?>
        <?php Panel::end() ?>
    </div>
</div>
