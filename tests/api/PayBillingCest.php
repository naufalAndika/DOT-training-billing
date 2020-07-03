<?php 

class PayBillingCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        $I->sendGet('/billings/84e10eff813fa203546c29cabcd73406/pay');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'data' => [
                'paid' => 1
            ]
        ]);
    }
}
