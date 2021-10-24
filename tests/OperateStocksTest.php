<?php

    class OperateStocksTest extends \Tests\TestCase
    {
        /**
         * @dataProvider operationsDataProvider
         */
        public function testShouldSucceed(string $expected, string $parameters): void
        {
            $this->artisan('operateStocks', ['operations' => $parameters])->expectsOutput($expected);
        }

        public function operationsDataProvider(): array
        {
            return [
                ['Please input a json array', 'strait string',],
                ['Please input a json array', '[1,2,3,4,5,6,7]',],
                ['Some operation json is out of standart, please check and try again', '[{"unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": 5000}]',],
                ['Some operation json is out of standart, please check and try again', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "quantity": 5000}]',],
                ['Some operation json is out of standart, please check and try again', '[{"operation":"buy", "unit-cost":10}, {"operation":"sell", "unit-cost":20, "quantity": 5000}]',],
                ['There were an invalid operation type please use "buy" or "sell"', '[{"operation":"buy2", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": 5000}]',],
                ['There were an invalid operation type please use "buy" or "sell"', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sells", "unit-cost":20, "quantity": 5000}]',],
                ['Operation`s quantity and unit cost must be zero or higher', '[{"operation":"buy", "unit-cost":-1, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": 5000}]',],
                ['Operation`s quantity and unit cost must be zero or higher', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": -5}]',],
                ['You cannot sell more stocks than you have bought', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": 15000}]',],
                ['[{"tax":0},{"tax":10000}]', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": 5000}]',],
                ['[{"tax":0},{"tax":0},{"tax":0}]', '[{"operation":"buy", "unit-cost":10, "quantity": 100}, {"operation":"sell", "unit-cost":15, "quantity": 50},{"operation":"sell","unit-cost":15, "quantity":50}]',],
                ['[{"tax":0},{"tax":10000},{"tax":0}]', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":20, "quantity": 5000},{"operation":"sell","unit-cost":5, "quantity":5000}]',],
                ['[{"tax":0},{"tax":0},{"tax":5000}]', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":5, "quantity": 5000},{"operation":"sell","unit-cost":20, "quantity":5000}]',],
                ['[{"tax":0},{"tax":0},{"tax":0}]', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"buy", "unit-cost":25, "quantity": 5000},{"operation":"sell","unit-cost":15, "quantity":10000}]',],
                ['[{"tax":0},{"tax":0},{"tax":0},{"tax":10000}]', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"buy", "unit-cost":25, "quantity": 5000},{"operation":"sell","unit-cost":15, "quantity":10000},{"operation":"sell","unit-cost":25, "quantity":5000}]',],
                ['[{"tax":0},{"tax":0},{"tax":0},{"tax":0},{"tax":3000}]', '[{"operation":"buy", "unit-cost":10, "quantity": 10000}, {"operation":"sell", "unit-cost":2, "quantity": 5000},{"operation":"sell","unit-cost":20, "quantity":2000},{"operation":"sell","unit-cost":20, "quantity":2000},{"operation":"sell","unit-cost":25, "quantity":1000}]',],
            ];
        }
    }
