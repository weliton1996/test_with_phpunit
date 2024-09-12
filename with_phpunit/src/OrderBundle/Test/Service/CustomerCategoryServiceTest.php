<?php

namespace OrderBundle\Test\Service;
use OrderBundle\Entity\Customer;
use OrderBundle\Service\CustomerCategoryService;
use OrderBundle\Service\HeavyUserCategory;
use OrderBundle\Service\LightUserCategory;
use OrderBundle\Service\MediumUserCategory;
use OrderBundle\Service\NewUserCategory;
use PHPUnit\Framework\TestCase;

class CustomerCategoryServiceTest extends TestCase
{
    private $customerCategoryService;

    protected function setUp()
    {
        $this->customerCategoryService = new CustomerCategoryService();
        $this->customerCategoryService->addCategory(new HeavyUserCategory());
        $this->customerCategoryService->addCategory(new MediumUserCategory());
        $this->customerCategoryService->addCategory(new LightUserCategory());
        $this->customerCategoryService->addCategory(new NewUserCategory());
    }

    /**
     * @test
     */
    public function customerShouldBeNewUser()
    {
        $customer = new Customer();
        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_NEW_USER, $usageCategory);
    }

    /**
     * @test
     */
    public function customerShouldBeLightUser()
    {
        $customer = new Customer();
        $customer->setTotalOrders(5);
        $customer->setTotalRatings(1);
        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_LIGHT_USER, $usageCategory);
    }

    /**
     * @test
     */
    public function customerShouldBeMediumUser()
    {
        $customer = new Customer();
        $customer->setTotalOrders(20);
        $customer->setTotalRatings(5);
        $customer->setTotalRecommendations(1);
        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_MEDIUM_USER, $usageCategory);
    }

    /**
     * @test
     */
    public function customerShouldBeHeavyUser()
    {
        $customer = new Customer();
        $customer->setTotalOrders(50);
        $customer->setTotalRatings(10);
        $customer->setTotalRecommendations(5);
        $usageCategory = $this->customerCategoryService->getUsageCategory($customer);

        $this->assertEquals(CustomerCategoryService::CATEGORY_HEAVY_USER, $usageCategory);
    }

    protected function tearDown(): void
    {
        $this->customerCategoryService = null;
        parent::tearDown();
    }

    //Remove um por um.
    // protected function tearDown(): void
    // {
    //     // Remove as categorias uma por uma na ordem inversa de como foram adicionadas
    //     $this->customerCategoryService->removeCategory(NewUserCategory::class);
    //     $this->customerCategoryService->removeCategory(LightUserCategory::class);
    //     $this->customerCategoryService->removeCategory(MediumUserCategory::class);
    //     $this->customerCategoryService->removeCategory(HeavyUserCategory::class);
    //     $this->customerCategoryService = null;
    //     parent::tearDown();
    // }

    //Antes
    // /**
    //  * @test
    //  */
    // public function customerShouldBeNewUser()
    // {
    //     $customerCategoryService = new CustomerCategoryService();
    //     $customerCategoryService->addCategory(new NewUserCategory());

    //     $customer = new Customer();
    //     $usageCategory = $customerCategoryService->getUsageCategory($customer);

    //     $this->assertEquals(CustomerCategoryService::CATEGORY_NEW_USER, $usageCategory);
    // }

    // /**
    //  * @test
    //  */
    // public function customerShouldBeLightUser()
    // {
    //     $customerCategoryService = new CustomerCategoryService();
    //     $customerCategoryService->addCategory(new LightUserCategory());

    //     $customer = new Customer();
    //     $customer->setTotalOrders(5);
    //     $customer->setTotalRatings(1);
    //     $usageCategory = $customerCategoryService->getUsageCategory($customer);

    //     $this->assertEquals(CustomerCategoryService::CATEGORY_LIGHT_USER, $usageCategory);
    // }

    // /**
    //  * @test
    //  */
    // public function customerShouldBeMediumUser()
    // {
    //     $customerCategoryService = new CustomerCategoryService();
    //     $customerCategoryService->addCategory(new MediumUserCategory());

    //     $customer = new Customer();
    //     $customer->setTotalOrders(20);
    //     $customer->setTotalRatings(5);
    //     $customer->setTotalRecommendations(1);
    //     $usageCategory = $customerCategoryService->getUsageCategory($customer);

    //     $this->assertEquals(CustomerCategoryService::CATEGORY_MEDIUM_USER, $usageCategory);
    // }

    // /**
    //  * @test
    //  */
    // public function customerShouldBeHeavyUser()
    // {
    //     $customerCategoryService = new CustomerCategoryService();
    //     $customerCategoryService->addCategory(new HeavyUserCategory());

    //     $customer = new Customer();
    //     $customer->setTotalOrders(50);
    //     $customer->setTotalRatings(10);
    //     $customer->setTotalRecommendations(5);
    //     $usageCategory = $customerCategoryService->getUsageCategory($customer);

    //     $this->assertEquals(CustomerCategoryService::CATEGORY_HEAVY_USER, $usageCategory);
    // }
}