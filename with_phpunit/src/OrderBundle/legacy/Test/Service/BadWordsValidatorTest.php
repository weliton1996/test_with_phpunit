<?php

namespace OrderBundle\Test\Service;

use OrderBundle\Repository\BadWordsRepository;
use OrderBundle\Service\BadWordsValidator;
use PHPUnit\Framework\TestCase;

class BadWordsValidatorTest extends TestCase {

    //Coloque no lugar do badWordsDataProvider para testa
    //@dataProvider sentencesWithAndWithoutBadWordsProvider
    /**
     * @test
     * @dataProvider badWordsDataProvider
     * 
     */
    public function hasBadWords($badWordsList,$text, $foundBadWords) {
        //Com Stubs
        // $badWordsRepository = new BadWordsRepositoryStub();
        // $badWordsValidator = new BadWordsValidator($badWordsRepository);

        //Com mocks && sentencesWithAndWithoutBadWordsProvider:
        // $badWordsRepository = $this->createMock(BadWordsRepository::class);
        // $badWordsRepository->method('findAllAsArray')
        //     ->willReturn(['bobo', 'abestado', 'babaca', 'mané', 'pateta']);

        // $badWordsValidator = new BadWordsValidator($badWordsRepository);

        // $hasBadWords = $badWordsValidator->hasBadWords($value);

        //Com mocks && badWordsDataProvider:
        $badWordsRepository = $this->createMock(BadWordsRepository::class);
        $badWordsRepository->method('findAllAsArray')
            ->willReturn($badWordsList);

        $badWordsValidator = new BadWordsValidator($badWordsRepository);

        $hasBadWords = $badWordsValidator->hasBadWords($text);

        $this->assertEquals($foundBadWords,$hasBadWords);
    }

    public function sentencesWithAndWithoutBadWordsProvider() {
        return [
            ['value'=> 'Você é um bobo!', 'expectedValue' => true],
            ['value'=> 'Que abestado você é.', 'expectedValue' => true],
            ['value'=> 'Só um babaca diria isso.', 'expectedValue' => true],
            ['value'=> 'Não seja um mané!', 'expectedValue' => true],
            ['value'=> 'Que coisa de pateta!', 'expectedValue' => true],

            ['value'=> 'Você é muito legal!', 'expectedValue' => false],
            ['value'=> 'Que pessoa incrível você é.', 'expectedValue' => false],
            ['value'=> 'Só alguém muito inteligente diria isso.', 'expectedValue' => false],
            ['value'=> 'Não seja tímido!', 'expectedValue' => false],
            ['value'=> 'Que coisa maravilhosa!', 'expectedValue' => false],
        ];                
    } 

    public function badWordsDataProvider() {
        return [
            'shouldFindWhenHasBadWords' => [
                'badWordsList' => ['bobo', 'chule', 'besta'],
                'text' => 'Seu restaurante e muito bobo',
                'foundBadWords' => true
            ],
            'shouldNotFindWhenHasNoBadWords' => [
                'badWordsList' => ['bobo', 'chule', 'besta'],
                'text' => 'Trocar batata por salada',
                'foundBadWords' => false
            ],
            'shouldNotFindWhenTextIsEmpty' => [
                'badWordsList' => ['bobo', 'chule', 'besta'],
                'text' => '',
                'foundBadWords' => false
            ],
            'shouldNotFindWhenBadWordsListIsEmpty' => [
                'badWordsList' => [],
                'text' => 'Seu restaurante e muito bobo',
                'foundBadWords' => false
            ]
        ];
    }
}