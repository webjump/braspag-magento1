<?php

/**
 * Pagador Unit Test
 *
 * @category  Unit_Test
 * @package   Webjump_BraspagPagador_Model_Method
 * @author    Webjump Core Team <desenvolvedores@webjump.com>
 * @copyright 2014 Webjump (http://www.webjump.com.br)
 * @license   http://www.webjump.com.br  Copyright
 * @link      http://www.webjump.com.br
 **/
class Webjump_BraspagPagador_Model_NotificationTest extends \Codeception\Test\Unit
{
    public function _before()
    {
        $this->notify = new \Webjump_BraspagPagador_Model_Notification;
    }

    public function testProcessTheReturn()
    {
        // prepare the tests
        $expected = array(
            'FORMAPAGTO' => 'Simulado',
            'CODPAGAMENTO' => '997',
            'VENDAID' => '100000644',
            'VALOR' => '1330',
            'CODAUTORIZACAO' => '715452',
            'TRANSID' => '0213104507111',
            'PARCELAS' => '1',
            'NOME' => 'webjump webjump',
            'CPF' => '67228512499',
            'BRASPAGORDERID' => 'f002cd95-e73a-4deb-987c-349452b41754',
            'TRANSACTIONID' => 'f7645720-d6e1-4f5e-8805-897a6dc776e3',
            'IPADDRESS' => '177.32.155.16',
            'LANGUAGE' => 'pt-BR',
            'MERCHANTID' => '7aea051a-1d01-e411-9406-0026b939d54b',
        );

        $this->response = array(
            'merchant_id' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'crypt' => 'hOUfxziYF3/GC+HS/uknsXMIdk437tFu9me4vN4KDxBqpUkxaRMAT8gkdMBit8N+GXqQLRtn5Y1zNe3dUtg4x9iTvl6uHfor72nWC8bKCIckxkqwBx/xUgjkJP7Rqqp5F3TUgloH4xMg4g527etOOr06ml/NS9PATrWVJues9AsvHXHHzBeR/TiBhhc/PgKgtOdCPzBH3a9mV1BoyabMyJVhNKeTaInvQnlqqqFFkwH9ZCe8dE6mw/i8C5+QaD6hOXWukq7/9kkdiixR4fBCsPDYEoWeUr2e+02r3S0bZqTYkQIuu5g5biHnTzV/k0LYn0UeCoIYO45Hv44J5+uDcgldp/dbKc5zT3pdJrkOB2F9JjzywUWHu2Cs+9n1C8kaivP0ELmHWjLX/e4RzK/lpAYQP2Xf1+hqF+HnJF6UEc+sarx3vh7Nn/xV/deG/mcq+xNivZXHxGL4hXGPtKd+oToJLFKClUKbGGBbMl5mY/2UC8qEw57gq7kYe4gNcTA1mbpr3eIQMR0E6VZLxshYnwg1/Y0B/yZlmca6mLuYB7k5WwFojExtKsQ+BB0x9fIWujNMLFNFLAp2NFA8NJ4vScU5+YU40LFAioeopjeIae8QWz0xtswZgqm7Ql7i3xwMVhn262d5+wrMMs4q4cdGLg9sr7Sw6mnbYKGWcwMezIFditA6L3xZx0OInn7jYxW8iNOlvSVhGQMPM7kdLxh7Q71iF8ntIw9ZR3SmxUXeIKM= ',
        );

        // apply changes
        $this->notify->setData($this->response);
        $this->return = $this->notify->decrypt();

        // test the results
        $this->tester->AssertEquals($expected, $this->return);
    }

    public function testProcessTheReturnNotAuthorized()
    {
        // prepare the tests
        $expected = array(
            'FORMAPAGTO' => 'Simulado',
            'CODPAGAMENTO' => '997',
            'CODRETORNO' => '2',
            'DESRETORNO' => 'Not Authorized',
            'VENDAID' => '100000645',
            'VALOR' => '1330',
            'TRANSID' => '0213033410961',
            'PARCELAS' => '1',
            'NOME' => 'webjump webjump',
            'CPF' => '67228512499',
            'BRASPAGORDERID' => '537d0131-ebd3-4838-85c0-936be7f99816',
            'TRANSACTIONID' => '1fbc0ff7-13aa-4ba9-ba77-8dc6e3760df9',
            'IPADDRESS' => '177.32.155.16',
            'LANGUAGE' => 'pt-BR',
            'MERCHANTID' => '7aea051a-1d01-e411-9406-0026b939d54b',
        );

        $this->response = array(
            'merchant_id' => '7AEA051A-1D01-E411-9406-0026B939D54B',
            'crypt' => 'GrSbWs3x8ffWv3h1nJLi1V3+yh2g8gjvFNfX7wYZ2Q18i16JYRvURkEkd+2bfKtL2BG1ckksSGrpS/e2cR3TK16bbVzapTm976ORxhCVygu05HynGWx0jf9u0apEy5hzmk5+9WineMnhWenUyzAABFwhvRm1XFXTj/wJvR5cFU1rWwq+bSAVb0ArQ+GKHY5hOXSJhaL3IbwquoZoL8PqLEZaThf3S32r6qvwUKvywFyrN5M6UBnH29rfq8hshlnzpTeBuIC8Qs0f2Amttys6idOZMp14Hlz8ZVrrD1H2/Bs1MCv/GEcPs9JNPgPmwj4TIgNBMAAnyzLiiV8tzpnzB2JOebO+ayJ05NmemQrb0ENfAu9tYgc+xDZo2nbSeFyy1n8txRVzL5XtOkAwmzM6WlawN2IR7saeLebLrEQLm71BWnIW0FNhIkO6Qw33cGpe9E/zmRC5RIJ8Bpxtef04KsQutgECQht3+LjkoGg/9vRr+6twfouJ9X9MUO9nayA3F+tgBZ0oU5wBytREhfPEBDCWxVGmztb1Cu01IqXJqeMJnGNMNuGqxvPvZfUNovZ7SZ1Jl8R8zRZwAxR92zSFsnrYQua38wpXAseOXO/KY34y4u0WmVES5cOTPWObnrUH7FO37igXVF+a+vRilWRo457tjn8JWUikljesJww7xb/SzpfJ0/ss/z6Dx4RGzqoUJQ6yWqCwYAMxy0cYMANHan3JmRfUijNlRPWGfX/eUAw= ',
        );

        // apply changes
        $this->notify->setData($this->response);
        $this->return = $this->notify->decrypt();

        // test the results
        $this->tester->AssertEquals($expected, $this->return);
    }
}
