<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Class ImageBehavior
 *
 * ```php
 * use common\behaviors\ImageBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         [
 *             'class' => ImageBehavior::className(),
 *             'savePath' => 'savePath',
 *             'attributeName' => 'attribute'
 *         ],
 *     ];
 * }
 * ```
 */
class ImageBehavior extends Behavior
{
    /** @var string the name of the model field in which the name of the image will be saved */
    public $attributeName = '';

    /** @var string the path in which image will be saved */
    public $savePath = '';

    /** @var UploadedFile */
    public $file;

    /**
     * @return array
     */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function init()
    {
        $this->savePath = Yii::getAlias($this->savePath) . DIRECTORY_SEPARATOR;
    }

    public function beforeValidate()
    {
        $this->setFile();
    }

    public function beforeSave()
    {
        $this->setFile();
    }

    protected function setFile()
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;
        $this->file = UploadedFile::getInstance($model, $this->attributeName);

        if (!($this->file instanceof UploadedFile)) {
            return;
        }

        $model->setAttribute($this->attributeName, $this->generateImageName());
    }

    /**
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    public function afterSave()
    {
        if (!($this->file instanceof UploadedFile)) {
            return;
        }
        $this->loadImage();
    }

    /**
     * @throws \yii\base\ErrorException
     */
    public function beforeDelete()
    {
        $this->clearDir();
    }

    /**
     * @return string
     */
    public function getFilePath(): string
    {
        /** @var ActiveRecord $model */
        $model = $this->owner;

        return $this->savePath . $model->getPrimaryKey() . DIRECTORY_SEPARATOR . $model->getAttribute($this->attributeName);
    }

    /**
     * @return string
     */
    public function getImageUrl(): string
    {
        return Yii::getAlias('@web')
            . DIRECTORY_SEPARATOR
            . $this->savePath
            . DIRECTORY_SEPARATOR
            . $this->owner->getPrimaryKey()
            . DIRECTORY_SEPARATOR
            . $this->owner->getAttribute($this->attributeName);
    }

    /**
     * @return string
     */
    protected function generateImageName(): string
    {
        return time() . '.' . $this->file->getExtension();
    }

    /**
     * @return bool
     * @throws \yii\base\ErrorException
     * @throws \yii\base\Exception
     */
    protected function loadImage(): bool
    {
        $this->clearDir();
        $this->createDir();

        return $this->file->saveAs($this->getFilePath());
    }

    /**
     * @throws \yii\base\ErrorException
     */
    protected function clearDir()
    {
        FileHelper::removeDirectory(dirname($this->getFilePath()));
    }

    /**
     * @throws \yii\base\Exception
     */
    protected function createDir()
    {
        FileHelper::createDirectory(dirname($this->getFilePath()));
    }
}
