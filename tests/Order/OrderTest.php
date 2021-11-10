<?php

namespace Order;

use App\Jobs\OrderJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use TestCase;

/**
 * Class OrderTest
 * @package Order
 * @runTestsInSeparateProcesses
 */
class OrderTest extends TestCase
{
    /**
     * @test
     */
    public function should_be_receive_webhook()
    {
        $this->withoutMiddleware();
        $this->withoutJobs();
        $response = $this->post('webhook-order');

        $response->assertResponseOk();
    }

    /**
     * @test
     */
    public function should_be_dispatches()
    {
        Bus::fake(); // faking the Bus command
        Bus::dispatch(new OrderJob([]));
        Bus::assertDispatched(OrderJob::class, 1);
    }

    /**
     * @test
     */
    public function should_be_queues()
    {
        Queue::fake(); // faking Queue using the facade
        dispatch(new OrderJob([]));
        Queue::assertPushed(OrderJob::class, 1);
    }

    /**
     * @test
     */
    public function should_be_order_created_at_dolibarr()
    {
        $this->withoutMiddleware();

        $data = [
            "id" => 247,
            "parent_id" => 0,
            "status" => "on-hold",
            "currency" => "BRL",
            "version" => "5.5.2",
            "prices_include_tax" => false,
            "date_created" => "2021-09-12T18:14:58",
            "date_modified" => "2021-09-12T18:14:58",
            "discount_total" => "0.00",
            "discount_tax" => "0.00",
            "shipping_total" => "0.00",
            "shipping_tax" => "0.00",
            "cart_tax" => "0.00",
            "total" => "299.00",
            "total_tax" => "0.00",
            "customer_id" => 1,
            "order_key" => "wc_order_oa9mfB3cHI2j6",
            "billing" => [
                "first_name" => "Filipe",
                "last_name" => "Assunção",
                "company" => "",
                "address_1" => "Servidão marcelino antonio dos santos",
                "address_2" => "277",
                "city" => "Santo Amaro da Imperatriz",
                "state" => "SC",
                "postcode" => "88140-000",
                "country" => "BR",
                "email" => "assuncaofi444lipe32397@gmail.com",
                "phone" => "(48) 92000-6382",
                "number" => "277",
                "neighborhood" => "",
                "persontype" => "F",
                "cpf" => "05264867917",
                "rg" => "",
                "cnpj" => "",
                "ie" => "",
                "birthdate" => "",
                "sex" => "",
                "cellphone" => ""
            ],
            "shipping" => [
                "first_name" => "",
                "last_name" => "",
                "company" => "",
                "address_1" => "",
                "address_2" => "",
                "city" => "",
                "state" => "",
                "postcode" => "",
                "country" => "",
                "number" => "",
                "neighborhood" => ""
            ],
            "payment_method" => "bacs",
            "payment_method_title" => "Transferência bancária",
            "line_items" => [
                [
                    "id" => 223,
                    "name" => "Tênis New Balance 571",
                    "product_id" => 11,
                    "variation_id" => 0,
                    "quantity" => 1,
                    "tax_class" => "",
                    "subtotal" => "299.00",
                    "subtotal_tax" => "0.00",
                    "total" => "299.00",
                    "total_tax" => "0.00",
                    "sku" => "NB-99",
                    "price" => 299,
                    "parent_name" => null
                ]
            ],
            "currency_symbol" => "R$"

        ];

        $response = $this->json('post', 'webhook-order', $data, [
            'DOLAPIKEY' => env('DOLAPIKEY'),
            'x-wc-webhook-resource' => 'order'
        ]);


        $response->assertResponseOk();

        $response = Http::withHeaders(['DOLAPIKEY' => '123'])
            ->get(env('ORDER_URL'))->collect();

        $this->assertEquals(1631404800, $response->last()['date']);

    }
}
