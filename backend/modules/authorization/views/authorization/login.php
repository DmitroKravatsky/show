<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<?php //echo Yii::$app->session->has('Enter_failed'); exit;?>

<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Please fill out the following fields to login:</p>
    <?php if(Yii::$app->session->has('Enter_failed')): ?>
        <div class="alert alert-danger" role="alert"><?php echo Yii::$app->session->getFlash('Enter_failed')?></div>
    <?php endif;?>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($modelLogin, 'phone_number')->textInput(['autofocus' => true]) ?>

                <?= $form->field($modelLogin, 'password')->passwordInput() ?>

                <?= $form->field($modelLogin, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
