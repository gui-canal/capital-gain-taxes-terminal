<?php

    namespace App\Domain;

    interface StockOperationsInterface
    {
        public const OPERATION_BUY    = 'buy';
        public const OPERATION_SELL   = 'sell';
        public const VALID_OPERATIONS = [self::OPERATION_BUY, self::OPERATION_SELL];
    }
