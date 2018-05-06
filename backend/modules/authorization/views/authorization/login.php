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
                    </ul>
                </div>
                <div id="login" class="col s12 ">
                    <?php $form = ActiveForm::begin(['action' =>['login'], 'method' => 'post']); ?>
                    <div class="row">
                        <div class="input-field">
                            <?= $form->field($modelLogin, 'phone_number',
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
            </div>
        </div>
    </div>
</section>

