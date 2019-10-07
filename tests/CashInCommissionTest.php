<?php
class CashInCommissionTest extends \PHPUnit\Framework\TestCase
{
    public function testUnderLimit()
    {
        $converter = new \Services\Currencies([
            'EUR' => [
                'rate' => 1.,
                'precision' => 2
            ]
        ]);
        $calculator = new \Services\Commissions\CacheInCommission($converter);
        $commission = $calculator->calculate(
            new \Entities\Transaction(
                new DateTimeImmutable(),
                new \Entities\UserType(1, \Entities\UserType::TYPE_NATURAL),
                new \Entities\Operation(\Entities\Operation::CASH_IN),
                new \Entities\Amount('200.00', 'EUR', $converter)
            )
        );
        $this->assertEquals('0.06', $commission->getAmount());
    }

    public function testOverLimit()
    {
        $converter = new \Services\Currencies([
            'EUR' => [
                'rate' => 1.,
                'precision' => 2
            ]
        ]);
        $calculator = new \Services\Commissions\CacheInCommission($converter);
        $commission = $calculator->calculate(
            new \Entities\Transaction(
                new DateTimeImmutable(),
                new \Entities\UserType(1, \Entities\UserType::TYPE_NATURAL),
                new \Entities\Operation(\Entities\Operation::CASH_IN),
                new \Entities\Amount('1000000.00', 'EUR', $converter)
            )
        );
        $this->assertEquals('5.00', $commission->getAmount());
    }
}
