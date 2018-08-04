<?php

namespace backend\modules\admin\controllers\actions\bid;

use backend\modules\admin\controllers\BidController;
use backend\modules\admin\models\BidEntitySearch;
use common\models\bid\BidEntity;
use yii\base\Action;

/**
 * Class ViewAction
 * @package backend\modules\admin\controllers\actions\bid
 */
class ViewAction extends Action
{
    /** @var  BidController */
    public $controller;

    /**
     * View detail bid info
     * @return string
     */
    public function run($id)
    {
        $model = $this->controller->findBid($id);

        return $this->controller->render('view', [
            'model'  => $model,
        ]);
    }
}
