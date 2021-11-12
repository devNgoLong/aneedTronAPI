<?php


namespace Aneed\TronAPI;
use IEXBase\TronAPI\Tron;


class AneedTronAPI
{
    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
    }

    /**
     * Friendly welcome
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function echoPhrase($phrase)
    {
        return $phrase;
    }

    public function createdAddress($number) {
        $client = new \GuzzleHttp\Client();

        $i = 0;
        $arrAddress = array();

        while ($i < $number) {
            $i++;
            $response = $client->request('GET', 'https://api.shasta.trongrid.io/wallet/generateaddress', [
                'headers' => [
                    'Accept' => 'application/json',
                ],
            ]);
            array_push($arrAddress, $response->getBody());
        }

        return $arrAddress;
    }
}
