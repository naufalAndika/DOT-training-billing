<?php 

class CancelBillingCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function tryToTest(ApiTester $I)
    {
        $I->sendGet('/billings/fc6c447cf59dfc437acb58c8e36bd149/cancel');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'data' => 'Billing Deleted'
        ]);
    }
}
