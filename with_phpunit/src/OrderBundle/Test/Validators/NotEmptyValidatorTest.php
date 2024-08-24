<?php

namespace OrderBundle\Test\Validators;

use OrderBundle\Validators\NotEmptyValidator;
use PHPUnit\Framework\TestCase;

class NotEmptyValidatorTest extends TestCase {

    
    public function valueProvider() { //Cenários
        return [
            "ShouldBeValidWhenValueIsNotEmpty" => ["value" => "something", "expectedResult" => true],
            "ShouldNotBeValidWhenValueIsEmpty" => ["value" => "", "expectedResult" => false],
        ];
    }

    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expectedResult) { //Usando data provider
        
        $notEmptyValidator = new NotEmptyValidator($value);

        $isValid = $notEmptyValidator->isValid();

        $this->assertEquals($expectedResult,$isValid);

        /*$dataProvider = [
            "" => false,
            "something" => true
        ];

        foreach ($dataProvider as $value => $expectedResult) {
            $notEmptyValidator = new NotEmptyValidator($value);

            $isValid = $notEmptyValidator->isValid();

            $this->assertEquals($expectedResult,$isValid);
        } 
        */
    }

    public function testShouldBeValidWhenValueIsNotEmpty() { //Sem data provider
        $notEmptyValue = "something";
        $notEmptyValidator = new NotEmptyValidator($notEmptyValue);

        $isValid = $notEmptyValidator->isValid();

        $this->assertNotFalse($isValid);
    }
}
