<?php

namespace rest\modules\api\v1\authorization\models\repositories;


use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use rest\modules\api\v1\authorization\entity\BlockToken;
use yii\web\{
    NotFoundHttpException, UnprocessableEntityHttpException
};

/**
 * Class AuthorizationRepository
 * @package rest\modules\api\v1\authorization\models\repositories
 */
trait AuthorizationRepository
{
    /**
     * Notes new users password in db
     *
     * @param $params array of the POST data
     *
     * @return bool the user's record was updated with a new password successfully
     *
     * @throws NotFoundHttpException
     * @throws UnprocessableEntityHttpException
     */
    public function updatePassword(array $params)
    {
        $userModel = AuthUserEntity::findOne(\Yii::$app->user->id);
        $userModel->setScenario(RestUserEntity::SCENARIO_UPDATE_PASSWORD);
        $userModel->setAttributes($params);

        if (!$userModel->validate()) {
            return $this->throwModelException($userModel->errors);
        }

        $userModel->password = $params['new_password'];

        if ($userModel->save(false)) {
            return true;
        }
        
        $this->throwModelException($userModel->errors);
    }

    /**
     * Check the token for the block
     *
     * @param bool
     * @return bool
     */
    public static function isAlreadyBlocked($token)
    {
        if (BlockToken::find()->where(['token' => $token])->one()) {
            return true;
        }
        return false;
    }

    /**
     * Verifies the user created on the bid after logging in
     */
    public function verifyUserAfterLogin()
    {
        if ($this->register_by_bid === self::REGISTER_BY_BID_YES && $this->status != self::STATUS_VERIFIED) {
            $this->status = self::STATUS_VERIFIED;
            $this->save(false, ['status']);
        }
    }
}
