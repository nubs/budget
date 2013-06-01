<?php
return function(MongoDB $db) {
    $collection = $db->budgets;

    return [
        'find' => function(array $conditions = array()) use($collection) {
            return array_map(function($budget) {
                $budget['total'] = array_sum(array_map(function($item) { return $item['amount']; }, $budget['items']));
                $budget['overrun'] = $budget['total'] > 0;

                return $budget;
            }, iterator_to_array($collection->find($conditions)));
        },
    ];
};