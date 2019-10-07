<?php
class CashOutLegalCommissionTest extends \PHPUnit\Framework\TestCase
{
    public function testUnderLimit()
    {
        $converter = new \Services\Currencies([
            'EUR' => [
                'rate' => 1.,
                'precision' => 2
            ]
        ]);
        $calculator = new \Services\Commissions\CacheOutLegalCommission($converter);
        $commission = $calculator->calculate(
            new \Entities\Transaction(
                new DateTimeImmutable(),
                new \Entities\UserType(1, \Entities\UserType::TYPE_LEGAL),
                new \Entities\Operation(\Entities\Operation::CASH_OUT),
                new \Entities\Amount('100', 'EUR', $converter)
            )
        );
        $this->assertEquals('0.50', $commission->getAmount());
    }

    public function testOverLimit()
    {
        $converter = new \Services\Currencies([
            'EUR' => [
                'rate' => 1.,
                'precision' => 2
            ]
        ]);
        $calculator = new \Services\Commissions\CacheOutLegalCommission($converter);
        $commission = $calculator->calculate(
            new \Entities\Transaction(
                new DateTimeImmutable(),
                new \Entities\UserType(1, \Entities\UserType::TYPE_LEGAL),
                new \Entities\Operation(\Entities\Operation::CASH_OUT),
                new \Entities\Amount('300.00', 'EUR', $converter)
            )
        );
        $this->assertEquals('0.90', $commission->getAmount());
    }
}

