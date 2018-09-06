<?php

namespace common\components\language;

use yii\web\Request;
use Yii;
use yii\helpers\ArrayHelper;
use common\models\language\Language;

class LanguageRequest extends Request
{
    public function resolveRequestUri()
    {
        $resolveRequestUri = parent::resolveRequestUri();

        $resolveRequestUri = explode('/', substr($resolveRequestUri, 1));
        $currentCode = $resolveRequestUri[1] ?? Yii::$app->language;

        if (in_array($currentCode, $this->getLanguageCodes())) {
            Yii::$app->language = $currentCode;
            ArrayHelper::removeValue($resolveRequestUri, $currentCode);
        }

        return '/' . implode('/', $resolveRequestUri);
    }

    /**
     * Returns an array of available language codes
     * @return array
     */
    protected function getLanguageCodes()
    {
        return array_keys(Language::getVisibleList());
    }
}
