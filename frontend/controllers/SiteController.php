<?php
namespace frontend\controllers;

use GuzzleHttp\Client;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'doc' => [
                'class' => 'light\swagger\SwaggerAction',
                'restUrl' => Url::to(['/site/api'], true),
            ],
            //The resultUrl action.
            'api' => [
                'class' => 'light\swagger\SwaggerApiAction',
                //The scan directories, you should use real path there.
                'scanDir' => [
                    Yii::getAlias('@docs'),
                    // Authorization module
                    Yii::getAlias('@rest/modules/api/v1/authorization/controllers/actions/authorization'),
                    Yii::getAlias('@rest/modules/api/v1/authorization/controllers/actions/social'),
                    // Bid module
                    Yii::getAlias('@rest/modules/api/v1/bid/controllers/actions'),
                    // Reserve module
                    Yii::getAlias('@rest/modules/api/v1/reserve/controllers/actions/ListAction.php'),
                    // Review module
                    Yii::getAlias('@rest/modules/api/v1/review/controllers/actions/CreateAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/review/controllers/actions/UpdateAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/review/controllers/actions/DeleteAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/review/controllers/actions/ListAction.php'),
                    // User module
                    Yii::getAlias('@rest/modules/api/v1/user/controllers/actions/profile/GetProfileAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/user/controllers/actions/profile/UpdateAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/user/controllers/actions/profile/UpdatePasswordAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/user/controllers/actions/profile/BindFbAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/user/controllers/actions/profile/BindGmailAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/user/controllers/actions/notifications/ListAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/user/controllers/actions/notifications/DeleteAction.php'),
                    // Wallet module
                    Yii::getAlias('@rest/modules/api/v1/wallet/controllers/actions/CreateAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/wallet/controllers/actions/UpdateAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/wallet/controllers/actions/ListAction.php'),
                    Yii::getAlias('@rest/modules/api/v1/wallet/controllers/actions/DeleteAction.php'),
                    // Payment System Module
                    Yii::getAlias('@rest/modules/api/v1/paymentSystem/controllers/actions/ListAction.php'),
                ],
            ],

            'doc-user' => [
                'class'   => 'light\swagger\SwaggerAction',
                'restUrl' => Url::to(['site/api-user']),
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Method provides access_token for GmailAuthorize action testing
     */
    public function actionGmail()
    {
        $client = new Client(['headers' => ['Content-Type' => 'application/x-www-form-urlencoded']]);
        $result = $client->request(
            'POST',
            'https://accounts.google.com/o/oauth2/token',
            [
                'form_params' => [
                    'client_id' =>  Yii::$app->params['gmail_secret_id'],
                    'client_secret' => Yii::$app->params['gmail_client_secret'],
                    'redirect_uri' => 'http://' . $_SERVER['HTTP_HOST'] . '/frontend/web/site/gmail',
                    'code' => \Yii::$app->request->get('code'),
                    'grant_type'  => 'authorization_code',
                ]
            ]
        );
        if ($result->getStatusCode() == 200) {
            $userData = json_decode($result->getBody()->getContents());
            var_dump($userData); exit;
        }
    }


    /**
     * Method provides access_token for FbAuthorize action testing
     */
    public function actionFace()
    {
        if (\Yii::$app->request->get('access_token')) {
            var_dump(\Yii::$app->request->get('access_token')); exit;
        }
            $code = \Yii::$app->request->get('code');

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://graph.facebook.com/v2.12/oauth/access_token?client_id=' . Yii::$app->params['fb_secret_id'] . '&client_secret='  . Yii::$app->params['fb_client_secret'] . '&redirect_uri=http://' . $_SERVER['HTTP_HOST'] . '/frontend/web/site/face&code='.$code);
            curl_exec($ch);

    }

}
