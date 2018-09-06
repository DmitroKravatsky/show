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
        $language = Yii::$app->session->get('language') ?? Yii::$app->language;

        return $this->prefixUrl . '/' . $language . $explodeUrl[1];
    }
}
