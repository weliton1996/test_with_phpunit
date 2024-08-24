<?php

namespace OrderBundle\Test\Validators;

use OrderBundle\Validators\CreditCardNumberValidator;
use PHPUnit\Framework\TestCase;

class CreditCardNumberValidatorTest extends TestCase {

    
    public function valueProvider() { //Cenários
        return [
            "ShouldBeValidWhenValueIsACreditCard" => ["value" => 1022321546512315, "expectedResult" => true],
            "ShouldBeValidWhenValueIsACreditCardAsString" => ["value" => "1022321546512315", "expectedResult" => true],
            "ShouldNotBeValidWhenValueIsNotACreditCard" => ["value" => 1, "expectedResult" => false],
            "ShouldNotBeValidWhenValueIsEmpty" => ["value" => "", "expectedResult" => false],
        ];
    }
    
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expectedResult) { //Usando data provider
        
        $creditCardNumberValidator = new CreditCardNumberValidator($value);

        $isValid = $creditCardNumberValidator->isValid();

        $this->assertEquals($expectedResult,$isValid);
    }

}
