<?php

class DefaultPage
{
    public static $user;

    public function __construct(AcceptanceTester $user)
    {
        $this->user = $user;
    }

    public static function of(AcceptanceTester $user)
    {
        return new static($user);
    }
}
