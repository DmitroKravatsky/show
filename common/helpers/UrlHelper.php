<?php

namespace common\helpers;

use Yii;

class UrlHelper
{
    public static function getFilterUrl()
    {
        return Yii::$app->request->baseUrl . '/' . Yii::$app->language . '/' . Yii::$app->request->pathInfo;
    }
}
