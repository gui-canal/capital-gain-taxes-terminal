<?php

    namespace App\Domain\DataObjects;

    class OperationObject
    {
        private string $operation;
        private int    $unitCost;
        private int    $quantity;

        public function getOperation(): string
        {
            return $this->operation;
        }

        public function setOperation(string $operation): void
        {
            $this->operation = $operation;
        }

        public function getUnitCost(): int
        {
            return $this->unitCost;
        }

        public function setUnitCost(int $unitCost): void
        {
            $this->unitCost = $unitCost;
        }

        public function getQuantity(): int
        {
            return $this->quantity;
        }

        public function setQuantity(int $quantity): void
        {
            $this->quantity = $quantity;
        }

    }
