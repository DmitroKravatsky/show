<?php

return [
    'secretJWT'       => '57H*fd^&G*&DD#Ge',
    'algorithmJWT'    => 'HS256',
    'tokenExpireDays' => 1,
    'refreshTokenExpireDays' => 30,
    'guest-email' => 'guest@gmail.com',
    'guest-password' => 'guestPassword',
    'posts-per-page' => 5,
    'rates-xml-file' => Yii::getAlias('@frontend/web/xml/rates.xml'),
    'emailVerificationCodeLifeTime' => 3600,
];