<?php

namespace common\validators;

use Yii;
use yii\validators\Validator;
use \Exception;

class Base64Validator extends Validator
{
    public $extensions = [];

    public function validateAttribute($model, $attribute): void
    {
        if (!preg_match('/^data:image\/(\w+);base64,/', $model->{$attribute}, $type)) {
            $model->addError($attribute, 'Avatar is not a valid base64 string.');
        }

        if (!$this->isSizeAllowed($model->{$attribute})) {
            $model->addError($attribute, 'The file is too big. Its size cannot exceed ' . Yii::$app->params['avatarMaxSizeInKb'] . ' Kb.');
        }

        $data = substr($model->{$attribute}, strpos($model->{$attribute}, ',') + 1);
        $type = strtolower($type[1]);

        if (!in_array($type, $this->extensions)) {
            $model->addError('avatar', 'Only files with these extensions are allowed: ' . implode(',', $this->extensions) . '.');
        }

        if (base64_decode($data) === false) {
            throw new Exception('Base64 decode failed.');
        }
    }

    protected function isSizeAllowed($base64String): bool
    {
        return $this->calculateFileSize($base64String) < Yii::$app->params['avatarMaxSizeInKb'];
    }

    protected function calculateFileSize($base64String)
    {
        $characterCount = strlen($base64String);
        $paddingCount = strlen(preg_replace(['#[^=]#'], '', $base64String));
        $sizeInBytes = (3 * ($characterCount / 4)) - $paddingCount;

        return round($sizeInBytes / 1024);
    }
}
