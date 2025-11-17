<?php

namespace App\Services;

class AprioriService
{
    protected $minSupport;
    protected $transactions;

    public function __construct(array $transactions, float $minSupport = 0.5)
    {
        $this->transactions = $transactions;
        $this->minSupport = $minSupport;
    }

    public function run()
    {
        $itemCounts = [];
        $numTransactions = count($this->transactions);

        foreach ($this->transactions as $transaction) {
            foreach ($transaction as $item) {
                if (!isset($itemCounts[$item])) {
                    $itemCounts[$item] = 0;
                }
                $itemCounts[$item]++;
            }
        }

        $frequentItems = [];
        foreach ($itemCounts as $item => $count) {
            $support = $count / $numTransactions;
            if ($support >= $this->minSupport) {
                $frequentItems[$item] = $support;
            }
        }

        return $frequentItems;
    }
}
