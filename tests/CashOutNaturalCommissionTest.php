<?php
class CacheOutNaturalCommissionTest extends \PHPUnit\Framework\TestCase
{
    public function testUnderLimit()
    {
        $converter = new \Services\Currencies([
            'EUR' => [
                'rate' => 1.,
                'precision' => 2
            ]
        ]);
        $calculator = new \Services\Commissions\CacheOutNaturalCommission($converter);
        $commission = $calculator->calculate(
            new \Entities\Transaction(
                new DateTimeImmutable(),
                new \Entities\UserType(1, \Entities\UserType::TYPE_NATURAL),
                new \Entities\Operation(\Entities\Operation::CASH_OUT),
                new \Entities\Amount('1000.00', 'EUR', $converter)
            )
        );
        $this->assertEquals('0.00', $commission->getAmount());
    }

    public function testOverLimit()
    {
        $converter = new \Services\Currencies([
            'EUR' => [
                'rate' => 1.,
                'precision' => 2
            ]
        ]);
        $calculator = new \Services\Commissions\CacheOutNaturalCommission($converter);
        $commissionOne = $calculator->calculate(
            new \Entities\Transaction(
                new DateTimeImmutable(),
                new \Entities\UserType(1, \Entities\UserType::TYPE_NATURAL),
                new \Entities\Operation(\Entities\Operation::CASH_OUT),
                new \Entities\Amount('1200.00', 'EUR', $converter)
            )
        );
        $commissionTwo = $calculator->calculate(
            new \Entities\Transaction(
                new DateTimeImmutable(),
                new \Entities\UserType(1, \Entities\UserType::TYPE_NATURAL),
                new \Entities\Operation(\Entities\Operation::CASH_OUT),
                new \Entities\Amount('1000.00', 'EUR', $converter)
            )
        );
        $this->assertEquals('0.60', $commissionOne->getAmount());
        $this->assertEquals('3.00', $commissionTwo->getAmount());
    }
}