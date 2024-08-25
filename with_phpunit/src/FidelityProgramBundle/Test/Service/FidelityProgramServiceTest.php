<?php

namespace FidelityProgramBundle\Test\Service;

use FidelityProgramBundle\Repository\PointsRepository;
use FidelityProgramBundle\Test\Service\PointsRepositorySty;
use FidelityProgramBundle\Service\FidelityProgramService;
use FidelityProgramBundle\Service\PointsCalculator;
use MyFramework\LoggerInterface;
use OrderBundle\Entity\Customer;
use PHPUnit\Framework\TestCase;

class FiFidelityProgramServiceTest extends TestCase {
    /**
     * @test
     */
    public function shouldSaveWhenReceivePoints() {

        $pointsRepository = $this->createMock(PointsRepository::class);
        //Aqui, eu garanto a verificação do meu teste. Se tudo ocorrer como esperado com o método addPoints(), o método save() será chamado exatamente uma vez, confirmando a validação do teste.
        $pointsRepository->expects($this->once())
            ->method('save');

        //Usando o dublê Spy
        // $pointsRepository = new PointsRepositorySpy();

        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $pointsCalculator->method('calculatePointsToReceive')
            ->willReturn(100);

        //Com Spy
        $logger = $this->createMock(LoggerInterface::class);
        $logger->method('log')
            ->will($this->returnCallback(
                function ($message) use (&$allMessages){
                    $allMessages[] = $message;
                }
            ));

        $fidelityProgramService = new FidelityProgramService($pointsRepository, $pointsCalculator, $logger);

        $customer = $this->createMock(Customer::class);
        $value = 50;

        $fidelityProgramService->addPoints($customer, $value);

        //Usando o dublê Spy
        // $this->assertTrue($pointsRepository->called());

        $expectedMessages = [
            'Checking points for customer',
            'Customer received points'
        ];

        $this->assertEquals($expectedMessages, $allMessages);
    }


    /**
     * @test
     */
    public function shouldNotSaveWhenReceiveZeroPoints() {

        $pointsRepository = $this->createMock(PointsRepository::class);
        //Aqui, eu garanto a verificação do meu teste. Se tudo ocorrer como esperado com o método addPoints(), o método save() será chamado exatamente uma vez, confirmando a validação do teste.
        $pointsRepository->expects($this->never())
            ->method('save');


        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $pointsCalculator->method('calculatePointsToReceive')
            ->willReturn(0);

        $logger = $this->createMock(LoggerInterface::class);

        $fidelityProgramService = new FidelityProgramService($pointsRepository, $pointsCalculator, $logger);

        //dummies são dublês criados apenas para satisfazer uma injeção de dependência necessária para que o teste possa ser executado
        $customer = $this->createMock(Customer::class);
        $value = 20;

        $fidelityProgramService->addPoints($customer, $value);

    }
}