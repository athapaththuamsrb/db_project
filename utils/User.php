<?php

function validateNIC($nic): bool
{
    return preg_match('/^[A-Z0-9]{10,14}$/', $nic);
}

abstract class User
{
    /** @var \string */
    private $type;
    /** @var \string */
    private $username;
    /** @var \string */
    private $name;

    public static function createUser($details): User
    {
        if (!isset($details['username']) || !preg_match('/^[a-zA-Z0-9._]{5,12}$/', $details['username'])) {
            throw new Exception('No valid username is provided');
        }
        if (!isset($details['name']) || !preg_match('/^[a-zA-Z.\s]{5,100}$/', $details['name'])) {
            throw new Exception('No valid name is provided');
        }
        if (!isset($details['type']) || !in_array($details['type'], ['admin', 'manager', 'employee', 'customer'])) {
            throw new Exception('No valid type is provided');
        }

        $username = $details['username'];
        $name = $details['name'];
        $type = $details['type'];
        if ($type === 'admin') {
            return new Admin($username, $name);
        } else if ($type === 'manager') {
            return new Manager($username, $name);
        } else if ($type === 'employee') {
            return new Employee($username, $name);
        }

        if (!isset($details['customer_type']) || !in_array($details['customer_type'], ['organization', 'individual'])) {
            throw new Exception('No valid customer type is provided');
        }
        $customerType = $details['customer_type'];
        if ($customerType === 'organization') {
            if (!isset($details['ownerNIC']) || !validateNIC($details['ownerNIC'])) {
                throw new Exception('No valid owner NIC is provided');
            }
            $ownerNIC = $details['ownerNIC'];
            return new Organization($username, $name, $ownerNIC);
        }
        if (!isset($details['DoB']) || !$details['DoB']) {
            throw new Exception('No valid date is provided');
        }
        $dob = null;
        try{
            $dob = new DateTime($details['DoB']);
            if ($dob->getTimestamp() > (new DateTime('now'))->getTimestamp()){
                throw new Exception('invalid date');
            }
        }catch (Exception $e){
            throw new Exception('Invalid date');
        }
        $NIC = null;
        if (isset($details['NIC']) && validateNIC($details['NIC'])) {
            $NIC = $details['NIC'];
        }else if (isset($details['guardianNIC']) && validateNIC($details['guardianNIC'])) {
            $NIC = $details['guardianNIC'];
        }else{
            throw new Exception('No valid NIC is provided');
        }
        return new Individual($username, $name, $NIC, $dob);
    }

    function __construct(string $uname, string $type, string $name)
    {
        $this->username = $uname;
        $this->type = $type;
        $this->name = $name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }
}

class Admin extends User
{
    function __construct(string $uname, string $name)
    {
        parent::__construct($uname, 'admin', $name);
    }
}

class Manager extends User
{
    function __construct(string $uname, string $name)
    {
        parent::__construct($uname, 'manager', $name);
    }
}

class Employee extends User
{
    function __construct(string $uname, string $name)
    {
        parent::__construct($uname, 'employee', $name);
    }
}

abstract class Customer extends User
{
    function __construct(string $uname, string $name)
    {
        parent::__construct($uname, 'customer', $name);
    }
    public abstract function getCustomerType(): string;
}

class Organization extends Customer
{
    /** @var \string */
    private $ownerNIC;

    function __construct(string $uname, string $name, string $ownerNIC)
    {
        parent::__construct($uname, $name);
        $this->ownerNIC = $ownerNIC;
    }

    public function getCustomerType(): string
    {
        return 'organization';
    }

    public function getOwnerNIC(): string
    {
        return $this->ownerNIC;
    }
}

class Individual extends Customer
{
    /** @var \DateTime */
    private $dob;
    /** @var \string */
    private $NIC;

    function __construct(string $uname, string $name, string $NIC, DateTime $dob)
    {
        parent::__construct($uname, $name);
        $this->dob = $dob;
        $this->NIC = $NIC;
    }

    public function getCustomerType(): string
    {
        return 'individual';
    }

    public function getNIC(): string
    {
        return $this->NIC;
    }

    public function getDoB(): DateTime
    {
        return $this->dob;
    }
}