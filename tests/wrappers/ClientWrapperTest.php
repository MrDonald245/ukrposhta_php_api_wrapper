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

class ClientWrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var clientWrapper
     */
    private $wrapper;

    public function setUp()
    {
        $this->wrapper = new clientWrapper(
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
    }
}
