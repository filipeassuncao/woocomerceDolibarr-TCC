<?php

namespace App\Jobs;

use App\Http\Stages\OrderStage;
use App\Models\Dolibarr\OrderDolibarr;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OrderJob extends Job
{
    use  InteractsWithQueue, Queueable;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order = new OrderDolibarr();
        $stage = new OrderStage();
        $customerId = $this->getCustomerInDoli($this->data['billing']['email']);
        $productsIds = $this->getProductsInDoli($this->data['line_items']);
        $order->fill($stage->convert($this->data, $customerId, $productsIds));
        $orderId = $this->createOrderInDoli($order);
        $this->validateOrderInDoli($orderId);
    }

    public function getCustomerInDoli($email)
    {
        $response = Http::withHeaders(['DOLAPIKEY' => '123'])
            ->get(env('CUSTOMER_URL') . "/email/$email");

        return $response['id'];
    }

    public function getProductsInDoli($products)
    {
        $ids = [];
        foreach ($products as $key => $product) {
            $ref = Str::ascii(str_replace(' ', '_', $product['name']));
            $response = Http::withHeaders(['DOLAPIKEY' => '123'])
                ->get(env('PRODUCT_URL') . "/ref/$ref");
            $ids[$key] = $response['id'];
        }

        return $ids;
    }

    /**
     * @param OrderDolibarr $order
     * @return string
     */
    public function createOrderInDoli(OrderDolibarr $order)
    {

        $response = Http::withHeaders(['DOLAPIKEY' => '123'])
            ->post(env('ORDER_URL'),
                $order->getAttributes());
        $this->verifyIsRequestError($response->status());

        return (int)$response->json();
    }

    /**
     * @param $orderId
     * @return string
     */
    public function validateOrderInDoli($orderId)
    {
        $response = Http::withHeaders(['DOLAPIKEY' => '123'])
            ->post(env('ORDER_URL') . "/$orderId/validate");
        $this->verifyIsRequestError($response->status());
    }
}
