<?php
return [
    'api/login' => [
        'controller' => 'api',
        'action' => 'login',
    ],
    'api/surveys' => [
        'controller' => 'api',
        'action' => 'surveys',
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
