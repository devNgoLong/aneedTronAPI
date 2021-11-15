<?php


namespace Aneed\TronAPI;
use IEXBase\TronAPI\Exception\TRC20Exception;
use IEXBase\TronAPI\Exception\TronException;
use IEXBase\TronAPI\Provider\HttpProvider;
use IEXBase\TronAPI\Tron;


class AneedTronAPI
{
    /**
     * Create a new TRON custom
     */
    public function __construct()
    {
    }

    /**
     * Created new address
     *
     * @param $number
     * @return array Returns the address and privateKey
     */
    static public function createdAddress(int $number) {

        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');

        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
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
     * @param array $arrAddressAndAmount | ['address' => 'address1', 'amount' => '123']
     * @return array
     * @throws TronException
     */
    static public function sendTRX(string $address, string $private, array $arrAddressAndAmount = [])
    {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
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
     * @param array $arrAddressAndAmount  | ['address' => 'address1', 'amount' => '123']
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
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
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

    /**
     * Send TRX to arr Address
     *
     * @param array $addresses | ['address 1','address 3','address 2']
     * @param array $tokens | ['token 1','token 3','token 2']
     * @return array
     */
    static public function getBalance(array $addresses = [], array $tokens = []) {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
            exit($e->getMessage());
        }

        $balances = array();

        foreach ($addresses as $address) {

            try {
                $tron->setAddress($address);
            } catch (\Exception $e) {
                continue;
            }

            foreach ($tokens as $token) {
                try {
                    $contract = $tron->contract($token);
                    $balance = array('token' => $token, 'balance' => $contract->balanceOf());
                    array_push($balances, $balance);
                } catch (\Exception $e) {
                    continue;
                }
            }
        }
        return $balances;
    }

    /**
     * Send TRX to arr Address
     *
     * @param array $wallets | address and privateKey
     * @param string $addressRecive
     * @param string $tokenContract
     * @return array
     */
    static public function collectorToken(array $wallets = [], string $addressRecive, string $tokenContract) {
        $fullNode = new HttpProvider('https://api.trongrid.io');
        $solidityNode = new HttpProvider('https://api.trongrid.io');
        $eventServer = new HttpProvider('https://api.trongrid.io');
        try {
            $tron = new Tron($fullNode, $solidityNode, $eventServer);
        } catch (TronException $e) {
            exit($e->getMessage());
        }
        $walletSucces = array();
        foreach ($wallets as $wallet) {
            try {
                $tron->setAddress($wallet['address']);
                $tron->setPrivateKey($wallet['privateKey']);
                $contract = $tron->contract($tokenContract);
                $amount = $contract->balanceOf();
                if($amount > 0) {
                    $send = $contract->transfer($addressRecive, $amount, $wallet['address']);
                    if(isset($send->result) & $send->result == true) {
                        array_push($walletSucces, [$wallet['address'], true, $send]);
                    } else {
                        array_push($walletSucces, [$wallet['address'], false]);
                    }
                } else {
                    array_push($walletSucces, [$wallet['address'], false]);
                }
            } catch (\Exception $e) {
                array_push($walletSucces, [$wallet['address'], false]);
                continue;
            }
        }
        return $walletSucces;
    }
}
