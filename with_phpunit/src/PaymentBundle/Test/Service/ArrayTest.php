<?php

namespace PaymentBundle\Test\Service;

use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    private $array;

    //Semelhante ao setUp(), porém é chamado somente uma vez e se mantem o estado para todos os testes, ele é geralmente usado com test de integração.
    public static function setUpBeforeClass() 
    {

    }

    /**
     * @test
     */
    public function shouldBeEmpty()
    {
        $this->assertEmpty($this->array);
    }

    /**
     * @test
     */
    public function shouldBeFilled()
    {
        $this->array = ['hello' => 'world'];

        $this->assertNotEmpty($this->array);
    }

    public static function tearDownAfterClass() 
    {

    }
}