<?php

declare(strict_types=1);

namespace rest\modules\api\v1\authorization\service\social;

use rest\modules\api\v1\authorization\model\social\{
    FbAuthorizationRequestModel, GmailAuthorizationRequestModel
};

interface SocialUserServiceInterface
{
    public function fbAuthorization(FbAuthorizationRequestModel $model): array;

    public function gmailAuthorization(GmailAuthorizationRequestModel $model): array;
}
