<?php

header('Content-Type: application/json');

$usernames = [
    'jeffreyway',
    'johndoe',
    'johnseymour',
    'johnseymour2',
    'johnseymour3',
    'johnseymour4',
    'johnseymour5',
    'janedoe',
    'suziedoe'
];

$results = array_slice(array_values(array_filter($usernames, function($name) {
    return $_GET['q'] ? strpos($name, $_GET['q']) === 0 : false;
})), 0, 5);

echo json_encode($results);
