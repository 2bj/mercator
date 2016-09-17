<?php

use App\Services\ProductService;
use Schema\Client;
use Schema\Collection;

/**
 * @property ProductService service
 */
class ProductServiceTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->service = new ProductService(app(Client::class));
    }

    public function test_all()
    {
        $result = $this->service->all();

        $this->assertInstanceOf(Collection::class, $result);
    }

}