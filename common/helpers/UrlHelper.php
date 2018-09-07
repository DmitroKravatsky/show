<?php

namespace common\helpers;

use Yii;
use yii\helpers\Url;
use common\models\language\Language;

class UrlHelper
{
    protected static $prefixUrl = '/admin';

    public static function getFilterUrl()
    {
        return Yii::$app->request->baseUrl . '/' . Yii::$app->language . '/' . Yii::$app->request->pathInfo;
    }

    public static function getCurrentUrlWithoutQueryParams()
    {
        return preg_replace('#\?.*#', '', Url::current());
    }

    public static function getCustomUrl()
    {
        $url = Yii::$app->request->url;
        $urlExplode = explode('?', $url);
        $queryParams = $urlExplode[1] ?? '';
        $url = $urlExplode[0];

        $url = str_replace(static::$prefixUrl, '', $url);

        $normalizeUrl = static::$prefixUrl . '/' . Yii::$app->language . $url;
        if (!empty($queryParams)) {
            $normalizeUrl = $normalizeUrl . '?' . $queryParams;
        }
        return $normalizeUrl;
    }

    public static function getReferrerUrlByLocale()
    {
        $referrer = Yii::$app->request->referrer;
        $langCodes = static::getLanguageCodes();

        $pattern = '(';
        foreach ($langCodes as $langCode) {
            $pattern .= $langCode . '|';
        }
        $pattern = rtrim($pattern, '|');
        $pattern .= ')';

        return preg_replace($pattern, Yii::$app->language, $referrer);
    }

    /**
     * Returns an array of available language codes
     * @return array
     */
    protected static function getLanguageCodes()
    {
        return array_keys(Language::getVisibleList());
    }
}
