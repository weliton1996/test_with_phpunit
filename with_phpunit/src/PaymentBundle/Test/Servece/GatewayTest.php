<?php

namespace PaymentBundle\Test\Service;
use MyFramework\HttpClientInterface;
use MyFramework\LoggerInterface;
use PaymentBundle\Service\Gateway;
use PHPUnit\Framework\TestCase;

class GatewayTest extends TestCase 
{
    /**
     * @test
     * @dataProvider AuthenticationAndPaymentDataProvider
     */
    public function shouldSuccessFullyPayWhenGatewayRuturnOk($user, $password,$credit_card_number, $expectedValue)
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient->method('send')
        ->will($this->returnCallback(
            function($method, $address, $body){
                return $this->fakeHttpClientSend($method, $address, $body);
            }
        ));

        $logger = $this->createMock(LoggerInterface::class);
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $paid = $gateway->pay(
            'Weliton', 
            $credit_card_number,
            new \DateTime('now'),
            100
        );

        $this->assertEquals($expectedValue, $paid);
    }

    public function fakeHttpClientSend($method, $address, $body) 
    {
         switch ($address) {
            case Gateway::BASE_URL. '/authenticate':
                if($body['password'] !== 'valid-password') 
                    return null;

                return 'token-generated';
            case Gateway::BASE_URL . '/pay':
                if($body['credit_card_number'] === 99999999999)
                    return ['paid' => true];

                return ['paid' => false];
         }
    }

    public function AuthenticationAndPaymentDataProvider(){
        return [
            'shouldSuccessFullyPayWhenGatewayRuturnOk' => ['user' => 'Fulano Da Silva', 'password' => 'valid-password', 'credit_card_number' => 99999999999, 'expectedValue' => true],
            'shouldNotPayWhenFailOnGateway' => ['user' => 'Fulano Da Silva', 'password' => 'valid-password', 'credit_card_number' => 99999913213, 'expectedValue' => false],
            'shouldNotPayWhenAuthenticationFail' => ['user' => 'Fulano Da Silva', 'password' => 'invalid-password', 'credit_card_number' => 99999999999, 'expectedValue' => false],
            'shouldNotPayWhenAuthenticationFailedAndPayment' => ['user' => 'Fulano Da Silva', 'password' => 'invalid-password', 'credit_card_number' => 99999992132, 'expectedValue' => false],
        ];
    }
}
