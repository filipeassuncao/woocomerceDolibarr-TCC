<?php

namespace App\Jobs;

use App\Events\ProductsInOrderEvent;
use App\Http\Stages\ProductStage;
use App\Models\Dolibarr\ProductDolibarr;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class ProductJob extends Job
{
    use  InteractsWithQueue, Queueable;

    private $resource;
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($resource, array $data)
    {
        $this->resource = $resource;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = new ProductDolibarr();
        $stage = new ProductStage();


        if ($this->resource == 'product') {
            $product->fill($stage->convert($this->resource, $this->data));
            $this->createProductInDoli($product);
        }

        if ($this->resource == 'order') {
            $productsInOrder = $stage->convert($this->resource, $this->data);

            foreach ($productsInOrder as $productInOrder) {
                $product->fill($productInOrder);
                $this->createProductInDoli($product);
            }

            event(new ProductsInOrderEvent($this->data));
        }
    }

    /**
     * @param ProductDolibarr $product
     * @return string
     */
    public function createProductInDoli(ProductDolibarr $product)
    {
        $response = Http::withHeaders(['DOLAPIKEY' => env('DOLAPIKEY')])
            ->post(env('PRODUCT_URL'),
                $product->getAttributes());

        $this->verifyIsRequestError($response->status());
    }
}
