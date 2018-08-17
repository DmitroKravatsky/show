<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;

/**
 * Class LanguageChoice
 * ```php
 * echo LanguageChoice::widget([
 *     'languages' => Language::getList(),
 *     'url' => 'site/toggle-language,
 *     'currentLanguage' => 'en'
 * ]);
 */
class LanguageChoice extends Widget
{
    public $languages;
    public $currentLanguage;
    public $url;

    public function init()
    {
        parent::init();

        $this->currentLanguage = Yii::$app->session->get('language', 'gb');
    }

    public function run()
    {
        return $this->render('index', [
            'url' => $this->url,
            'languages' => $this->languages,
            'currentLanguage' => $this->currentLanguage,
        ]);
    }
}
