<?php


namespace Aneed\TronAPI;
use IEXBase\TronAPI\Exception\TRC20Exception;
use IEXBase\TronAPI\Exception\TronException;
use IEXBase\TronAPI\Provider\HttpProvider;


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
     * @param $number
     * @return array Returns the address and privateKey
     */
    static public function createdAddress(int $number) {

        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');

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

    /**
     * Send TRX to arr Address
     *
     * @param string $address
     * @param string $private
     * @param array $arrAddressAndAmount
     * @return array
     * @throws TronException
     */
    static public function sendTRX(string $address, string $private, array $arrAddressAndAmount = [])
    {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        try {
            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            exit($e->getMessage());
        }
        $tron->setAddress($address);
        $tron->setPrivateKey($private);
        $sendArr = array();
        foreach ($arrAddressAndAmount as $key => $value) {
            $send = $tron->send( $value->address, $value->amount);
            array_push($sendArr, $send);
        }
        return $sendArr;
    }


    /**
     * Send TRX to arr Address
     *
     * @param string $address
     * @param string $private
     * @param string $contract
     * @param array $arrAddressAndAmount
     * @return array
     * @throws TronException
     * @throws TRC20Exception
     */
    static public function sendToken(string $address, string $private, string $contract, array $arrAddressAndAmount = [])
    {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        try {
            $tron = new \IEXBase\TronAPI\Tron($fullNode, $solidityNode, $eventServer);
        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            exit($e->getMessage());
        }
        $tron->setAddress($address);
        $tron->setPrivateKey($private);
        $sendArr = array();
        $contract = $tron->contract($contract);
        foreach ($arrAddressAndAmount as $key => $value) {
            $send = $contract->transfer($value->address, $value->amount, $address);
            array_push($sendArr, $send);
        }
        return $sendArr;
    }

}
