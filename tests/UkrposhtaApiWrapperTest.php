<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 04/04/18
 * Time: 14:53
 */

require_once '../UkrposhtaApiWrapper.php';
require_once '../UkrposhtaApi.php';

class UkrposhtaApiWrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var UkrposhtaApiWrapper
     */
    private $wrapper;

    protected function setUp()
    {
        $this->wrapper = new UkrposhtaApiWrapper(
            'f9027fbb-cf33-3e11-84bb-5484491e2c94',
            'ba5378df-985e-49c5-9cf3-d222fa60aa68');
    }

    public function testCreateAddressWithArray()
    {
        $address_data = ['postcode' => '07401',
            'country' => 'UA',
            'region' => 'Київська',
            'city' => 'Бровари',
            'district' => 'Київський',
            'street' => 'Котляревського',
            'houseNumber' => '12',
            'apartmentNumber' => '33'];

        $this->wrapper->createAddress($address_data);
    }

    public function testCreateAddressWithEntity()
    {
        $address = new Address(['postcode' => '07401',
            'country' => 'UA',
            'region' => 'Київська',
            'city' => 'Бровари',
            'district' => 'Київський',
            'street' => 'Котляревського',
            'houseNumber' => '12',
            'apartmentNumber' => '33']);

        $address = $this->wrapper->createAddress($address);
    }
}
