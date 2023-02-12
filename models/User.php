<?php

namespace models;

use core\Database;
use helpers\Helpers;
use PDO;

class User
{
    protected Helpers $helpers;
    /**
     * @var PDO|void
     */

    public function __construct()
    {
         $this->helpers = new Helpers();
    }

    /**
     * @param string $name
     * @param string $email
     * @param string $password
     * @return boolean
     */
    public function register(string $name, string $email, string $password): bool
    {
        $name = $this->helpers->clearData($name);
        $email = $this->helpers->clearData($email);
        $password = $this->helpers->clearData($password);
        $sql = 'INSERT INTO users (`name`, `email`, `password`) '
            . 'VALUES (:name, :email, :password)';
        $result = $this->getDbConnect()->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
       return $result->execute();
    }

    /**
     * Validates name: not less than 2 characters
     * @param string $name
     * @return boolean
     */
    public function checkName(string $name): bool
    {
        $name = $this->helpers->clearData($name);
        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    /**
     * Validates name: no less than 6 characters
     * @param string $password
     * @return boolean
     */
    public function checkPassword(string $password): bool
    {
        $password = $this->helpers->clearData($password);
        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    /**
     * Checks email
     * @param string $email
     * @return boolean
     */
    public function checkEmail(string $email): bool
    {

        $email = $this->helpers->clearData($email);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Checks if the email is in use by another user
     * @param string $email
     * @return boolean
     */
    public function checkEmailExists(string $email): bool
    {
        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
        $result =  $this->getDbConnect()->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn()) {
            return true;
        }
        return false;
    }

    /**
     * password validation
     * @param $password - new password
     * @param $pwd2 - repeat password
     * @return bool
     */
    public function comparePwd($password, $pwd2): bool
    {
        $password = trim($password);
        $pwd2 = trim($pwd2);
        if ($password == $pwd2) {
            return true;
        }

        return false;

    }

    public function checkUserData($email, $password)
    {
        $this->getDbConnect();
        $hash = User::getHashPwd($email);
        $hashPwd = password_verify($password, $hash);
        if ($hashPwd) {
            $sql = "SELECT * FROM users WHERE email = :email";
        } else {
            echo 'Данные не верны'; return false;
        }

        $result = $this->getDbConnect()->prepare($sql);
        $result->bindParam(':email', $email);
        $result->execute();
        $user =  $result->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $this->auth($user['id']);
            return $user;
       }
        return false;
    }

    /**
     * @param $name
     * @param $email
     * @return bool
     */
    public function createToken($token, $user_id): bool
    {
        $sql = "UPDATE  users SET `token`=:token WHERE `id`=:id";
        $result = $this->getDbConnect()->prepare($sql);
        $result->bindParam(':token', $token);
        $result->bindParam(':id', $user_id);

       return $result->execute();
    }

    /**
     * @param $email
     * @return mixed
     */
    public function getHashPwd($email): mixed
    {
        $row = "SELECT `password` FROM `users` WHERE `email` = '{$email}'";
        $res = $this->getDbConnect()->prepare($row);
        $res->execute();
        $hashPwd = $res->fetch(PDO::FETCH_ASSOC);

        return $hashPwd['password'];
    }
    /**
     * Запоминаем пользователя
     * @param integer $userId <p>id пользователя</p>
     */
    public function auth(int $userId): void
    {
        // Записываем идентификатор пользователя в сессию
        $_SESSION['user'] = $userId;
    }
    /**
     * Возвращает идентификатор пользователя, если он авторизирован.<br/>
     * Иначе перенаправляет на страницу входа
     * @return string <p>Идентификатор пользователя</p>
     */
    public static function checkLogged()
    {
        // Если сессия есть, вернем идентификатор пользователя
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }


        header("Location: /cabinet/login");
    }

    /**
     * Проверяет является ли пользователь гостем
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function isGuest()
    {
        if (isset($_SESSION['user'])) {
            return false;
        }
        return true;

    }

    /**
     * Возвращает пользователя с указанным id
     * @param integer $id <p>id пользователя</p>
     * @return array <p>Массив с информацией о пользователе</p>
     */
    public function getUserById($id)
    {
        // Текст запроса к БД
        $sql = 'SELECT * FROM users WHERE id = :id ';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $this->getDbConnect()->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }
    /**
     * @return PDO|void
     */
     protected function getDbConnect()
    {
        return Database::getConnection();
    }
}

