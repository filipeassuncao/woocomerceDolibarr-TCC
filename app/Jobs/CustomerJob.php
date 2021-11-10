<?php

namespace App\Jobs;

use App\Http\Stages\CustomerStage;
use App\Models\Dolibarr\CustomerDolibarr;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Http;

class CustomerJob extends Job
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
        $customer = new CustomerDolibarr();

        $stage = new CustomerStage();

        $customer->fill($stage->convert($this->resource, $this->data));

        $response = Http::withHeaders(['DOLAPIKEY' => env('DOLAPIKEY')])
            ->post(env('CUSTOMER_URL'),
                $customer->getAttributes());

        $this->verifyIsRequestError($response->status());
    }
}
