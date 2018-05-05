<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use backend\modules\authorization\models\RegistrationForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="forms">
    <div class="container box">
        <a href="index.html" class="logo z-depth-3"></a>
        <div class="row">
            <div class="col s12 m10 offset-m1 l6 offset-l3 z-depth-5 forms-box">
                <div class="col s12">
                    <ul class="tabs">
                        <li class="tab col s6"><a href="#login">Авторизация</a></li>
                        <li class="tab col s6"><a href="#registration">Регистрация</a></li>
                    </ul>
                </div>
                <div id="login" class="col s12 ">
                    <?php $form = ActiveForm::begin(['action' =>['login'], 'method' => 'post']); ?>
                    <div class="row">
                        <div class="input-field">
                            <?= $form->field($modelLogin, 'email',
                                ['template' => "{label}\n<i class=\"fa fa-envelope fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                                ->textInput(['class' => 'validate', 'placeholder' => 'E-mail'])
                                ->label(false); ?>
                        </div>
                        <div class="input-field col s12">
                            <?= $form->field($modelLogin, 'password',
                                ['template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                                ->passwordInput(['placeholder' => 'Пароль'])->label(false); ?>
                            <?php if (Yii::$app->session->has('forbiddenIp')) : ?>
                                <div class="help-block"><?= Yii::$app->session->get('forbiddenIp')?></div>
                            <?php endif ?>
                        </div>
                        <div class="input-field col s12">
                            <?= Html::submitButton('Войти', [
                                'class' => 'btn waves-effect waves-light green log-btn',
                                'name' => 'login-button'
                            ]); ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div id="registration" class="col s12">
                    <?php $formRegistration = ActiveForm::begin(['action' => ['registration'],
                        'class' => 'col s12 regForm', 'method' => 'post']); ?>
                    <div class="row">
                        <div class="input-field col s12">
                            <?= $formRegistration->field($modelRegistration, 'role')->dropDownList([
                                'null'     => 'Выберите роль',
                                RegistrationForm::ROLE_ADMIN    => 'Админ',
                                RegistrationForm::ROLE_MANAGER  => 'Менеджер'
                            ])->label(false); ?>
                        </div>
                        <div class="input-field col s12">
                            <?= $formRegistration->field($modelRegistration, 'name',
                                ['template' => "{label}\n<i class=\"fa fa-user fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                                ->textInput(['placeholder' => 'Имя пользователя'])
                                ->label(false); ?>
                        </div>
                        <div class="input-field col s12">
                            <?= $formRegistration->field($modelRegistration, 'last_name',
                                ['template' => "{label}\n<i class=\"fa fa-user fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                                ->textInput(['placeholder' => 'Имя пользователя'])
                                ->label(false); ?>
                        </div>
                        <div class="input-field col s12">
                            <?= $formRegistration->field($modelRegistration, 'email',
                                ['template' => "{label}\n<i class=\"fa fa-envelope fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                                ->textInput(['class' => 'validate', 'placeholder' => 'E-mail'])
                                ->label(false); ?>
                        </div>
                        <div class="input-field col s12">
                            <?= $formRegistration->field($modelRegistration, 'password',
                                ['template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                                ->passwordInput(['placeholder' => 'Пароль'])
                                ->label(false) ?>
                        </div>
                        <div class="input-field col s12">
                            <?= $formRegistration->field($modelRegistration, 'confirm_password',
                                ['template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                                ->passwordInput(['placeholder' => 'Повторите пароль'])
                                ->label(false) ?>
                        </div>

                        <?= Html::submitButton('Зарегистрироваться', [
                            'class' => 'btn waves-effect waves-light green reg-btn',
                            'name'  => 'registration-button'
                        ]); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</section>

