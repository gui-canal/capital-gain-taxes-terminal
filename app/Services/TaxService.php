<?php

    namespace App\Services;

    use App\Domain\DataObjects\OperationObject;
    use App\Domain\DataObjects\TaxObject;
    use App\Domain\StockOperationsInterface;
    use Exception;

    class TaxService implements StockOperationsInterface
    {
        private const TAX_LESS_PROFIT_LIMIT    = 20000;
        private const PROFIT_TAX_PERCENTAGE    = 0.20;
        private const TAX_LESS_OPERATION_PRICE = 0;

        private int   $totalStockQuantity   = 0;
        private int   $totalPreviousLosses  = 0;
        private int   $totalPreviousProfits = 0;
        private array $buyingOperationsLog;

        public function processOperations(array $operations): array
        {
            $taxes = [];

            foreach ($operations as $operationObject) {
                $taxes[] = $this->getTaxByOperation($operationObject);
            }

            return $taxes;
        }

        /**
         * @throws \Exception
         */
        private function getTaxByOperation(OperationObject $operationObject): TaxObject
        {
            if ($operationObject->getOperation() === self::OPERATION_BUY) {
                return $this->processBuyingRules($operationObject);
            }

            return $this->processSellingRules($operationObject);
        }

        /**
         * @throws \Exception
         */
        private function processSellingRules(OperationObject $sellingOperation): TaxObject
        {
            if ($sellingOperation->getQuantity() > $this->totalStockQuantity) {
                throw new Exception("You cannot sell more stocks than you have bought");
            }

            $this->totalStockQuantity -= $sellingOperation->getQuantity();

            return $this->getTaxObject($this->applySellingTaxRules($sellingOperation));
        }

        private function applySellingTaxRules(OperationObject $sellingOperation): int
        {
            $profitsValue = $this->getProfitsAveragePriceValue($sellingOperation);

            if ($profitsValue < 0) {
                return $this->applyLossingTaxRules($sellingOperation);
            }

            if ($profitsValue > 0) {
                return $this->applyProfitingTaxRules($sellingOperation);
            }

            return self::TAX_LESS_OPERATION_PRICE;
        }

        private function applyProfitingTaxRules(OperationObject $sellingOperation): int
        {
            $liquidOperationProfit = $this->calculateLiquidOperationProfit($sellingOperation);

            if ($liquidOperationProfit < 0) {
                $this->totalPreviousLosses = $liquidOperationProfit * -1;

                return self::TAX_LESS_OPERATION_PRICE;
            }

            $this->totalPreviousLosses  = 0;

            if ($this->totalPreviousProfits < self::TAX_LESS_PROFIT_LIMIT) {
                return self::TAX_LESS_OPERATION_PRICE;
            }

            return $liquidOperationProfit * self::PROFIT_TAX_PERCENTAGE;
        }

        private function calculateLiquidOperationProfit(OperationObject $sellingOperation): int
        {
            $operationTotalPrice        = $this->calculateOperationTotalPrice($sellingOperation);
            $equivalentBoughtTotalPrice = $this->getAllBoughtWeightedAveragePrice() * $sellingOperation->getQuantity();

            $operationProfit = $operationTotalPrice - $equivalentBoughtTotalPrice;

            $this->totalPreviousProfits += $operationProfit;

            return $operationProfit - $this->totalPreviousLosses;
        }

        private function applyLossingTaxRules(OperationObject $sellingOperation): int
        {
            $avaregePriceLoss          = $this->getProfitsAveragePriceValue($sellingOperation) * -1;
            $this->totalPreviousLosses += $avaregePriceLoss * $sellingOperation->getQuantity();

            return self::TAX_LESS_OPERATION_PRICE;
        }

        private function getProfitsAveragePriceValue(OperationObject $sellingOperation): int
        {
            $sellingAveragePrice = $this->getSellingOperationWeightedAveragePrice($sellingOperation);
            $boughtAveragePrice  = $this->getAllBoughtWeightedAveragePrice();

            return $sellingAveragePrice - $boughtAveragePrice;
        }

        private function getSellingOperationWeightedAveragePrice(OperationObject $sellingOperation): float
        {
            $totalSellingPrice = $this->calculateOperationTotalPrice($sellingOperation);

            return $totalSellingPrice / $sellingOperation->getQuantity();
        }

        private function getAllBoughtWeightedAveragePrice(): float
        {
            $quantityBought = 0;
            $totalPrice     = 0;

            foreach ($this->buyingOperationsLog as $buyingOperation) {
                $quantityBought += $buyingOperation->getQuantity();
                $totalPrice     += $this->calculateOperationTotalPrice($buyingOperation);
            }

            return $totalPrice / $quantityBought;
        }

        private function calculateOperationTotalPrice(OperationObject $operation): int
        {
            return $operation->getQuantity() * $operation->getUnitCost();
        }

        private function processBuyingRules(OperationObject $buyingOperation): TaxObject
        {
            $this->buyingOperationsLog[] = $buyingOperation;
            $this->totalStockQuantity    += $buyingOperation->getQuantity();

            return $this->getTaxObject();
        }

        private function getTaxObject(int $taxPrice = self::TAX_LESS_OPERATION_PRICE): TaxObject
        {
            $taxObject = new TaxObject();
            $taxObject->setTax($taxPrice);

            return $taxObject;
        }
    }
