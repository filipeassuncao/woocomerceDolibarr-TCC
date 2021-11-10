<?php

namespace Customer;

use App\Jobs\CustomerJob;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use TestCase;

/**
 * Class CustomerTest
 * @package Customer
 * @runTestsInSeparateProcesses
 */
class CustomerTest extends TestCase
{
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = \Faker\Factory::create('pt_BR');
    }
    /**
     * @test
     */
    public function should_be_receive_webhook()
    {
        $this->withoutMiddleware();
        $this->withoutJobs();
        $response = $this->post('webhook-customer');

        $response->assertResponseOk();
    }

    /**
     * @test
     */
    public function should_be_dispatches()
    {
        Bus::fake();
        Bus::dispatch(new CustomerJob('', []));
        Bus::assertDispatched(CustomerJob::class, 1);
    }

    /**
     * @test
     */
    public function should_be_queues()
    {
        Queue::fake();
        dispatch(new CustomerJob('', []));
        Queue::assertPushed(CustomerJob::class, 1);
    }

    /**
     * @test
     */
    public function should_be_customer_created_at_dolibarr()
    {

        $this->withoutMiddleware();

        $email = $this->faker->email;
        $data = [
            'username' => 'teste',
            'email' => $email
        ];
        $response = $this->json('post', 'webhook-customer', $data, [
            'DOLAPIKEY' => env('DOLAPIKEY'),
            'x-wc-webhook-resource' => 'customer'
        ]);

        $response->assertResponseOk();

        $response = Http::withHeaders(['DOLAPIKEY' => '123'])
            ->get(env('CUSTOMER_URL') . "/email/$email");


        $this->assertEquals($email, $response['email']);
    }
}
