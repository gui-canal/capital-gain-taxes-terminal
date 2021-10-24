<?php

namespace App\Commands;

use App\Libraries\OperationBuilder;
use App\Services\TaxService;
use LaravelZero\Framework\Commands\Command;

class OperateStocks extends Command
{
    /**
     * @var string
     */
    protected $signature = 'operateStocks {operations : The stocks operations json (opitional) }';

    /**
     * @var string
     */
    protected $description = 'Retrieves all given stock operations taxes ';

    public function handle(): void
    {
        $operations = json_decode($this->argument("operations"));

        if (empty($operations)) {
            $this->info("Please input a json array");
            return;
        }

        try {
            $operationBuilder = new OperationBuilder();
            $operations       = $operationBuilder->buildOperationsArrayByPayload($operations);

            $taxService = new TaxService();
            $return = $taxService->processOperations($operations);
            $return2 = json_encode($return);
            $this->info($return2);
        } catch (\Exception $exception) {
            $this->info($exception->getMessage());
        }

    }
}
