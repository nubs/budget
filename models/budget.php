<?php
return function(MongoDB $db) {
    $collection = $db->budgets;

    $populateBudget = function($budget) {
        $budget['total'] = array_sum(array_map(function($item) { return $item['amount']; }, $budget['items']));
        $budget['overrun'] = $budget['polarity'] * $budget['total'] > 0;

        return $budget;
    };

    $findOne = function($id) use($collection, $populateBudget) {
        return $populateBudget($collection->findOne(['_id' => new MongoID($id)]));
    };

    return [
        'find' => function(array $conditions = array()) use($collection, $populateBudget) {
            return array_map($populateBudget, iterator_to_array($collection->find($conditions)->sort(['polarity' => -1, 'title' => 1])));
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
        'snapshot' => function($id) use ($collection, $findOne, $removeItem) {
            $budget = $findOne($id);

            $collection->update(
                ['_id' => new MongoID($id)],
                ['$set' => ['items' => [['description' => 'Snapshot on ' . date('Y-m-d'), 'amount' => $budget['total']]]]]
            );
        },
    ];
};
