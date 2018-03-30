<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 30/03/18
 * Time: 09:27
 */

require_once '../UkrposhtaApi.php';


class UkrposhtaApiTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UkrposhtaApi $api
     */
    private $api;

    /**
     * Create UkrposhtaApi instance
     */
    protected function setUp()
    {
        $this->api = new UkrposhtaApi(
            'f9027fbb-cf33-3e11-84bb-5484491e2c94',
            'ba5378df-985e-49c5-9cf3-d222fa60aa68');
    }

    /**
     * Test address creation
     *
     * @return array
     */
    public function testCreateAddress()
    {
        $address = $this->createAddressesWithApi($this->api);
        $this->checkAddressKeys($address);

        return $address;
    }

    /**
     * Get the address by id of already created one
     *
     * @depends testCreateAddress
     * @param $address array
     * @return array $address
     */
    public function testGetAddress($address)
    {
        $address = $this->api->method('GET')->addresses($address['id']);
        $this->checkAddressKeys($address);

        return $address;
    }

    /**
     * Create a client.
     *
     * @depends testGetAddress
     * @param $address array
     * @return array $client
     */
    public function testCreateClient($address)
    {
        $client = $this->createClientWithApi($this->api, $address);
        $this->checkClientKeys($client);

        return $client;
    }

    /**
     * Check an exception if wrong bearer is used
     */
    public function testWrongBearer()
    {
        $this->expectException(UkrposhtaApiException::class);

        $api = new UkrposhtaApi(
            'silly bearer',
            'ba5378df-985e-49c5-9cf3-d222fa60aa68');

        $this->createAddressesWithApi($api);
    }

    /**
     * @depends testGetAddress
     * @param array $address
     */
    public function testCreateClientWithWrongToken($address)
    {
        $this->expectException(UkrposhtaApiException::class);

        $api = new UkrposhtaApi(
            'f9027fbb-cf33-3e11-84bb-5484491e2c94',
            'silly token');

        $this->createClientWithApi($api, $address);
    }

    /**
     * Checks all required address' keys.
     *
     * @param $address array
     */
    private function checkAddressKeys($address)
    {
        $this->assertArrayHasKey('id', $address);
        $this->assertArrayHasKey('postcode', $address);
        $this->assertArrayHasKey('region', $address);
        $this->assertArrayHasKey('district', $address);
        $this->assertArrayHasKey('city', $address);
        $this->assertArrayHasKey('street', $address);
        $this->assertArrayHasKey('houseNumber', $address);
        $this->assertArrayHasKey('apartmentNumber', $address);
        $this->assertArrayHasKey('description', $address);
        $this->assertArrayHasKey('countryside', $address);
        $this->assertArrayHasKey('foreignStreetHouseApartment', $address);
        $this->assertArrayHasKey('detailedInfo', $address);
        $this->assertArrayHasKey('country', $address);
    }

    /**
     * Checks all required client's keys.
     *
     * @param array $client
     */
    private function checkClientKeys($client)
    {
        $this->assertArrayHasKey('uuid', $client);
        $this->assertArrayHasKey('name', $client);
        $this->assertArrayHasKey('firstName', $client);
        $this->assertArrayHasKey('middleName', $client);
        $this->assertArrayHasKey('lastName', $client);
        $this->assertArrayHasKey('nameEn', $client);
        $this->assertArrayHasKey('firstNameEn', $client);
        $this->assertArrayHasKey('lastNameEn', $client);
        $this->assertArrayHasKey('postId', $client);
        $this->assertArrayHasKey('externalId', $client);
        $this->assertArrayHasKey('uniqueRegistrationNumber', $client);
        $this->assertArrayHasKey('counterpartyUuid', $client);
        $this->assertArrayHasKey('addressId', $client);
        $this->assertArrayHasKey('addresses', $client);
        $this->assertArrayHasKey('phoneNumber', $client);
        $this->assertArrayHasKey('phones', $client);
        $this->assertArrayHasKey('email', $client);
        $this->assertArrayHasKey('emails', $client);
        $this->assertArrayHasKey('type', $client);
        $this->assertArrayHasKey('individual', $client);
        $this->assertArrayHasKey('edrpou', $client);
        $this->assertArrayHasKey('bankCode', $client);
        $this->assertArrayHasKey('bankAccount', $client);
        $this->assertArrayHasKey('tin', $client);
        $this->assertArrayHasKey('contactPersonName', $client);
        $this->assertArrayHasKey('resident', $client);
    }

    /**
     * Create address
     *
     * @param UkrposhtaApi $api
     * @return array $addresses
     */
    private function createAddressesWithApi($api)
    {
        return $api->method('POST')->params([
            'postcode' => '07401',
            'country' => 'UA',
            'region' => 'Київська',
            'city' => 'Бровари',
            'district' => 'Київський',
            'street' => 'Котляревського',
            'houseNumber' => '12',
            'apartmentNumber' => '33'
        ])->addresses();
    }

    /**
     * Create client
     *
     * @param UkrposhtaApi $api
     * @param array $address
     * @return array $addresses
     */
    private function createClientWithApi($api, $address)
    {
        return $api->method('POST')->params([
            'firstName' => 'Евгений',
            'middleName' => 'Константинович',
            'lastName' => 'Бочарников',
            'individual' => true,
            'uniqueRegistrationNumber' => '0035',
            'addressId' => $address['id'],
            'phoneNumber' => '067 123 12 34',
            'resident' => true,
            'email' => 'test@test.com',
        ])->clients();
    }
}