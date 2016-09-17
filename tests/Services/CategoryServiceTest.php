<?php

use App\Services\CategoryService;
use Schema\Client;
use Schema\Collection;

/**
 * @property CategoryService service
 */
class CategoryServiceTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();

        $this->service = new CategoryService(app(Client::class));
    }

    public function test_all()
    {
        $result = $this->service->all();

        $this->assertInstanceOf(Collection::class, $result);
    }


}