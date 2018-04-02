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
     * Get a client
     *
     * @depends testCreateClient
     * @param array $client
     * @return array
     */
    public function testGetClient($client)
    {
        $client = $this->api->method('GET')->clients($client['uuid']);
        $this->checkClientKeys($client);

        return $client;
    }

    /**
     * Delete client
     *
     * @depends testGetClient
     * @param array $client
     */
    public function testDeleteClient($client)
    {
        $this->api->method('DELETE')->clients($client['uuid']); // TODO: this method does not work
    }

    /**
     * Test PUT method for client
     *
     * @depends testGetClient
     * @param $client
     */
    public function testChangeClient($client)
    {
        $client = $this->api->method('PUT')->params(['firstName' => 'Ибрагим'])->clients($client['uuid']);
        $this->assertEquals('Ибрагим', $client['firstName']);
    }

    /**
     * Test creation of a shipment
     */
    public function testCreateShipment()
    {
        $sender_address = $this->createAddressesWithApi($this->api);
        $recipient_address = $this->createAddressesWithApi($this->api);

        $sender_uuid = $this->createClientWithApi($this->api, $sender_address)['uuid'];
        $recipient_uuid = $this->createClientWithApi($this->api, $recipient_address)['uuid'];

        $shipment = $this->api->method('POST')->params([
            'sender' => ['uuid' => $sender_uuid],
            'recipient' => ['uuid' => $recipient_uuid],
            'deliveryType' => 'W2D',
            'paidByRecipient' => 'true',
            'nonCashPayment' => 'false',
            'parcels' => [['weight' => 1200, 'length' => 170]]
        ])->shipments();

        $this->checkShipmentKeys($shipment);

        return $shipment;
    }

    /**
     * Test of getting a shipment
     *
     * @depends testCreateShipment
     * @param array $shipment
     * @return array $shipment
     */
    public function testGetShipment($shipment)
    {
        $shipment = $this->api->method('GET')->shipments($shipment['uuid']);
        $this->checkShipmentKeys($shipment);

        return $shipment;
    }

    /**
     * Test PUT method for shipment
     *
     * @depends testGetShipment
     * @param $shipment
     * @return array $shipment
     */
    public function testChangeShipment($shipment)
    {
        $shipment = $this->api->method('PUT')->params(['paidByRecipient' => 'false'])->shipments($shipment['uuid']);
        $this->assertEquals(false, $shipment['paidByRecipient']);

        return $shipment;
    }

    /**
     * Test of removing a shipment
     * @depends testChangeShipment
     * @param $shipment
     */
    public function testDeleteShipment($shipment)
    {
        $this->expectException(UkrposhtaApiException::class);

        $this->api->method('DELETE')->shipments($shipment['uuid']);
        $this->api->method('GET')->shipments($shipment['uuid']);
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
     * Checks all required shipment's keys.
     * @param array $shipment
     */
    private function checkShipmentKeys($shipment)
    {
        $this->arrayHasKey($shipment['uuid']);
        $this->arrayHasKey($shipment['type']);
        $this->arrayHasKey($shipment['sender']);
        $this->arrayHasKey($shipment['recipientPhone']);
        $this->arrayHasKey($shipment['recipientEmail']);
        $this->arrayHasKey($shipment['recipientAddressId']);
        $this->arrayHasKey($shipment['returnAddressId']);
        $this->arrayHasKey($shipment['shipmentGroupUuid']);
        $this->arrayHasKey($shipment['externalId']);
        $this->arrayHasKey($shipment['deliveryType']);
        $this->arrayHasKey($shipment['packageType']);
        $this->arrayHasKey($shipment['onFailReceiveType']);
        $this->arrayHasKey($shipment['barcode']);
        $this->arrayHasKey($shipment['weight']);
        $this->arrayHasKey($shipment['length']);
        $this->arrayHasKey($shipment['width']);
        $this->arrayHasKey($shipment['height']);
        $this->arrayHasKey($shipment['declaredPrice']);
        $this->arrayHasKey($shipment['deliveryPrice']);
        $this->arrayHasKey($shipment['postPay']);
        $this->arrayHasKey($shipment['postPayUah']);
        $this->arrayHasKey($shipment['postPayDeliveryPrice']);
        $this->arrayHasKey($shipment['currencyCode']);
        $this->arrayHasKey($shipment['postPayCurrencyCode']);
        $this->arrayHasKey($shipment['currencyExchangeRate']);
        $this->arrayHasKey($shipment['discount']);
        $this->arrayHasKey($shipment['lastModified']);
        $this->arrayHasKey($shipment['description']);
        $this->arrayHasKey($shipment['parcels']);
        $this->arrayHasKey($shipment['direction']);
        $this->arrayHasKey($shipment['lifecycle']);
        $this->arrayHasKey($shipment['deliveryDate']);
        $this->arrayHasKey($shipment['calculationDescription']);
        $this->arrayHasKey($shipment['international']);
        $this->arrayHasKey($shipment['paidByRecipient']);
        $this->arrayHasKey($shipment['postPayPaidByRecipient']);
        $this->arrayHasKey($shipment['nonCashPayment']);
        $this->arrayHasKey($shipment['bulky']);
        $this->arrayHasKey($shipment['fragile']);
        $this->arrayHasKey($shipment['bees']);
        $this->arrayHasKey($shipment['recommended']);
        $this->arrayHasKey($shipment['sms']);
        $this->arrayHasKey($shipment['toReturnToSender']);
        $this->arrayHasKey($shipment['documentBack']);
        $this->arrayHasKey($shipment['checkOnDelivery']);
        $this->arrayHasKey($shipment['transferPostPayToBankAccount']);
        $this->arrayHasKey($shipment['deliveryPricePaid']);
        $this->arrayHasKey($shipment['postPayPaid']);
        $this->arrayHasKey($shipment['postPayDeliveryPricePaid']);
        $this->arrayHasKey($shipment['packedBySender']);
        $this->arrayHasKey($shipment['free']);
        $this->arrayHasKey($shipment['discountPerClient']);
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