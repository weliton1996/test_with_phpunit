<?php

namespace OrderBundle\Test\Validators;

use OrderBundle\Validators\NumericValidator;
use PHPUnit\Framework\TestCase;

class NumericValidatorTest extends TestCase {


    public function valueProvider() { //Cenários
        return [
            "ShouldBeValidWhenValueIsANumber" => ["value" => 10, "expectedResult" => true],
            "ShouldBeValidWhenValueIsANumericString" => ["value" => '10', "expectedResult" => true],
            "ShouldNotBeValidWhenValueIsNotANumber" => ["value" => "something", "expectedResult" => false],
            "ShouldNotBeValidWhenValueIsEmpty" => ["value" => "", "expectedResult" => false],
        ];
    }

    /**
     * @dataProvider valueProvider
     */
    public function testIsValid ($value, $expectedResult) { //Usando data provider

        $numericValidator = new NumericValidator($value);

        $isValid = $numericValidator->isValid();

        $this->assertEquals($expectedResult,$isValid);
    }

}