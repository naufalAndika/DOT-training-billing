<?php 

class GenerateBillingCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        $I->haveHttpHeader('Content-Type', 'multipart/form-data');
        $I->sendPost('/billings', [
            'product_name' => 'Chitato',
            'price'        => '2000',
            'discount'     => '20',
            'pay_before'   => '2020-07-29',
            'email'        => 'andikaa.naufal@gmail.com'
        ]);
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
    }
}
