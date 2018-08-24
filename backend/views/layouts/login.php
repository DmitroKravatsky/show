<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yiister\gentelella\assets\Asset;
use yii\helpers\Html;
use common\models\language\Language;

Asset::register($this);
echo Html::cssFile(Yii::getAlias('@web/css/flags.css'));
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Yii::t('app', 'Login') ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body class="login">
<?php $this->beginBody() ?>
    <div class="top_nav">
        <ul class="nav_menu">
            <nav class="" role="navigation">
                <ul class="nav navbar-nav navbar-right">
                    <?= \common\widgets\LanguageChoice::widget([
                        'languages' => Language::getVisibleList(),
                        'url' => '/site/toggle-language',
                    ]) ?>
                </ul>
            </nav>
        </ul>
    </div>

    <div class="container">
        <?= $content ?>
    </div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
