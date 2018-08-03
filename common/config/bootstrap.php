<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@rest', dirname(dirname(__DIR__)) . '/rest');
Yii::setAlias('@docs', dirname(dirname(__DIR__)) . '/docs');
Yii::setAlias('image.default.user.avatar', '/images/default-user-avatar.png');
Yii::setAlias('@image', Yii::getAlias('@frontend/web/image'));
