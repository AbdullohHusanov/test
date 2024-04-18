<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    
    public function getCurrencies()
    {
        $client = new Client();
        $data = $client->get('openexchangerates.org/api/currencies.json?prettyprint=false&show_alternative=false&show_inactive=false&app_id=1');
        $data = json_decode($data->getBody(), true);

        $data_dd = [];
        
        foreach ($data as $code => $c_name) {
            $data_dd[] = [
                'code' => $code,
                'country_name' => $c_name,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        Currency::insert($data_dd);
        
        return response()->json([
            'status' => true,
            'message' => 'currency list',
            'result' => $data
        ]);
    }
}
