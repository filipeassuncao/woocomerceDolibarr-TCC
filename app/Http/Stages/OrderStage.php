<?php

namespace App\Http\Stages;

class OrderStage
{
    public function convert(array $data, $customerId, $productsIds)
    {
        $products = [];

        $paymentMethod = $this->paymentMethodTransform($data['payment_method']);

        foreach ($data['line_items'] as $key => $product) {
           $products[] =
                [
                    'qty' => $product['quantity'],
                    'fk_product' => $productsIds[$key],
                    'price' => $product['price'],
                    'subprice' => $product['price']
                ];

        }

        return [
            'ref_ext' => 123,
            'socid' => $customerId,
            'mode_reglement' => $paymentMethod['mode_reglement'],
            'mode_reglement_id' => $paymentMethod['mode_reglement_id'],
            'mode_reglement_code' => $paymentMethod['mode_reglement_code'],
            'date_creation' => strtotime($data['date_created']),
            'date' => strtotime($data['date_created']),
            'type' => '0',
            'lines' => $products,
            'multicurrency_tx' => 1.00000000,
            "total_ht" => 299.00000000,
            "total_tva" => 0.00000000,
            "total_localtax1" => 0.00000000,
            "total_localtax2" => 0.00000000,
            "total_ttc" => 299.00000000,
            "specimen" => 0,
            "fk_incoterms" => 0,
            "remise" => 0,
            "modelpdf" => "einstein",
            "warehouse_id" => 1,
            'status' => 1,
            "multicurrency_code" => "BRL",
            'entity' => 1,
        ];
    }

    private function paymentMethodTransform($paymentMethod)
    {
        if ($paymentMethod == 'bacs') {
            return [
                'mode_reglement' => 'Transfer',
                'mode_reglement_id' => 2,
                'mode_reglement_code' => 'VIR'
            ];
        }
        return null;
    }
}
