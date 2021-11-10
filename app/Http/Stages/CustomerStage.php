<?php

namespace App\Http\Stages;

class CustomerStage
{
    public function convert($resource, array $data)
    {
        if($resource == 'customer') {
            return [
                'entity' => 1,
                'name' => $data['username'] ?? null,
                'nameAlias' => $data['username'] ?? null,
                'status' => 1,
                'email' => $data['email'],
                'client' => 1
            ];
        }

        if($resource == 'order') {
           $customer = $data['billing'];
            return [
                'entity' => 1,
                'name' => $customer['first_name'] . ' ' . $customer['last_name'],
                'nameAlias' => $customer['first_name'],
                'address' => $customer['address_1'] ?? null,
                'zip' => $customer['postcode'] ?? null,
                'town' => $customer['city'] ?? null,
                'status' => 1,
                'stateId' => 0,
                'stateCode' => null,
                'state' => $customer['state'] ?? null,
                'phone' => $customer['phone'] ?? null,
                'email' => $customer['email'] ?? null,
                'idprof4' => $customer['cpf'] ?? null,
                'client' => 1
            ];
        }

        return '';
    }
}
