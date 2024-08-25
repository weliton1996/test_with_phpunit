<?php

namespace FidelityProgramBundle\Test\Service;
use FidelityProgramBundle\Repository\PointsRepository;
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

        $pointsCalculator = $this->createMock(PointsCalculator::class);
        $pointsCalculator->method('calculatePointsToReceive')
            ->willReturn(100);
        $logger = $this->createMock(LoggerInterface::class);

        $fidelityProgramService = new FidelityProgramService($pointsRepository, $pointsCalculator, $logger);

        $customer = $this->createMock(Customer::class);
        $value = 50;

        $fidelityProgramService->addPoints($customer, $value);
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

        $customer = $this->createMock(Customer::class);
        $value = 20;

        $fidelityProgramService->addPoints($customer, $value);
    }
}