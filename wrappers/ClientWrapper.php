<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 05/04/18
 * Time: 09:59
 */

require_once 'UkrposhtaApiWrapper.php';

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

    public function create()
    {

    }
}