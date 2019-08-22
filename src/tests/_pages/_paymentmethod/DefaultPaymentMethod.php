<?php

interface DefaultPaymentMethod
{
    public function setOptions(AcceptanceTester $user);

    public function validateTransaction(AcceptanceTester $user);
}
