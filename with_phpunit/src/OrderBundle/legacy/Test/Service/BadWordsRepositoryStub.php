<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepositoryInterface;

class BadWordsRepositoryStub implements BadWordsRepositoryInterface {

    public function findAllAsArray() {
        return ['bobo', 'abestado', 'babaca', 'mané', 'pateta'];
    }
}