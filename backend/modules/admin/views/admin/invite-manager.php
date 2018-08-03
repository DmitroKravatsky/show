<?php

/* @var $this yii\web\View */

use backend\modules\authorization\models\RegistrationForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
            <div class="x_title">
                <h2>New manager registration form</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>
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
        </div>
    </div>
</div>
<!--<div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
<div class="row">
    <div class="col s12 m10 offset-m1 l6 offset-l3 z-depth-5 forms-box">

    <div id="registration" class="col s12">
    <?php /*$formRegistration = ActiveForm::begin(['action' => ['/invite-manager'],
        'class' => 'col s12 regForm', 'method' => 'post']); */?>
    <div class="row">
        <div class="input-field col s12">
            <?/*= $formRegistration->field($modelRegistration, 'role')->dropDownList([
                'null'     => 'Выберите роль',
                RegistrationForm::ROLE_ADMIN    => 'Админ',
                RegistrationForm::ROLE_MANAGER  => 'Менеджер'
            ])->label(false); */?>
        </div>
        <div class="input-field col s12">
            <?/*= $formRegistration->field($modelRegistration, 'name',
                ['template' => "{label}\n<i class=\"fa fa-user fa-fw prefix\"
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                ->textInput(['placeholder' => 'Имя пользователя'])
                ->label(false); */?>
        </div>
        <div class="input-field col s12">
            <?/*= $formRegistration->field($modelRegistration, 'last_name',
                ['template' => "{label}\n<i class=\"fa fa-user fa-fw prefix\"
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                ->textInput(['placeholder' => 'Фамилия пользователя'])
                ->label(false); */?>
        </div>
        <div class="input-field col s12">
            <?/*= $formRegistration->field($modelRegistration, 'email',
                ['template' => "{label}\n<i class=\"fa fa-envelope fa-fw prefix\"
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                ->textInput(['class' => 'validate', 'placeholder' => 'E-mail'])
                ->label(false); */?>
        </div>
        <div class="input-field col s12">
            <?/*= $formRegistration->field($modelRegistration, 'phone_number',
                ['template' => "{label}\n<i class=\"fa fa-envelope fa-fw prefix\"
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                ->textInput(['class' => 'validate', 'placeholder' => 'Phone number'])
                ->label(false); */?>
        </div>
        <div class="input-field col s12">
            <?/*= $formRegistration->field($modelRegistration, 'password',
                ['template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\"
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                ->passwordInput(['placeholder' => 'Пароль'])
                ->label(false) */?>
        </div>
        <div class="input-field col s12">
            <?/*= $formRegistration->field($modelRegistration, 'confirm_password',
                ['template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\"
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                ->passwordInput(['placeholder' => 'Повторите пароль'])
                ->label(false) */?>
        </div>

        <?/*= Html::submitButton('Зарегистрировать', [
            'class' => 'btn waves-effect waves-light green reg-btn',
            'name'  => 'registration-button'
        ]); */?>
    </div>
    <?php /*ActiveForm::end(); */?>
    </div>
    </div>
</div>-->
