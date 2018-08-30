<?php

namespace common\components\language;

use Yii;
use yii\web\UrlManager;

class LanguageUrlManager extends UrlManager
{
    protected $prefixUrl = '/admin';

    public function createUrl($params)
    {
        $url = parent::createUrl($params);

        $explodeUrl = explode($this->prefixUrl, $url);

        return $this->prefixUrl . '/' . Yii::$app->language . $explodeUrl[1];
    }
}
