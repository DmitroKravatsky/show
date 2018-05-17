<?php
use backend\modules\authorization\models\RegistrationForm;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="modal" tabindex="-1"  id="password-reset" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Creation password </h5>
            </div>
            <div class="modal-body">
                <div class="col-xs-12">
                <?php $formRegistration = ActiveForm::begin(['action' => ['/invite-manager'],
                    'class' => 'col s12 regForm', 'method' => 'post']); ?>
                    <p>Hello <?= $passwordUpdateModel->last_name . '' . $passwordUpdateModel->name . '.' ?></p>
                    <p>You have been registred as a <?= $passwordUpdateModel->role . ''?> and get specific rights</p>
                    <p>In a terms of high security we advise you to change your password for <strong><?= $passwordUpdateModel->email . ''?> </strong>
                        account <?php ?></p>
                    <div class="input-field col s12">
                        <?= $formRegistration->field($passwordUpdateModel, 'password',
                            ['template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                            ->passwordInput(['placeholder' => 'Пароль'])
                            ->label(false) ?>
                    </div>
                    <div class="input-field col s12">
                        <?= $formRegistration->field($passwordUpdateModel, 'confirm_password',
                            ['template' => "{label}\n<i class=\"fa fa-lock fa-fw prefix\" 
                                    aria-hidden=\"true\"></i>\n{input}\n{hint}\n{error}"])
                            ->passwordInput(['placeholder' => 'Повторите пароль'])
                            ->label(false) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="site-index">

    <div class="jumbotron">
        <h1>ITS AN ADMIN PANEL</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>
            </div>
            <div class="col-lg-4">
                <h2>Heading</h2>

                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip
                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu
                    fugiat nulla pariatur.</p>

                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>
            </div>
        </div>

    </div>
</div>
