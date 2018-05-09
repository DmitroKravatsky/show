<?php

/* @var $this yii\web\View */

use backend\modules\authorization\models\RegistrationForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'My Yii Application';
?>
<div class="row">
    <div class="col s12 m10 offset-m1 l6 offset-l3 z-depth-5 forms-box">

    <div id="registration" class="col s12">
    <?php $formRegistration = ActiveForm::begin(['action' => ['/invite-manager'],
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
                ->textInput(['placeholder' => 'Фамилия пользователя'])
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

        <?= Html::submitButton('Зарегистрировать', [
            'class' => 'btn waves-effect waves-light green reg-btn',
            'name'  => 'registration-button'
        ]); ?>
    </div>
    <?php ActiveForm::end(); ?>
    </div>
    </div>
</div>