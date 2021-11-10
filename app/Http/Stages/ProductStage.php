<?php

namespace App\Http\Stages;

class ProductStage
{
    private $status = 0;

    public function convert($resource, array $data)
    {
        if ($resource == 'product') {

            if(isset($data['status']) && $data['status'] == 'publish') {
                $this->status = 1;
            }

            return [
                'ref' => $data['name'] ?? null,
                'label' => $data['sku'] ?? null,
                'description' => $data['description'] ?? null,
                'price' => $data['price'] ?? null,
                'price_min' => $data['sale_price'] ?? null,
                'price_min_ttc' => $data['sale_price'] ?? null,
                'status' => $this->status,
                'status_buy' => 1,
                'finished' => 1,
                'weight' => $data['weight'] ?? null,
                'length' => $data['dimensions']['length'] ?? null,
                'width' => $data['dimensions']['width'] ?? null,
                'height' => $data['dimensions']['height'] ?? null,
                'data_creation' => $data['date_created'] ?? null,
                'date_modification' => $data['date_modified'] ?? null,
                'fk_default_warehouse' => 1,
            ];
        }

        if ($resource == 'order') {

            $products = [];
            $orderProducts = $data['line_items'];

            foreach ($orderProducts as $product) {

                $products[] = [
                    'ref' => $product['name'],
                    'label' => $product['sku'],
                    'price' => $product['price'],
                    'price_ttc' => $product['price'],
                    'status' => 1,
                    'status_buy' => 1,
                    'finished' => 1,
                    'fk_default_warehouse' => 1,
                ];
            }

            return $products;
        }

        return null;
    }
}
