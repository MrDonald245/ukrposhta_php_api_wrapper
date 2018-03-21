<?php
/**
 * Created by Eugene.
 * User=> eugene
 * Date=> 19/03/18
 * Time=> 09=>52
 */

include_once 'UkrposhtaApi.php';

$up_api = new UkrposhtaApi(
    'f9027fbb-cf33-3e11-84bb-5484491e2c94',
    'ba5378df-985e-49c5-9cf3-d222fa60aa68'
);

//$address = $up_api->method('GET')->addresses('376216');

$address = $up_api->method('POST')->params([
    'postcode' => '07401',
    'country' => 'UA',
    'region' => 'Київська',
    'city' => 'Бровари',
    'district' => 'Київський',
    'street' => 'Котляревського',
    'houseNumber' => '12',
    'apartmentNumber' => '33'
])->addresses();

$client = $up_api->method('POST')->params([
    "firstName" => "Евгений",
    "middleName" => "Константинович",
    "lastName" => "Бочарников",
    "uniqueRegistrationNumber" => "0035",
    "addressId" => $address['id'],
    "phoneNumber" => "067 123 12 34",
    "bankCode" => "123000",
    "bankAccount" => "111000222000999",
    "resident" => true,
    "edrpou" => "20053145",
    "emai" => "test@test.com",
])->clients();

var_dump($client);