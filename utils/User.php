<?php
class User
{
    /** @var \string */
    private $type;
    /** @var \string */
    private $username;

    public function __construct(string $uname, string $type)
    {
        $this->username = $uname;
        $this->type = $type;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
