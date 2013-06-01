<?php
return function(MongoDB $db) {
    $collection = $db->users;

    return [
        'exists' => function(array $conditions = array()) use($collection) {
            return $collection->findOne($conditions) !== null;
        },
    ];
};
