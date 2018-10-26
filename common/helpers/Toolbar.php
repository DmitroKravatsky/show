<?php

namespace common\helpers;

use yii\helpers\Html;
use yii\helpers\Url;
use Yii;

class Toolbar
{
    public static function createButton($url, $title = '')
    {
        return Html::a(
            '<i class="glyphicon glyphicon-plus"></i>',
            Url::to([$url]),
            ['type' => 'button', 'title' => $title, 'class' => 'btn btn-success']
        ) . ' ';
    }

    public static function deleteButton($url, $title = '', $disabled = false)
    {
        $class = 'btn btn-danger';
        if ($disabled) {
            $class .= ' no-link';
        }
        return Html::a(
                '<i class="glyphicon glyphicon-trash"></i>',
                Url::to([$url]),
                ['type' => 'button', 'title' => $title, 'class' => $class, 'disabled' => $disabled,]
            ) . ' ';
    }

    public static function resetButton()
    {
        return Html::a(
            '<i class="glyphicon glyphicon-repeat"></i>',
            UrlHelper::getCurrentUrlWithoutQueryParams(),
            ['data-pjax' => 0, 'class' => 'btn btn-default', 'title' => Yii::t('app', 'Reset Grid')]
        ) . ' ';
    }

    public static function readAllButton($url, $disabled = false)
    {
        $class = 'btn btn-primary';
        if ($disabled) {
            $class .= ' no-link';
        }

        return Html::a(
                '<i class="glyphicon glyphicon-eye-open"></i>',
                Url::to([$url]),
                ['data-pjax' => 0, 'class' => $class, 'title' => Yii::t('app', 'Read all'), 'disabled' => $disabled,]
        ) . ' ';
    }

    /**
     * @param $url string Direction link
     * @param array $properties Array of actual tag properties
     * @return string
     */
    public static function createButtonWithProperties($url, array $propertiesList): string
    {
        return Html::a(
            '<i class="glyphicon glyphicon-plus"></i>',
            Url::to([$url]),
            $propertiesList
        ) . ' ';
    }
}
