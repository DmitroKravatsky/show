<?php

namespace common\helpers;

use Yii;
use yii\helpers\Url;

class UrlHelper
{
    public static function getFilterUrl()
    {
        return Yii::$app->request->baseUrl . '/' . Yii::$app->language . '/' . Yii::$app->request->pathInfo;
    }

    public static function getCurrentUrlWithoutQueryParams()
    {
        return preg_replace('#\?.*#', '', Url::current());
    }
}
