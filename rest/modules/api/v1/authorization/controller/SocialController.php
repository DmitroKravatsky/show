<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\controller;

use rest\modules\api\v1\authorization\controller\action\social\{
    FbAuthorizeAction, GmailAuthorizeAction
};
use rest\modules\api\v1\authorization\entity\AuthUserEntity;
use rest\modules\api\v1\authorization\service\social\SocialUserServiceInterface;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\base\Module;

/**
 * Class SocialController
 * @package rest\modules\api\v1\authorization\controllers
 *
 */
class SocialController extends Controller
{
    /** @var AuthUserEntity */
    public $modelClass = AuthUserEntity::class;
    public $service;

    public function __construct($id, Module $module, SocialUserServiceInterface $service, array $config = [])
    {
        $this->service = $service;

        parent::__construct($id, $module, $config);
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['verbs'] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'gmail-authorize'   => ['POST'],
                'fb-authorization'  => ['POST'],
            ]
        ];

        return $behaviors;
    }

    public function actions(): array
    {
        $actions = parent::actions();

        $actions['gmail-authorize'] =  [
            'class'      => GmailAuthorizeAction::class,
            'modelClass' => $this->modelClass
        ];

        $actions['fb-authorize']    =  [
            'class'      => FbAuthorizeAction::class,
            'modelClass' => $this->modelClass
        ];

        return $actions;
    }
}
