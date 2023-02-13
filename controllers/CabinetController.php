<?php

namespace controllers;

use core\BaseController;
use helpers\Helpers;
use JetBrains\PhpStorm\NoReturn;
use models\Surveys;

class CabinetController extends BaseController
{
    public function actionIndex(): bool|array
    {
        $db = new Surveys();
        $user_id = $_SESSION['user'] ?? null;
        if (!$user_id) header('Location: /');
        $surveyCollection = $db->getAll($user_id);

        $surveyData = $this->getArr($surveyCollection);
        $this->view->render('cabinet/cabinet', $surveyData);

        return $surveyData;
    }

    /**
     * @return void
     */
    #[NoReturn] public function actionAddSurvey(): void
    {
        $db = new Surveys();
        $resData = [];
        $title = Helpers::clearData($_POST['title']);
        $answer = $_POST['answer'];
        $votes = $_POST['votes'];
        $status = (int)$_POST['status'];
        $date_published = $_POST['date_published'];
        $user_id = $_SESSION['user'];
        if ($title === '') {
            $resData['success'] = 0;
            $resData['message'] = 'Введите название вопрос!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if ($answer === '') {
            $resData['success'] = 0;
            $resData['message'] = 'Введите название ответа!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if ($votes === '') {
            $resData['success'] = 0;
            $resData['message'] = 'Введите количество голосов числом!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if ($status === '') {
            $resData['success'] = 0;
            $resData['message'] = 'Выберите статус!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }
        if (!$resData) {
            $surveys = [];
            foreach ($answer as $key => $value) {
                foreach ($votes as $keyVote => $voteValue) {

                    if ($key === $keyVote) {
                        $surveys[$value] = $voteValue;
                    }
                }
            }
            $db->createSurvey($title, $user_id, $status, $date_published);
            $surveyData = $db->getLastSurvey($user_id);
           $db->createAnswer($surveys, $surveyData['id']);
            $lastSurveyData = $db->getLastSurveyData($user_id);

            $resData['surveyList'] = $lastSurveyData;
            $resData['success'] = 1;
            $resData['message'] = "Опрос успешно добавлен!";
        } else {
            $resData['success'] = 0;
            $resData['message'] = 'Ошибка при добавлении опроса!';
        }
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @param $id
     * @return bool
     */
    public function actionEdit($id): bool
    {
        $db = new Surveys();
        $surveyCollection = $db->getSurveyById((int)$id);
        $surveyData = $this->getArr($surveyCollection);
        $this->view->render('cabinet/editSurvey', $surveyData);

        return true;
    }

    /**
     * @param $id
     * @return void
     */
    #[NoReturn] public function actionUpdateSurvey($id): void
    {
        $resData = [];
        $surveyId = (int)$id;
        $survey_title = Helpers::clearData($_POST['surveyTitle']);
        $answers = $_POST['answer'];
        foreach ($answers as $answer){

            if ($answer['title'] === '') {
                $resData['success'] = 0;
                $resData['message'] = 'Введите ответ!';
                $resData['error'] = 'Введите ответ!';
                echo json_encode($resData, JSON_UNESCAPED_UNICODE);
                exit;
            }

            if ($answer['votes'] === '') {
                $resData['success'] = 0;
                $resData['error'] = 'Введите количество голосов числом!';
                echo json_encode($resData, JSON_UNESCAPED_UNICODE);
                exit;
            }
        }
        $status = $_POST['status'];
        $date_published = date('Y-m-d H:i:s');
        $user_id = $_SESSION['user'];

        if ($survey_title === '') {
            $resData['success'] = 0;
            $resData['error'] = 'Введите название вопроса!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($status === '') {
            $resData['success'] = 0;
            $resData['error'] = 'Выберите статус!';
            echo json_encode($resData, JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (count($resData) === 0) {
            $db = new Surveys();
            $db->updateSurvey($surveyId, $user_id, $survey_title, $status, $date_published,);
            foreach ($answers as $answerId => $answer) {
                $db->updateAnswer($surveyId,$answerId, $answer['title'], $answer['votes']);
            }
            $resData['success'] = 1;
            $resData['message'] = "Опрос успешно обновлен!";
        } else {
            $resData['success'] = 0;
            $resData['error'] = 'Ошибка при обновления опроса!';
        }
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * delete survey
     */
    #[NoReturn] public function actionDeletedSurvey($survey_id): void
    {
        $db = new Surveys();
        if (!$survey_id) {
            exit();
        }
        $res = $db->deleteSurvey($survey_id);
        if ($res) {
            $resData['success'] = 1;
            $resData['message'] = 'Опрос удалён';
        } else {
            $resData['success'] = 0;
            $resData['message'] = 'ошибка при удалении';
        }
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @return void
     */
    #[NoReturn] public function actionSearchSurveyByTitle(): void
    {
        $db = new Surveys();
        $user_id = $_SESSION['user'] ?? null;
        $resData = [];
        $surveyTitleFilter = $_POST['surveyTitleFilter'] ?? null;
        $surveyCollection = $db->getSurveysListByTitle($surveyTitleFilter, $user_id);
        $resData = $this->getResData($surveyCollection, $resData);
        if (isset($resData['surveyList'])) {
            $resData['success'] = 1;
            $resData['message'] = 'Поиск опроса по названию успешен';
        } else {
            $resData['success'] = 0;
            $resData['message'] = 'Ошибка при поиске опроса по названию';
        }
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @return void
     */
    #[NoReturn] public function actionSearchSurveyByStatus(): void
    {
        $db = new Surveys();
        $user_id = $_SESSION['user'] ?? null;

        $resData = [];
        $keyStatus = null;
        if ($_POST['filterStatus'] === 'Черновик') {
            $keyStatus = 0;
        } elseif ($_POST['filterStatus'] === 'Опубликовано') {
            $keyStatus = 1;
        }
        $surveyCollection = $db->getSurveysListByStatus($keyStatus, $user_id);
        $resData = $this->getResData($surveyCollection, $resData);
        if ($resData) {
            $resData['success'] = 1;
            $resData['message'] = 'Поиск опроса по названию успешен';
        } else {
            $resData['success'] = 0;
            $resData['message'] = 'Ошибка при поиске опроса по названию';
        }
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    #[NoReturn] public function actionSearchSurveyByDate(): void
    {
        $db = new Surveys();
        $user_id = $_SESSION['user'] ?? null;
        $resData = [];
        $filterDate = $_POST['filterDate'] ?? null;
        $surveyCollection = $db->getSurveysListByDate($filterDate, $user_id);
        $resData = $this->getResData($surveyCollection, $resData);

        if ($resData) {
            $resData['success'] = 1;
            $resData['message'] = 'Поиск опроса по дате публикации успешен';
        } else {
            $resData['success'] = 0;
            $resData['message'] = 'Ошибка при поиске опроса по дате публикации';
        }
        echo json_encode($resData, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * @param bool|array $surveyCollection
     * @return array
     */
    public function getArr(bool|array $surveyCollection): array
    {
        $surveyData = [];
        foreach ($surveyCollection as $key => $answers) {
            $surveyData[$answers['survey_id']]['survey_id'] = $answers['survey_id'];
            $surveyData[$answers['survey_id']]['surveysTitle'] = $answers['surveysTitle'];
            $surveyData[$answers['survey_id']]['answers'][$answers['id']] =
                [
                    'answerTitle' => $answers['answerTitle'],
                    'numberVotes' => $answers['number_votes']
                ];
            $surveyData[$answers['survey_id']]['status'] = $answers['status'];
            $surveyData[$answers['survey_id']]['date_published'] = $answers['date_published'];
        }
        return $surveyData;
    }

    /**
     * @param bool|array $surveyCollection
     * @param array $resData
     * @return array
     */
    public function getResData(bool|array $surveyCollection, array $resData): array
    {
        foreach ($surveyCollection as $answers) {
            $resData['surveyList'][$answers['survey_id']]['survey_id'] = $answers['survey_id'];
            $resData['surveyList'][$answers['survey_id']]['surveysTitle'] = $answers['surveysTitle'];
            $resData['surveyList'][$answers['survey_id']]['answers'][$answers['id']] =
                [
                    'answerTitle' => $answers['answerTitle'],
                    'numberVotes' => $answers['number_votes']
                ];
            $resData['surveyList'][$answers['survey_id']]['status'] = $answers['status'];
            $resData['surveyList'][$answers['survey_id']]['date_published'] = $answers['date_published'];
        }
        return $resData;
    }
}