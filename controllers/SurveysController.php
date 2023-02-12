<?php

namespace controllers;

use core\BaseController;
use helpers\Helpers;
use JetBrains\PhpStorm\NoReturn;
use models\User;

class SurveysController extends BaseController
{
    /**
     * @return bool
     */
    public function actionIndex(): bool
    {
        $this->view->render('surveys/index');

        return true;
    }

    /**
     * @return void
     */
    #[NoReturn] public function actionRegister(): void
    {
        $resData = [];
        $name = Helpers::clearData($_POST['name']) ?? null;
        $email = Helpers::clearData($_POST['email']) ?? null;
        $password = Helpers::clearData($_POST['password']) ?? null;
        $pwd2 = Helpers::clearData($_POST['pwd2']) ?? null;
        if (!$this->getUser()->checkName($name)) {
            $resData['success'] = 0;
            $resData['message'] = 'Имя не должно быть короче 2-х символов!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        }
        if (!$this->getUser()->checkEmail($email)) {
            $resData['success'] = 0;
            $resData['message'] = 'Неправильный email!';
        }
        if (!$this->getUser()->checkPassword($password)) {
            $resData['success'] = 0;
            $resData['message'] = 'Пароль не должен быть короче 6-ти символов!';
        }
        if ($this->getUser()->checkEmailExists($email)) {
            $resData['success'] = 0;
            $resData['message'] = 'Такой email уже используется!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if (!$this->getUser()->comparePwd($password, $pwd2)) {
            $resData['success'] = 0;
            $resData['message'] = 'Пароли не совпадают!';
        }

        $hash = null;

        if (!$resData) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $this->getUser()->register($name, $email, $hash);
            $resData['success'] = 1;
            $resData['message'] = "Вы зарегистрировались!</p>";
        } else {
            $resData['success'] = 0;
            $resData['message'] = 'Ошибка регистрации!';
        }

        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @return void
     * @throws \Exception
     */
    #[NoReturn] public function actionLogin(): void
    {
        $resData = [];
        $email = Helpers::clearData($_POST['email']) ?? null;
        $password = Helpers::clearData($_POST['password']) ?? null;
        if (!$this->getUser()->checkEmail($email)) {
            $resData['success'] = 0;
            $resData['message'] = ' Неправильный email!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if (!$this->getUser()->checkPassword($email)) {
            $resData['success'] = 0;
            $resData['message'] = 'Пароль не должен быть короче 6-ти символов!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        $user = $this->getUser()->checkUserData($email, $password);
        if ($user['id']) {
            $token = bin2hex(random_bytes(16));
            $tokenInsertResult = $this->getUser()->createToken($token,$user['id']);
            if(!$tokenInsertResult){
                $resData['success'] = 0;
                $resData['message'] = 'Ошибка при авторизации!';
            }else {
                $this->getUser()->auth($user['id']);
                $resData['user_name'] = $user['name'];
                $resData['success'] = 1;
                $resData['message'] = "Вы успешно авторизировались!</p>";
            }
        } else {
            $resData['success'] = 0;
            $resData['message'] = 'Введите правильно свои данные!';
        }
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @return void
     */
    public function actionLogout(): void
    {
        unset($_SESSION['user']);
        header("Location: /");
    }

    /**
     * @return User
     */
    private function getUser(): User
    {
        return new User();
    }
}