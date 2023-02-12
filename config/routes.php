<?php
return [
    'api/surveys' => [
        'controller' => 'api',
        'action' => 'index',
    ],
    'surveys' =>[
        'controller' => 'surveys',
        'action' => 'index',
        ],
    '^$' =>[
        'controller' => 'surveys',
        'action' => 'index',
    ],
    'cabinet' =>[
        'controller' => 'cabinet',
        'action' => 'index',
    ],
    'cabinet/addSurvey' =>[
        'controller' => 'cabinet',
        'action' => 'addSurvey',
    ],
    'cabinet/searchSurveyByTitle' =>[
        'controller' => 'cabinet',
        'action' => 'searchSurveyByTitle',
    ],
    'cabinet/searchSurveyByStatus' =>[
        'controller' => 'cabinet',
        'action' => 'searchSurveyByStatus',
    ],
    'cabinet/searchSurveyByDate' =>[
        'controller' => 'cabinet',
        'action' => 'searchSurveyByDate',
    ],
    'cabinet/edit/([0-9]+)' =>[
        'controller' => 'cabinet',
        'action' => 'edit/$1',
    ],
    'cabinet/updateSurvey/([0-9]+)' =>[
        'controller' => 'cabinet',
        'action' => 'updateSurvey/$1',
    ],
    'cabinet/deletedSurvey/([0-9]+)' =>[
        'controller' => 'cabinet',
        'action' => 'deletedSurvey/$1',
    ],
    'logout' =>[
        'controller' => 'surveys',
        'action' => 'logout',
    ],
    'login' =>[
        'controller' => 'surveys',
        'action' => 'login',
    ],
    'register' =>[
        'controller' => 'surveys',
        'action' => 'register',
    ],
];
//return [
//    'surveys' => 'surveys/list',
//    'register' => 'surveys/register',
//    'login' => 'surveys/login',
//    'logout' => 'surveys/logout',
//    'cabinet/deletedSurvey' => 'cabinet/deletedSurvey',
//    'cabinet/updateSurvey/([0-9]+)' => 'cabinet/updateSurvey/$1',
//    'cabinet/edit/([0-9]+)' => 'cabinet/edit/$1',
//        'cabinet/searchSurveyByStatus' => 'cabinet/searchSurveyByStatus',
//    'cabinet/searchSurveyByTitle' => 'cabinet/searchSurveyByTitle',
//    'cabinet/addSurvey' => 'cabinet/addSurvey',
//    'cabinet' => 'cabinet/index',
////   '^$' => 'surveys/list',
//    '^$' => [SurveysController::class, 'actionList'],
//
//];
