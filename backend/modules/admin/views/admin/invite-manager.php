<?php

/* @var $this yii\web\View */

use backend\modules\authorization\models\RegistrationForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yiister\gentelella\widgets\Panel;

$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <?php Panel::begin([
            'header' => Yii::t('app', 'New manager creation form'),
            'collapsable' => true,
        ]) ?>
            <div class="x_content">
                <br>
                    <?php $formRegistration = ActiveForm::begin(['action' => ['/invite-manager'],
                        'options' => ['class' => 'form-horizontal form-label-left'], 'method' => 'post']); ?>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">First Name <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                    <?= $formRegistration->field($modelRegistration, 'name')
                        ->textInput(['placeholder' => 'Имя пользователя'])
                        ->label(false); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Last Name <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'last_name')
                        ->textInput(['placeholder' => 'Фамилия пользователя'])
                        ->label(false); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Email <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'email')
                            ->textInput(['placeholder' => 'E-mail'])
                            ->label(false); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Phone number <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'phone_number')
                            ->textInput(['placeholder' => 'Phone number'])
                            ->label(false); ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Password <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'password')
                            ->passwordInput(['placeholder' => 'Пароль'])
                            ->label(false) ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12"> Password again <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?= $formRegistration->field($modelRegistration, 'confirm_password')
                            ->passwordInput(['placeholder' => 'Повторите пароль'])
                            ->label(false) ?>
                    </div>
                </div>

                <div class="ln_solid"></div>
                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <button id="reset" class="btn btn-primary" type="reset">Reset</button>
                        <?= Html::submitButton('Registry', [
                            'class' => 'btn btn-success',
                            'name'  => 'registration-button'
                        ]); ?>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        <?php Panel::end() ?>
    </div>
</div>

