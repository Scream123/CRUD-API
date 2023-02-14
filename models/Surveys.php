<?php

namespace models;

use core\Database;
use PDO;

class Surveys
{
    /**
     * @param $title
     * @param $user_id
     * @param $status
     * @param $date_published
     * @return mixed
     */
    public function createSurvey($title, $user_id, $status, $date_published): mixed
    {
        $stmt = $this->getDbConnect()->prepare(
            'INSERT INTO surveys(`title`,`user_id`, `status`, `date_published`) VALUES (?,?,?,?)'
        );
        $stmt->execute([$title, $user_id, $status, $date_published]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * @param $answerData
     * @param $surveyId
     * @return bool|\PDOStatement
     */
    public function createAnswer($answerData, $surveyId): bool|\PDOStatement
    {
        $sql = [];
        foreach ($answerData as $answerKey => $valueVote) {
            $sql[] ="('".$answerKey . "',". $surveyId ."," . $valueVote.")";
        }
        $query  =
            "INSERT INTO answers(`title`, `survey_id`,`number_votes`)
             VALUES "  . implode(',' , $sql)." ";

        return  $this->getDbConnect()->query($query);
    }


    /**
     * @param $id
     * @return array|false
     */
    public function getSurveyById($id): bool|array
    {
        $sql = "SELECT a.`id`, a.`survey_id`, a.`title` answerTitle, a.`number_votes`, `user_id`, s.`title` surveysTitle,
       `status`, `date_published` 
                FROM `surveys` s
                LEFT JOIN answers a ON s.`id` = a.survey_id
                WHERE  `deleted_at` IS NULL AND s.id = {$id} 
                 ORDER BY a.`survey_id`";
        $result = $this->getDbConnect()->query($sql);
        $result->execute();
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getLastSurvey($user_id): mixed
    {
        $sql = "SELECT * FROM `surveys` WHERE `user_id` = '{$user_id}' ORDER BY `id` DESC LIMIT 1";
        $result = $this->getDbConnect()->query($sql);

        return $result->fetchAll()[0];
    }

    /**
     * @param $user_id
     * @return array|false
     */
    public function getLastSurveyData($user_id): bool|array
    {
        $sql = "SELECT a.`survey_id`, a.`id` answerId, a.`title` answerTitle, `number_votes`, `user_id`, 
       s.`title` surveysTitle, `status`, `date_published` 
                FROM `answers` a
                LEFT JOIN surveys s ON s.id = a.survey_id
                WHERE  s.`user_id` = '{$user_id}'
                AND `deleted_at` IS NULL AND a.`survey_id` = (
                SELECT `id` FROM `surveys` WHERE `user_id` = '{$user_id}' ORDER BY `id` DESC LIMIT 1
                )";
        $result = $this->getDbConnect()->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getAll($user_id): bool|array
    {
        //connect with DB
        $sql = "SELECT a.`id`, a.`survey_id`, a.`title` answerTitle, `number_votes`, `user_id`, s.`title` surveysTitle,
       `status`, `date_published` 
                FROM `answers` a
                LEFT JOIN surveys s ON s.`id` = a.`survey_id`
                WHERE  `deleted_at` IS NULL AND s.`user_id` = {$user_id}
                 ORDER BY a.`survey_id`";
        $result = $this->getDbConnect()->query($sql);

        return $result->fetchAll();
    }

    /**
     *Update data of survey
     *
     *
     */
    public function updateSurvey(
        $surveyId,
        $user_id,
        $title,
        $status,
        $date_published
    ): bool|\PDOStatement
    {
        $set = [];
        if(!$surveyId) return false;
        if ($user_id) {
            $set[] = "`user_id` = '{$user_id}'";
        }
        if ($title) {
            $set[] = "`title` = '{$title}'";
        }
        if ($status) {
            $set[] = "`status` = '{$status}'";
        }
        if ($date_published) {
            $set[] = "`date_published` = '{$date_published}'";
        }
        $setStr = implode(', ', $set);
        $sql = "UPDATE `surveys` SET {$setStr} WHERE `id` = {$surveyId}";

        return $this->getDbConnect()->query($sql);
    }
    public function updateAnswer(
        $survey_id,
        $answer_id,
        $title,
        $votes
    ): bool|\PDOStatement
    {
        $stmt = $this->getDbConnect()->prepare(
             "UPDATE `answers`
                    SET `title` = ?, `number_votes`= ?
                    WHERE `survey_id` = {$survey_id}
                    AND `id` = {$answer_id}"
        );
            $stmt->execute([$title, $votes]);

       return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * delete survey from table in view
     * @param $survey_id
     */
    public function deleteSurvey($survey_id): bool|\PDOStatement
    {
        $sql = "UPDATE surveys SET `deleted_at` = CURRENT_TIMESTAMP WHERE `id` = {$survey_id}";

        return $this->getDbConnect()->query($sql);
    }

    /**
     * @param $surveyTitle
     * @return array|false
     */
    public function getSurveysListByTitle($surveyTitle, $user_id)
    {
        $sql = "SELECT a.`id`, a.`survey_id`, a.`title` answerTitle, a.`number_votes`, `user_id`, s.`title` surveysTitle,
       `status`, `date_published`,  IF(s.`status`  = 0, 'Черновик', 'Опубликовано' )  status 
                FROM `surveys` s
                LEFT JOIN answers a ON s.`id` = a.survey_id
                WHERE  `deleted_at` IS NULL AND s.`title` = '{$surveyTitle}' AND s.`user_id` = {$user_id}   
                 ORDER BY s.`id` ";
        $result = $this->getDbConnect()->query($sql);
        return $result->fetchAll();
    }

    /**
     * @param $surveyStatus
     * @param $user_id
     * @return array|false
     */
    public function getSurveysListByStatus($surveyStatus, $user_id)
    {
        $sql = "SELECT a.`id`, a.`survey_id`, a.`title` answerTitle, a.`number_votes`, `user_id`, s.`title` surveysTitle,
       `status`, `date_published`, IF(s.`status`  = 0, 'Черновик', 'Опубликовано' )  status  
                FROM `surveys` s
                LEFT JOIN answers a ON s.`id` = a.survey_id
                WHERE  `deleted_at` IS NULL AND s.`status` = {$surveyStatus} AND s.`user_id` = {$user_id} 
                 ORDER BY s.`id` ";
        $result = $this->getDbConnect()->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param $surveyDate
     * @param $user_id
     * @return bool|array
     */
    public function getSurveysListByDate($surveyDate, $user_id): bool|array
    {
        $sql = "SELECT a.`id`, a.`survey_id`, a.`title` answerTitle, a.`number_votes`, `user_id`, s.`title` surveysTitle,
       `status`, `date_published`, IF(s.`status`  = 0, 'Черновик', 'Опубликовано' )  status  
                FROM `surveys` s
                LEFT JOIN answers a ON s.`id` = a.survey_id
                WHERE  `deleted_at` IS NULL AND s.`date_published` = '{$surveyDate}' AND s.`user_id` = {$user_id} 
                 ORDER BY s.`id` ";
        $result = $this->getDbConnect()->query($sql);

        return $result->fetchAll();
    }

    /**
     * @param $user_id
     * @return bool|array
     */
    public function getSurveysByRand($user_id): bool|array
    {
        $sql = "SELECT a.`id`, a.`survey_id`, a.`title` answerTitle, a.`number_votes`, `user_id`, s.`title` surveysTitle,
       `status`, `date_published`
                FROM `surveys` s
                LEFT JOIN answers a ON s.`id` = a.survey_id
                WHERE  `deleted_at` IS NULL AND  s.`user_id` = {$user_id}
                 ORDER BY  s.`id`";
        $result = $this->getDbConnect()->query($sql);

        return $result->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * @return PDO|void
     */
    protected function getDbConnect()
    {
        return Database::getConnection();
    }

}