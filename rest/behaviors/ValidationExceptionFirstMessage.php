<?php

namespace rest\behaviors;

use yii\db\Exception;

/**
 * Class ValidationExceptionFirstMessage
 * @package rest\behaviors
 */
class ValidationExceptionFirstMessage extends \yii\base\Behavior
{
    /**
     * Method of validation post data
     * @param $modelErrors
     * @return bool
     * @throws Exception
     */
    public function throwModelException($modelErrors)
    {
        if (is_array($modelErrors) && !empty($modelErrors)) {
            $fields = array_keys($modelErrors);
            $firstMessage = current($modelErrors[$fields[0]]);

            throw new Exception($firstMessage);
        }

        return false;
    }

}