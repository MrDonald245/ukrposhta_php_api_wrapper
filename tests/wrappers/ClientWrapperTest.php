<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 05/04/18
 * Time: 11:11
 */

require_once '../../wrappers/ClientWrapper.php';
require_once '../../wrappers/entities/Client.php';
require_once '../../kernel/UkrposhtaApi.php';
require_once '../../wrappers/UkrposhtaApiWrapper.php';

class ClientWrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UkrposhtaApiWrapper
     */
    private $wrapper;

    public function setUp()
    {
        $this->wrapper = new UkrposhtaApiWrapper(
            'f9027fbb-cf33-3e11-84bb-5484491e2c94',
            'ba5378df-985e-49c5-9cf3-d222fa60aa68');
    }

    public function testCreateAddress()
    {
        $address = new Address(['postcode' => '07401',
            'country' => 'UA',
            'region' => 'Київська',
            'city' => 'Бровари',
            'district' => 'Київський',
            'street' => 'Котляревського',
            'houseNumber' => '12',
            'apartmentNumber' => '33']);

        $address = $this->wrapper->address()->create($address);
        $this->assertEquals('Київський', $address->getDistrict());

        return $address;
    }


    /**
     * @depends testCreateAddress
     * @param Address $address
     */
    public function testCreateClientWithArray($address)
    {
        $client_data = [
            'name' => 'ТОВ Експресс Банк',
            'uniqueRegistrationNumber' => '0035',
            'addressId' => $address->getId(),
            'phoneNumber' => '067 123 12 34',
            'resident' => true,
            'edrpou' => '20053145',
            'email' => 'test@test.com',];

        $client = $this->wrapper->client()->create($client_data);
        $this->assertEquals('0035', $client->getUniqueRegistrationNumber());
    }

    /**
     * @depends testCreateAddress
     * @param Address $address
     * @return Client $client
     */
    public function testCreateClientWithEntity($address)
    {
        $client = new client([
            'name' => 'ТОВ Експресс Банк',
            'uniqueRegistrationNumber' => '0035',
            'addressId' => $address->getId(),
            'phoneNumber' => '067 123 12 34',
            'resident' => true,
            'edrpou' => '20053145',
            'email' => 'test@test.com',]);

        $client = $this->wrapper->client()->create($client);
        $this->assertEquals('0035', $client->getUniqueRegistrationNumber());

        return $client;
    }

    /**
     * @depends testCreateClientWithEntity
     * @param Client $client
     */
    public function testGetClientById($client)
    {
        $client = $this->wrapper->client()->getById($client->getUuid());
        $this->assertEquals('test@test.com', $client->getEmail());
    }

    /**
     * @depends testCreateClientWithEntity
     * @param Client $client
     */
    public function testGetClientByExternalId($client)
    {
        $client->setExternalId(1);
        $result_client = $this->wrapper->client()->getByExternalId($client->getExternalId());
        $this->assertEquals(1, $result_client->getExternalId());
    }

    /**
     * @depends testCreateClientWithEntity
     * @param Client $client
     */
    public function testGetAllClientsPhones($client)
    {
        $array_phones = $this->wrapper->client()->getAllPhones($client->getUuid())[0];

        $this->assertArrayHasKey('uuid', $array_phones);
        $this->assertArrayHasKey('phoneNumber', $array_phones);
        $this->assertArrayHasKey('type', $array_phones);
        $this->assertArrayHasKey('main', $array_phones);
    }

    /**
     * @depends testCreateClientWithEntity
     * @param Client $client
     * @return Client $client_with_new_phones
     */
    public function testAddPhone($client)
    {
        $client_with_new_phones = $this->wrapper->client()
            ->addPhone($client->getUuid(), '+38099999999');

        $amount_of_phones = sizeof($client_with_new_phones->getPhones());

        $this->assertEquals(2, $amount_of_phones);

        return $client_with_new_phones;
    }

    /**
     * @depends testAddPhone
     * @param Client $client
     */
    public function testDeletePhone($client)
    {
        $phone_amount_before_deleting = sizeof($client->getPhones());

        foreach ($client->getPhones() as $phone) {
            if ($phone['main'] == false) {
                $new_phone_uuid = $phone['uuid'];
            }
        }

        $this->wrapper->client()->deletePhone($new_phone_uuid);

        $phone_amount_after_deleting = $this->wrapper->client()->getAllPhones($client->getUuid());
        $this->assertEquals(false, $phone_amount_after_deleting == $phone_amount_before_deleting);
    }
}
