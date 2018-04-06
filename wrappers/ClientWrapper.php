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

    /**
     * @param int $clientId
     * @return Client
     */
    public function getById($clientId)
    {
        $client_array = $this->api->method('GET')->action('getById')->clients($clientId);
        return new Client($client_array);
    }

    /**
     * @param int $clientExternalId
     * @return Client
     */
    public function getByExternalId($clientExternalId)
    {
        $client_array = $this->api->method('GET')->action('getByExternalId')->clients($clientExternalId);
        return new Client($client_array);
    }

    /**
     * @param int $clientUuid
     * @return array $phones
     */
    public function getAllPhones($clientUuid)
    {
        return $this->api->method('GET')->action('getAllPhones')->clients($clientUuid);
    }

    /**
     * @param string $clientUuid
     * @param string $phoneNumber
     * @return Client $clientWithAddedPhones
     */
    public function addPhone($clientUuid, $phoneNumber)
    {
        $client_array = $this->api->method('PUT')->params(['phoneNumber' => $phoneNumber])->clients($clientUuid);
        return new Client($client_array);
    }

    /**
     * @param string $phoneUuid
     * @return void
     */
    public function deletePhone($phoneUuid)
    {
        $this->api->method('DELETE')->action('deletePhone')->clients($phoneUuid);
    }
}