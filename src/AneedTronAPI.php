<?php


namespace Aneed\TronAPI;
use IEXBase\TronAPI\Exception\TronException;
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

        $fullNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
        $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');
        $eventServer = new \IEXBase\TronAPI\Provider\HttpProvider('https://api.trongrid.io');

        try {
            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            exit($e->getMessage());
        }

        $i = 0;
        $arrAddress = array();

        while ($i < $number) {
            $i++;
            try {
                array_push($arrAddress, $tron->generateAddress());
            } catch (TronException $e) {

            }
        }

        return $arrAddress;
    }
}
