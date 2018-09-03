<?php

/**
 * @var string $content
 * @var \yii\web\View $this
 */

use backend\assets\AppAsset;
use common\models\userNotifications\UserNotificationsEntity;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yiister\gentelella\widgets\Menu;
use common\models\user\User;
use yii\helpers\Url;
use dmstr\widgets\Alert;
use common\models\language\Language;

$bundle = yiister\gentelella\assets\Asset::register($this);
AppAsset::register($this);

$user = User::findOne(Yii::$app->user->id);
$this->title = Yii::t('app', 'Dashboard');
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta charset="<?= Yii::$app->charset ?>" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="nav-<?= !empty($_COOKIE['menuIsCollapsed']) && $_COOKIE['menuIsCollapsed'] == 'true' ? 'sm' : 'md' ?>" >
    <?php $this->beginBody(); ?>
        <div class="container body">
            <div class="main_container">
                <div class="col-md-3 left_col">
                    <div class="left_col scroll-view">
                        <div class="navbar nav_title">
                            <a href="<?= Url::to(['/index']) ?>" class="site_title">
                                <span><?= Yii::t('app', 'Dashboard') ?></span>
                            </a>
                        </div>

                        <div class="clearfix"></div>

                        <!-- menu prile quick info -->
                        <div class="profile">
                            <div class="profile_pic">
                                <?= isset($user->profile) && $user->profile->avatar !== null
                                    ? Html::img($user->profile->getImageUrl(), ['class' => 'img-circle profile_img', 'style' => 'height:56px;'])
                                    : Html::img(Yii::getAlias('@image.default.user.avatar'), ['class' => 'img-circle profile_img', 'style' => 'height:56px;'])
                                ?>
                            </div>

                            <div class="profile_info">
                                <span><?= Yii::t('app', 'Welcome') ?>,</span>
                                <h2><?= Html::encode($user->fullname ?? null) ?></h2>
                            </div>
                        </div>
                        <!-- /menu prile quick info -->

                        <br />

                        <!-- sidebar menu -->
                        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                            <div class="menu_section">
                                <h3><?= Yii::t('app', 'General') ?></h3>
                                <?= Menu::widget([
                                    'items' => [
                                        [
                                            'label' => Yii::t('app', 'Management'),
                                            'icon' => 'th',
                                            'url' => '#',
                                            'items' => [
                                                [
                                                    'label' => Yii::t('app', 'Managers'), 'url' => [Url::to('/manager/index')],
                                                    'visible' => Yii::$app->user->can(User::ROLE_ADMIN),
                                                ],
                                                [
                                                    'label' => Yii::t('app', 'Bids'), 'url' => '#',
                                                    'items' => [
                                                        [
                                                            'label' => Yii::t('app', 'List'), 'url' => ['/bid/index'],
                                                        ],
                                                        [
                                                            'label' => Yii::t('app', 'Bids History'), 'url' => ['/bid-history/index']
                                                        ],
                                                    ],
                                                ],
                                                [
                                                    'label' => Yii::t('app', 'Notifications'),
                                                    'url' => ['/notifications/index'],
                                                ],
                                                [
                                                    'label' => Yii::t('app', 'Reviews'),
                                                    'url' => ['/review/index'],
                                                ],
                                                [
                                                    'label' => Yii::t('app', 'Reserves'),
                                                    'url' => ['/reserve/index'],
                                                ],
                                            ],
                                        ],
                                    ],
                                ]) ?>
                            </div>

                        </div>
                        <!-- /sidebar menu -->

                        <!-- /menu footer buttons -->
                        <div class="sidebar-footer hidden-small">
                            <a data-toggle="tooltip" data-placement="top" title="Settings">
                                <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                                <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Lock">
                                <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                            </a>
                            <a data-toggle="tooltip" data-placement="top" title="Logout">
                                <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            </a>
                        </div>
                        <!-- /menu footer buttons -->
                    </div>
                </div>

                <!-- top navigation -->
                <div class="top_nav">
                    <ul class="nav_menu">
                        <nav class="" role="navigation">
                            <div class="nav toggle">
                                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                            </div>

                            <ul class="nav navbar-nav navbar-right">
                                <?= \common\widgets\LanguageChoice::widget([
                                    'languages' => Language::getVisibleList(),
                                    'url' => '/site/toggle-language',
                                ]) ?>

                                <li class="">
                                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <?= isset($user->profile) && $user->profile->avatar !== null
                                            ? Html::img($user->profile->getImageUrl())
                                            : Html::img(Yii::getAlias('@image.default.user.avatar'))
                                        ?>

                                        <?= Html::encode($user->fullname ?? null) ?>
                                        <span class="fa fa-angle-down"></span>
                                    </a>

                                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                                        <li><?= Html::a(Yii::t('app', 'Profile'), Url::to(['profile/index'])) ?></li>

                                        <li>
                                            <a href="<?= Url::to('/admin/logout') ?>"><i class="fa fa-sign-out pull-right"></i><?= Yii::t('app', 'Log Out') ?></a>
                                        </li>
                                    </ul>
                                </li>

                                <?php $notifications = UserNotificationsEntity::getUnreadUserNotifications(3) ?>

                                <li role="presentation" class="dropdown">
                                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fa fa-bell-o"></i>
                                        <span class="badge bg-green"><?= UserNotificationsEntity::getCountUnreadNotificationsByRecipient() ?></span>
                                    </a>

                                    <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                                        <?php if (empty($notifications)): ?>
                                            <li>
                                                <a>
                                                <span class="message">
                                                    <?= Yii::t('app', 'There are new notifications') ?>
                                                </span>
                                                </a>
                                            </li>
                                        <?php else:?>
                                            <?php foreach ($notifications as $notification): ?>
                                                <li>
                                                    <a href="<?= Url::to(["/notification/view/{$notification->id}"])?>">
                                                        <span class="image">
                                                            <img src="http://placehold.it/128x128" alt="Profile Image" />
                                                        </span>

                                                        <span>
                                                            <span class="name">
                                                                <?= $notification->userProfile->getUserFullName() ?? null; ?>
                                                            </span>
                                                            <span class="time"><?= date('d-m-y h:m', $notification->created_at) ?></span>
                                                        </span>

                                                        <span class="message">
                                                            <?php if ($notification->type == UserNotificationsEntity::TYPE_NEW_USER): ?>
                                                                <?= Yii::t('app', $notification->text, [
                                                                    'phone_number' => $notification->custom_data->phone_number ?? null
                                                                ]) ?>
                                                            <?php elseif ($notification->type == UserNotificationsEntity::TYPE_NEW_BID): ?>
                                                                <?= Yii::t('app', $notification->text, [
                                                                    'sum'      => $notification->custom_data->sum ?? null,
                                                                    'currency' => $notification->custom_data->currency ?? null,
                                                                    'wallet'   => $notification->custom_data->wallet ?? null,
                                                                ]) ?>
                                                            <?php endif; ?>
                                                        </span>
                                                    </a>
                                                </li>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        <li>
                                            <div class="text-center">
                                                <a href="<?= Url::to(['/notifications/index']) ?>">
                                                    <strong><?= Yii::t('app', 'See All Alerts') ?></strong>
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                <!-- /top navigation -->

                <!-- page content -->
                <div class="right_col" role="main">
                    <?php if (isset($this->params['h1'])): ?>
                        <div class="page-title">
                            <div class="title_left">
                                <h1><?= $this->params['h1'] ?></h1>
                            </div>
                            <div class="title_right">
                                <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Search for...">
                                        <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">Go!</button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="clearfix"></div>

                    <?= Breadcrumbs::widget([
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Url::to(['/index']),
                        ],
                        'links' => $this->params['breadcrumbs'] ?? [],
                    ]) ?>
                    <?= Alert::widget() ?>

                    <?= $content ?>
                </div>
                <!-- /page content -->
                <!-- footer content -->
                <footer>
                    <div class="text-center">
                        &copy; <?= date('Y') ?> <?= Yii::t('app', 'Created By') ?> RatkusSoft
                    </div>
                    <div class="clearfix"></div>
                </footer>
                <!-- /footer content -->
            </div>
        </div>

        <div id="custom_notifications" class="custom-notifications dsp_none">
            <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
            </ul>
            <div class="clearfix"></div>
            <div id="notif-group" class="tabbed_notifications"></div>
        </div>
    <?php $this->endBody(); ?>
    </body>
</html>
<?php $this->endPage(); ?>
