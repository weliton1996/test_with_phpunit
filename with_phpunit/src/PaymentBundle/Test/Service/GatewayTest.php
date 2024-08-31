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
     */
    public function shouldNotPayWhenAuthenticationFail()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'invalid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                null
            ]
        ];
        $httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValueMap($map));

        $paid = $gateway->pay(
            'Vinicius Oliveira',
            5555444488882222,
            new \DateTime('now'),
            100
        );

        $this->assertEquals(false, $paid);
    }

    /**
     * @test
     */
    public function shouldNotPayWhenFailOnGateway()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $token = 'meu-token';
        $httpClient
            ->expects($this->at(0))
            ->method('send')
            ->willReturn($token);

        $httpClient
            ->expects($this->at(1))
            ->method('send')
            ->willReturn(['paid' => false]);

        $logger
            ->expects($this->once())
            ->method('log')
            ->with('Payment failed');

        $name = 'Vinicius Oliveira';
        $creditCardNumber = 5555444488882222;
        $value = 100;
        $validity = new \DateTime('now');
        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(false, $paid);
    }

    /**
     * @test
     */
    public function shouldSuccessfullyPayWhenGatewayReturnOk()
    {
        $httpClient = $this->createMock(HttpClientInterface::class);
        $logger = $this->createMock(LoggerInterface::class);
        $user = 'test';
        $password = 'valid-password';
        $gateway = new Gateway($httpClient, $logger, $user, $password);

        $name = 'Vinicius Oliveira';
        $creditCardNumber = 9999999999999999;
        $validity = new \DateTime('now');
        $value = 100;
        $token = 'meu-token';
        $map = [
            [
                'POST',
                Gateway::BASE_URL . '/authenticate',
                [
                    'user' => $user,
                    'password' => $password
                ],
                'meu-token'
            ],
            [
                'POST',
                Gateway::BASE_URL . '/pay',
                [
                    'name' => $name,
                    'credit_card_number' => $creditCardNumber,
                    'validity' => $validity,
                    'value' => $value,
                    'token' => $token
                ],
                ['paid' => true]
            ]
        ];
        $httpClient
            ->expects($this->atLeast(2))
            ->method('send')
            ->will($this->returnValueMap($map));

        $paid = $gateway->pay(
            $name,
            $creditCardNumber,
            $validity,
            $value
        );

        $this->assertEquals(true, $paid);
    }

    /**
     * @test
     * @dataProvider usedFakeInAuthenticationAndPaymentDataProvider
     */
    public function testingThePayMethodWithFakes($user, $password,$credit_card_number, $expectedValue)
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

    //Cenarios
    public function usedFakeInAuthenticationAndPaymentDataProvider(){
        return [
            'shouldSuccessFullyPayWhenGatewayRuturnOk' => ['user' => 'Fulano Da Silva', 'password' => 'valid-password', 'credit_card_number' => 99999999999, 'expectedValue' => true],
            'shouldNotPayWhenFailOnGateway' => ['user' => 'Fulano Da Silva', 'password' => 'valid-password', 'credit_card_number' => 99999913213, 'expectedValue' => false],
            'shouldNotPayWhenAuthenticationFail' => ['user' => 'Fulano Da Silva', 'password' => 'invalid-password', 'credit_card_number' => 99999999999, 'expectedValue' => false],
            'shouldNotPayWhenAuthenticationFailedAndPayment' => ['user' => 'Fulano Da Silva', 'password' => 'invalid-password', 'credit_card_number' => 99999992132, 'expectedValue' => false],
        ];
    }

    /**
     * @test
     * @dataProvider usedMocksInAuthenticationAndPaymentDataProvider
     */
    public function testingThePayMethodWithMocks($user, $password,$credit_card_number, $expectedToken, $expected_credit_card_number, $expectedValue)
    {
        $validityDate = new \DateTime('now');
        
        $httpClient = $this->createMock(HttpClientInterface::class);

        $logger = $this->createMock(LoggerInterface::class);

        $gateway = new Gateway($httpClient, $logger, $user, $password);
        
        //Expected authentication and payment success or expected authentication success but payment failure
        if($expectedToken !== null) {
            $map = [
                [
                    'POST',
                    Gateway::BASE_URL . '/authenticate',
                    [
                        'user' => $user,
                        'password' => $password
                    ],
                    $expectedToken 
                ],
                [
                    'POST', 
                    Gateway::BASE_URL . '/pay', 
                    [
                        'name' => 'Weliton',
                        'credit_card_number' => $credit_card_number,
                        'validity' => $validityDate,
                        'value' => 100,
                        'token' => $expectedToken
                    ],
                    ['paid' => $expected_credit_card_number === $credit_card_number ? true : false]
                ],
            ];
            $httpClient
                ->expects($this->exactly(2))
                ->method('send')
                ->will($this->returnValueMap($map));
             
            $paidResponse = $map[1][3]['paid'];
            if ($paidResponse === false) {
                $logger
                    ->expects($this->once())
                    ->method('log')
                    ->with('Payment failed');
            } else {
                $logger
                    ->expects($this->never())
                    ->method('log');
            }        
                
        } 
        //Expected authentication failure
        else {
            $map = [
                [
                    'POST',
                    Gateway::BASE_URL . '/authenticate',
                    [
                        'user' => $user,
                        'password' => $password
                    ],
                    $expectedToken 
                ],
            ];

            $httpClient
                ->expects($this->once())
                ->method('send')
                ->will($this->returnValueMap($map));
            
            $logger
                ->expects($this->once())
                ->method('log')
                ->with('Authentication failed');
        }

        $paid = $gateway->pay(
            'Weliton', 
            $credit_card_number,
            $validityDate,
            100
        );

        $this->assertEquals($expectedValue, $paid);
    }

    //Cenarios
    public function usedMocksInAuthenticationAndPaymentDataProvider(){
        return [
            'shouldSuccessFullyPayWhenGatewayRuturnOk' => [
                'user' => 'Fulano Da Silva', 
                'password' => 'valid-password', 
                'credit_card_number' => 99999999999,
                'expectedToken' => 'token-generated', 
                'expected_credit_card_number' => 99999999999,
                'expectedValue' => true
            ],
            'shouldNotPayWhenFailOnGateway' => [
                'user' => 'Fulano Da Silva', 
                'password' => 'valid-password', 
                'credit_card_number' => 99999913213, 
                'expectedToken' => 'token-generated',
                'expected_credit_card_number' => 99999999999,
                'expectedValue' => false
            ],
            'shouldNotPayWhenAuthenticationFail' => [
                'user' => 'Fulano Da Silva', 
                'password' => 'invalid-password', 
                'credit_card_number' => 99999999999, 
                'expectedToken' => null, 
                'expected_credit_card_number' => 99999999999,
                'expectedValue' => false
            ],
            'shouldNotPayWhenAuthenticationFailedAndPayment' => [
                'user' => 'Fulano Da Silva', 
                'password' => 'invalid-password', 
                'credit_card_number' => 99999992132, 
                'expectedToken' => null, 
                'expected_credit_card_number' => 99999999999,
                'expectedValue' => false
            ],
        ];
    }
}
