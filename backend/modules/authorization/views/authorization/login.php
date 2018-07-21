<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $modelLogin \backend\modules\authorization\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

    <?php if(Yii::$app->session->has('Enter_failed')): ?>
        <div class="alert alert-danger" role="alert"><?php echo Yii::$app->session->getFlash('Enter_failed')?></div>
    <?php endif;?>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100 p-t-190 p-b-30">
            <?php $form = ActiveForm::begin(['id' => 'login-form',
                'options' => ['class' => 'login100-form validate-form']
            ]); ?>

                <div class="wrap-input100 validate-input m-b-10" >
                    <?= $form->field($modelLogin, 'phone_number')->textInput([
                            'autofocus' => true , 'class' => 'input100', 'placeholder' => "Enter Your Phone Number"
                    ])->label(false) ?>
                    <span class="symbol-input100">
							<i class="fa fa-user"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input m-b-10">
                    <?= $form->field($modelLogin, 'password')->passwordInput([
                            'class' => 'input100', 'placeholder' => "Enter Your Password"
                    ])->label(false) ?>
                    <span class="symbol-input100">
							<i class="fa fa-lock"></i>
                    </span>
                </div>
                <div class="wrap-input100 validate-input m-b-10">
                    <?= $form->field($modelLogin, 'rememberMe')->checkbox()->label("remember me") ?>
                    <span class="symbol-input100">
                                <i class="fa fa-lock"></i>
                    </span>
                </div>
                <div class="container-login100-form-btn p-t-10">
                    <?= Html::submitButton('Login', ['class' => 'login100-form-btn', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
