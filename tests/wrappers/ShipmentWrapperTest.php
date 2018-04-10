<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 10/04/18
 * Time: 12:00
 */

require_once '../../wrappers/ShipmentWrapper.php';
require_once '../../wrappers/entities/Shipment.php';
require_once '../../kernel/UkrposhtaApi.php';
require_once '../../wrappers/UkrposhtaApiWrapper.php';

class ShipmentWrapperTest extends PHPUnit_Framework_TestCase
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


}
