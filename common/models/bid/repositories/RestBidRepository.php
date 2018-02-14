<?php

namespace common\models\bid\repositories;

use common\models\bid\BidEntity;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use Yii;
use yii\db\BaseActiveRecord;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class RestBidRepository
 * @package common\models\bid\repositories
 */
trait RestBidRepository
{
    /**
     * @param $params
     * @return ArrayDataProvider
     */
    public function getBids($params): ArrayDataProvider
    {
        /** @var ActiveQuery $query */
        $query = self::find()->where(['created_by' => Yii::$app->user->id]);

        if (isset($params['created_at']) && $params['created_at'] == 'week') {
            $query->andWhere(['>=', 'created_at', time() - (3600 * 24 * 7)]);
        } elseif (isset($params['created_at']) && $params['created_at'] == 'month') {
            $query->andWhere(['>=', 'created_at', time() - (3600 * 24 * 30)]);
        }

        $dataProvider = new ArrayDataProvider([
            'allModels'  => $query->orderBy(['created_at' => SORT_DESC])->all(),
            'pagination' => [
                'pageSize' => $params['per-page'] ?? 10
            ]
        ]);

        return $dataProvider;
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function getBidDetails($id)
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        
        return $bid->getAttributes([
            'status', 'from_payment_system', 'to_payment_system', 'from_wallet', 'to_wallet', 'from_currency',
            'to_currency', 'from_sum', 'to_sum'
        ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function updateBid($id)
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        $bid->setScenario(BidEntity::SCENARIO_UPDATE);
        $bid->setAttributes(Yii::$app->request->bodyParams);

        if (!$bid->save()) {
            $this->throwModelException($bid->errors);
        }

        return $this->setResponse(200, Yii::t('app', 'Заявка успешно изменена.'), $bid->getAttributes());
    }

    /**
     * @param $id
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws \yii\db\StaleObjectException
     */
    public function deleteBid($id)
    {
        $bid = $this->findModel(['id' => $id, 'created_by' => Yii::$app->user->id]);
        if ($bid->delete()) {
            return $this->setResponse(200, Yii::t('app', 'Заявка успешно удалёна.'), ['id' => $id]);
        }

        throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при удалении заявки.'));
    }

    /**
     * @return mixed
     * @throws ServerErrorHttpException
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function createBid()
    {
        $bid = new self;
        $bid->setScenario(BidEntity::SCENARIO_CREATE);
        $bid->setAttributes(Yii::$app->request->bodyParams);

        if ($bid->save()) {
            return $this->setResponse(201, Yii::t('app', 'Заявка успешно добавлена.'), $bid->getAttributes());
        } elseif ($bid->hasErrors()) {
            $this->throwModelException($bid->errors);
        }

        throw new ServerErrorHttpException(Yii::t('app', 'Произошла ошибка при добавлении заявки.'));
    }

    /**
     * @param $params
     * @return BaseActiveRecord
     * @throws NotFoundHttpException
     */
    public function findModel($params): BaseActiveRecord
    {
        if (empty($bidModel = self::findOne($params))) {
            throw new NotFoundHttpException('Заявка не найдена');
        }

        return $bidModel;
    }
}