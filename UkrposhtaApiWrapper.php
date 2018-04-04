<?php
/**
 * Created by eugene.
 * User: eugene
 * Date: 04/04/18
 * Time: 13:07
 */

require_once 'entities/Address.php';

/**
 * Wraps the API for Ukrposhta
 * in order to make it easier to work with.
 */
class UkrposhtaApiWrapper
{
    /**
     * @var UkrposhtaApi $api
     */
    private $api;

    /**
     * UkroshtaApiWrapper constructor.
     *
     * @param string $bearer
     * @param string $token
     */
    public function __construct($bearer, $token)
    {
        $this->api = new UkrposhtaApi($bearer, $token);
    }

    /**
     * @param Address|array $address
     * @return Address
     */
    public function createAddress($address)
    {
        $data = is_array($address) ? $address : $address->toArray();
        $result = $this->api->method('POST')->params($data)->addresses();

        return new Address($result);
    }

    /**
     *
     */
    public function createClient()
    {

    }

}