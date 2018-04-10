<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 10/04/18
 * Time: 11:57
 */

require_once 'UkrposhtaApiWrapper.php';
require_once 'entities/Shipment.php';

/**
 * Class ShipmentWrapper is used for working with Ukrposhta API shipment.
 */
class ShipmentWrapper extends UkrposhtaApiWrapper
{
    /**
     * @param string $bearer
     * @param string $token
     */
    public function __construct($bearer, $token)
    {
        parent::__construct($bearer, $token);
    }


}