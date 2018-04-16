<?php

namespace rest\behaviors;

use yii\base\Behavior;
use yii\web\UnprocessableEntityHttpException;

/**
 * Class ValidationExceptionFirstMessage
 * @package rest\behaviors
 */
class ValidationExceptionFirstMessage extends Behavior
{
    // todo данное поведение перенести в папку common
    /**
     * Method of validation post data
     * @param $modelErrors
     * @return bool
     * @throws UnprocessableEntityHttpException
     */
    public function throwModelException($modelErrors)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);

            throw new UnprocessableEntityHttpException($firstMessage);
        }

        return false;
    }
}
