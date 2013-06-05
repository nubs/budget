<?php
return function(MongoDB $db) {
    $collection = $db->budgets;

    $findOne = function($id) use($collection) {
        return $collection->findOne(['_id' => new MongoID($id)]);
    };

    return [
        'find' => function(array $conditions = array()) use($collection) {
            return array_map(function($budget) {
                $budget['total'] = array_sum(array_map(function($item) { return $item['amount']; }, $budget['items']));
                $budget['overrun'] = $budget['total'] > 0;

                return $budget;
            }, iterator_to_array($collection->find($conditions)));
        },
        'findOne' => $findOne,
        'addItem' => function($id, array $item) use($collection) {
            if (empty($item['description']) || empty($item['amount'])) {
                throw new Exception('You must enter both a description and an amount.');
            }

            $collection->update(['_id' => new MongoID($id)], ['$push' => ['items' => $item]]);
        },
        'removeItem' => function($id, $index) use($collection, $findOne) {
            $budget = $findOne($id);
            unset($budget['items'][$index]);

            $collection->update(['_id' => new MongoID($id)], ['$set' => ['items' => array_values($budget['items'])]]);
        },
    ];
};
