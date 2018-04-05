<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 05/04/18
 * Time: 11:11
 */


class ClientWrapperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var AddressWrapper
     */
    private $wrapper;

    public function setUp()
    {
        $this->wrapper = new AddressWrapper(
            'f9027fbb-cf33-3e11-84bb-5484491e2c94',
            'ba5378df-985e-49c5-9cf3-d222fa60aa68');
    }
}
