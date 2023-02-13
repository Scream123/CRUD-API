<?php

namespace controllers;

use core\BaseController;
use helpers\Helpers;
use JetBrains\PhpStorm\NoReturn;
use models\Surveys;
use models\User;

class ApiController extends BaseController
{
    /**
     * @throws \Exception
     */
    #[NoReturn] public function actionSurveys()
    {

        $db = new Surveys();
        $surveyData = [];
        $getToken = getallheaders();
        $authData = explode(' ', $getToken['Authorization']);
        $token = $authData[1];

        if (!$token) {
            $resData['message'] = 'Ошибка при авторизации!';
            echo json_encode($resData['message'], JSON_UNESCAPED_UNICODE);
            exit;
        } else {
            $userId = $this->getUser()->getUserIdByToken($token);
            $resData['surveyData'] = $db->getSurveysByRand($userId['id']);

            foreach ($resData['surveyData'] as $key => $answers) {
                $surveyData[$answers['survey_id']]['surveysTitle'] = $answers['surveysTitle'];
//                    $surveyData[$answers['survey_id']]['answers'][$answers['id']] =
                $surveyData[$answers['survey_id']]['answers'][] =
                    [
                        'answerTitle' => $answers['answerTitle'],
                        'numberVotes' => $answers['number_votes']
                    ];
            }
            $randSurveys = array_rand($surveyData);
            echo json_encode($surveyData[$randSurveys], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    /**
     * @return void
     * @throws \Exception
     */
    #[NoReturn] public function actionLogin(): void
    {
        $email = Helpers::clearData($_POST['email']) ?? null;
        $password = Helpers::clearData($_POST['password']) ?? null;
        //  print_r($getToken);die;

        if (!$this->getUser()->checkEmail($email)) {
            $resData['message'] = ' Неправильный email!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if (!$this->getUser()->checkPassword($email)) {
            $resData['message'] = 'Пароль не должен быть короче 6-ти символов!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $user = $this->getUser()->checkUserData($email, $password);
        if (!$user['id']) {
            $resData['success'] = 0;
            $resData['message'] = 'Введите правильно свои данные!';
            echo json_encode($resData['message'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $token = bin2hex(random_bytes(16));
        $this->getUser()->createToken($token, $user['id']);
        $userData = $this->getUser()->getUserById($user['id']);
        $resData['message'] = 'Вы успешно авторизировались!';
        $resData['token'] = $userData['token'];
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @return User
     */
    private function getUser(): User
    {
        return new User();
    }

}