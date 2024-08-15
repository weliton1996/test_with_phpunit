<?php

class DiscountCalculatorTest {

    public function ShouldApply_WhenValueIsAbouveTheMinimumTest() {

        $discountCalculator = new DiscountCalculator();

        $totalValue = 130;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        $expectedValue = 110;
        $this->assertEquals($expectedValue, $totalWithDiscount);
    }

    public function ShouldNotApply_WhenValueIsBellowTheMinimumTest() {

        $discountCalculator = new DiscountCalculator();

        $totalValue = 90;
        $totalWithDiscount = $discountCalculator->apply($totalValue);

        $expectedValue = 90;
        $this->assertEquals($expectedValue, $totalWithDiscount);
    }
    public function assertEquals($expectedValue,$actualValue) {

        if($expectedValue !== $actualValue){

            $message = 'Expected: '.$expectedValue.' but got: '.$actualValue.'\n';
            throw new \Exception($message);
        }

        echo "Test passed! \n";
    }
}