<?php

namespace OrderBundle\Test\Validators;

use OrderBundle\Validators\CreditCardExpirationValidator;
use PHPUnit\Framework\TestCase;

class CreditCardExpirationValidatorTest extends TestCase {

    
    public function valueProvider() { //Cenários
        return [
            "ShouldBeValidWhenDateIsNotExpired" => ["value" => '2030-01-01', "expectedResult" => true],
            "ShouldBeValidWhenDateIsExpired" => ["value" => '2010-01-01', "expectedResult" => false],
        ];
    }
    
    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expectedResult) { //Usando data provider
        
        $creditCardExpirationDate = new \DateTime($value);
        $creditCardExpirationValidator = new CreditCardExpirationValidator($creditCardExpirationDate);

        $isValid = $creditCardExpirationValidator->isValid();

        $this->assertEquals($expectedResult,$isValid);
    }

}
