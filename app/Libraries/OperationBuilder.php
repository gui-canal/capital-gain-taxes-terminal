<?php

    namespace App\Libraries;

    use App\Domain\DataObjects\OperationObject;
    use App\Domain\StockOperationsInterface;
    use Exception;
    use stdClass;

    class OperationBuilder implements StockOperationsInterface
    {
        public function buildOperationsArrayByPayload(array $operationsPayload): array
        {
            $operations = [];

            foreach ($operationsPayload as $jsonOperation) {
                $operations[] =  $this->buildOperationObject($jsonOperation);
            }

            return $operations;
        }

        private function buildOperationObject(stdClass $operation): OperationObject
        {
            if(empty($operation->operation) || empty($operation->{'unit-cost'}) || empty($operation->quantity)){
                throw new Exception("Some operation json is out of standart, please check and try again");
            }

            if (!$this->isValidOperation($operation->operation)) {
                throw new Exception('There were an invalid operation type please use "buy" or "sell"');
            }

            if ($operation->{'unit-cost'} < 0 || $operation->quantity < 0) {
                throw new Exception("Operation`s quantity and unit cost must be zero or higher");
            }

            $operationObject = new OperationObject();

            $operationObject->setOperation($operation->operation);
            $operationObject->setUnitCost($operation->{'unit-cost'});
            $operationObject->setQuantity($operation->quantity);

            return $operationObject;
        }

        private function isValidOperation(string $operationType): bool
        {
            if (empty($operationType)) {
                return false;
            }

            if (!in_array($operationType, self::VALID_OPERATIONS)) {
                return false;
            }

            return true;
        }

    }
