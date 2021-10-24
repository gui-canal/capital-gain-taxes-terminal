<?php

    namespace App\Domain\DataObjects;

    class TaxObject
    {
        public int $tax = 0;

        public function setTax(int $tax): void
        {
            $this->tax = $tax;
        }
    }
