<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $modelLogin \backend\modules\authorization\models\LoginForm */

use backend\modules\authorization\models\RegistrationForm;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php if(Yii::$app->session->has('Enter_failed')): ?>
        <div class="alert alert-danger" role="alert"><?php echo Yii::$app->session->getFlash('Enter_failed')?></div>
    <?php endif;?>

<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <?php $form = ActiveForm::begin(['id' => 'login-form',
                    'options' => ['class' => 'form-signin']
                    ]); ?>
                    <h1>Admin Panel</h1>
                    <div>
                        <?= $form->field($modelLogin, 'phone_number')->textInput([
                            'autofocus' => true , 'class' => 'form-control', 'id'=>"inputEmail",  'placeholder' => "Enter Your Phone Number"
                            ])->label(false) ?>
                    </div>
                    <div>
                        <?= $form->field($modelLogin, 'password')->passwordInput([
                            'class' => 'form-control', 'id'=>"inputPassword", 'placeholder' => "Enter Your Password"
                            ])->label(false) ?>
                    </div>
                    <div>
                        <?= $form->field($modelLogin, 'rememberMe')->checkbox()->label("Remember me", ['class' => "checkbox-inline", "style"=>"padding-left: 0px;"]) ?>
                        <div class="clearfix"></div>
                        <?= Html::submitButton('Login', ['class' => 'btn btn-default submit', 'name' => 'login-button']) ?>
                    </div>

                    <div class="clearfix"></div>

                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>


<!--<div class="card card-container">
        <div style="text-align: center" ><h2>Admin panel</h2></div>
        <?php /*$form = ActiveForm::begin(['id' => 'login-form',
                'options' => ['class' => 'form-signin']
            ]); */?>
        <span id="reauth-email" class="reauth-email"></span>
        <?/*= $form->field($modelLogin, 'phone_number')->textInput([
                            'autofocus' => true , 'class' => 'form-control', 'id'=>"inputEmail",  'placeholder' => "Enter Your Phone Number"
                    ])->label(false) */?>
        <?/*= $form->field($modelLogin, 'password')->passwordInput([
                            'class' => 'form-control', 'id'=>"inputPassword", 'placeholder' => "Enter Your Password"
                    ])->label(false) */?><div id="remember" class="checkbox">
        <?/*= $form->field($modelLogin, 'rememberMe')->checkbox(['class' => "checkbox" ])->label("Remember me") */?>
        <?/*= Html::submitButton('Login', ['class' => 'btn btn-lg btn-primary btn-block btn-signin', 'name' => 'login-button']) */?>
        <?php /*ActiveForm::end(); */?>
</div><!-- /card-container -->
