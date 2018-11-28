<?php

namespace common\behaviors;

use Yii;
use yii\{ behaviors\AttributeBehavior, web\BadRequestHttpException };

/**
 * Class ValidateReportParameters
 *
 * @property array $params
 * @package common\behaviors
 */
class ValidatePostParameters extends AttributeBehavior
{
    /**
     * @var array
     */
    public $inputParams = [];

    /**
     * @throws BadRequestHttpException
     * @throws \yii\base\InvalidConfigException
     */
    public function validationParams()
    {
        foreach ($this->inputParams as $nameParam) {
            $param = Yii::$app->request->getBodyParam($nameParam);
            if (!isset(Yii::$app->request->getBodyParams()[$nameParam])) {
                throw new BadRequestHttpException("Parameter {$nameParam} is required");
            }
            $this->owner->params[$nameParam] = (int) $param;
        }
    }
}
