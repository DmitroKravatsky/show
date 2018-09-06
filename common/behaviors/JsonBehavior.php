<?php

namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class JsonBehavior extends AttributeBehavior
{
    public $attributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND      => 'decodeJson',
            ActiveRecord::EVENT_BEFORE_INSERT   => 'encodeJson',
            ActiveRecord::EVENT_BEFORE_UPDATE   => 'encodeJson',
        ];
    }

    public function encodeJson()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->{$attribute} = !empty($this->owner->{$attribute}) ? Json::encode($this->owner->{$attribute}) : '{}';
        }
    }

    public function decodeJson()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->{$attribute} = !empty($this->owner->{$attribute}) ? Json::decode($this->owner->{$attribute}, false) : new \StdClass();
        }
    }
}
