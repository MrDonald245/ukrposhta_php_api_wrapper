<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 05/04/18
 * Time: 09:59
 */

require_once 'UkrposhtaApiWrapper.php';
require_once 'entities/Client.php';

/**
 * Class ClientWrapper is used for working with Ukrposhta API client.
 */
class ClientWrapper extends UkrposhtaApiWrapper
{
    /**
     * @param string $bearer
     * @param string $token
     */
    public function __construct($bearer, $token)
    {
        parent::__construct($bearer, $token);
    }

    /**
     * @param Client|array $client
     * @return Client
     */
    public function create($client)
    {
        $data = parent::entityToArray($client);
        $client_array = $this->api->method('POST')->params($data)->clients();

        return new Client($client_array);
    }
}