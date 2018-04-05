<?php
/**
 * Created by Eugene.
 * User: eugene
 * Date: 05/04/18
 * Time: 09:48
 */

require_once 'EntityBase.php';

class Client extends EntityBase
{
    const TYPE_INDIVIDUAL = 'INDIVIDUAL';
    const TYPE_COMPANY = 'COMPANY';
    const TYPE_PRIVATE_ENTREPRENEUR = 'PRIVATE_ENTREPRENEUR';

    /**
     * Тип клієнта.
     * INDIVIDUAL – фізична особа
     * COMPANY – юридична особа
     * PRIVATE_ENTREPRENEUR – фізична особа підприємець.
     * По замовченню тип COMPANY.
     * Тип клієнта не можна змінити.
     *
     * @var string $type
     */
    private $type;

    /**
     * Ім’я клієнта, (максимальна кількість символів 250,
     * є обов’язковим для юридичної особи
     * та фізичної особи підприємця, для фізичної особи формується
     * з параметрів: firstName, middleName , lastName)
     *
     * @var string
     */
    private $name;

    /**
     * Ім’я фізичної особи (максимальна кількість символів 250, мінімальна 2)
     *
     * @var string $firstName
     */
    private $firstName;

    /**
     * По батькові фізичної особи (максимальна кількість символів 250, мінімальна 2)
     *
     * @var string $middleName
     */
    private $middleName;

    /**
     * Фамілія фізичної особи (максимальна кількість символів 250)
     *
     * @var string $lastName
     */
    private $lastName;

    /**
     * Унікальний реєстраційний номер
     *
     * @var string $uniqueRegistrationNumber
     */
    private $uniqueRegistrationNumber;

    /**
     * Ідентифікатор адреси, вказується Id попередньо створеної адреси
     *
     * @var int $addressId
     */
    private $addressId;

    /**
     * Якщо клієнт вказав декілька адрес,
     * буде використовуватись та у якій main- true.
     * Тип адреси (PHYSICAL,LEGAL)
     *
     * @var array $addresses
     */
    private $addresses;

    /**
     * Змінна яка вказує чи є клієнт юридичної або фізичною особою.
     * Уюридичної особи individual повинен бути-false,
     * у фізичної-true. (Буде видалений )
     *
     * @var bool $individual
     */
    private $individual;

    /**
     * Телефонний номер клієнта (тільки цифри, максимальна кількість 25 символів)
     *
     * @var string $phoneNumber
     */
    private $phoneNumber;

    /**
     * Якщо клієнт вказав декілька телефонів,
     * буде використовуватись той у якого main -true.
     * Type – тип телефонного номера клієнта (WORK, PERSONAL).
     * Uuid- ідентифікатор.
     *
     * @var array $phones
     */
    private $phones;

    /**
     * МФО код клієнта, (тільки цифри, максимальна кількість 6 символів), тільки діючи банки.
     *
     * @var string $bankCode
     */
    private $bankCode;

    /**
     * Розрахунковий рахунок, (тільки цифри, від 6 до 14 символів), перевірка на валідність.
     *
     * @var string $bankAccount
     */
    private $bankAccount;

    /**
     * Ідентифікатор контрагента який створив клієнта
     *
     * @var int $counterpartyUuid
     */
    private $counterpartyUuid;

    /**
     * Електрона пошта клієнта
     *
     * @var string $email
     */
    private $email;

    /**
     * Якщо клієнт вказав декілька електронних пошти, буде використовуватись та у якої main - true
     *
     * @var array $emails
     */
    private $emails;

    /**
     * Унікальний ідентифікатор клієнта що надається ПАТ «Укрпошта»
     *
     * @var string $postId
     */
    private $postId;

    /**
     * Зовнішній ідентифікатор клієнта в базі контрагента
     *
     * @var string $externalId
     */
    private $externalId;

    /**
     * Ім’я контактної особи
     *
     * @var string $contactPersonName
     */
    private $contactPersonName;

    /**
     * Резидент України. Якщо resident - true то клієнт є резидентом України. По замовченню при створенні клієнта resident – true.
     *
     * @var bool $resident
     */
    private $resident;

    /**
     * ЄДРПОУ юридичної особи (тільки цифри, 5-8 символів).
     * Може бути збережений тільки валідний ЄДРПОУ.
     *
     * @var string $edrpou
     */
    private $edrpou;

    /**
     * Індивідуальний податковий номер для фізичних осіб та фізичних осіб підприємців
     * (тільки цифри, для фізичних осіб 10, для фізичних осіб підприємців 12 символів).
     * Може бути збережений тільки валідний ІПН.
     *
     * @var string
     */
    private $tin;

    /**
     * EntityBase constructor.
     *
     * @param array|null $array_data
     */
    public function __construct($array_data = null)
    {
        $this->initWithArray($array_data);
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return parent::objectToArray();
    }

    /**
     * @param array $data
     * @return void
     */
    public function initWithArray($data)
    {
        foreach ($this as $key => $value) {
            if (isset($data[$key])) {
                $this->$key = $data[$key];
            }
        }
    }
}