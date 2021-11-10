<?php

namespace Product;

use App\Jobs\ProductJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use TestCase;

class ProductTest extends TestCase
{
    /**
     * @test
     */
    public function should_be_receive_webhook()
    {
        $this->withoutMiddleware();
        $this->withoutJobs();
        $response = $this->post('webhook-product');

        $response->assertResponseOk();
    }

    /**
     * @test
     */
    public function should_be_dispatches()
    {
        Bus::fake();
        Bus::dispatch(new ProductJob('', []));
        Bus::assertDispatched(ProductJob::class, 1);
    }

    /**
     * @test
     */
    public function should_be_queues()
    {
        Queue::fake();
        dispatch(new ProductJob('', []));
        Queue::assertPushed(ProductJob::class, 1);
    }

    /**
     * @test
     */
    public function should_be_product_created_at_dolibarr()
    {
        $this->withoutMiddleware();

        $data = [
            'name' => 'abc',
            'sku' => 'nk-99'
        ];
        $response = $this->json('post', 'webhook-product', $data, [
            'DOLAPIKEY' => env('DOLAPIKEY'),
            'x-wc-webhook-resource' => 'product'
        ]);

        $response->assertResponseOk();

        $response = Http::withHeaders(['DOLAPIKEY' => '123'])
            ->get(env('PRODUCT_URL')."/ref/abc");

        $this->assertNotEmpty($response['label']);
    }
}
