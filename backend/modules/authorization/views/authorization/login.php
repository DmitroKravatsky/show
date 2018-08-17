<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $modelLogin \backend\modules\authorization\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= \dmstr\widgets\Alert::widget() ?>

<div>
    <div class="login_wrapper">
        <div class="animate form login_form">
            <section class="login_content">
                <?php $form = ActiveForm::begin(['id' => 'login-form',
                    'options' => ['class' => 'form-signin']
                ]); ?>
                    <h1><?= Yii::t('app', 'Admin Panel') ?></h1>

                    <div>
                        <?= $form->field($modelLogin, 'email')->textInput([
                            'autofocus' => true,
                            'class' => 'form-control',
                            'id' => 'inputEmail',
                            'placeholder' => Yii::t('app', 'Enter Your E-mail Address')
                        ])->label(false) ?>
                    </div>

                    <div>

                        <?= $form->field($modelLogin, 'password')->passwordInput([
                            'class' => 'form-control',
                            'id' => 'inputPassword',
                            'placeholder' => Yii::t('app', 'Enter Your Password')
                        ])->label(false) ?>
                    </div>

                    <div>
                        <?= $form->field($modelLogin, 'rememberMe')->checkbox()->label(Yii::t('app', 'Remember me'), [
                                'class' => "checkbox-inline", "style"=>"padding-left: 0px;"
                        ]) ?>
                        <div class="clearfix"></div>
                        <?= Html::submitButton(Yii::t('app', 'Login'), [
                            'class' => 'btn btn-default submit',
                            'name' => 'login-button'
                        ]) ?>
                    </div>

                    <div class="clearfix"></div>

                <?php ActiveForm::end(); ?>
            </section>
        </div>
    </div>
</div>
