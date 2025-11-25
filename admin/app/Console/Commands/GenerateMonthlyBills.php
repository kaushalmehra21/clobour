<?php

namespace App\Console\Commands;

use App\Services\BillingService;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateMonthlyBills extends Command
{
    protected $signature = 'bills:generate {month?}';
    protected $description = 'Generate monthly maintenance bills for all units';

    protected $billingService;

    public function __construct(BillingService $billingService)
    {
        parent::__construct();
        $this->billingService = $billingService;
    }

    public function handle(): void
    {
        $month = $this->argument('month') ?? Carbon::now()->format('Y-m');
        
        $this->info("Generating bills for {$month}...");
        
        $result = $this->billingService->generateMonthlyBills($month);
        
        $this->info("Generated {$result['generated']} bills successfully.");
        
        if (!empty($result['errors'])) {
            $this->error("Errors occurred:");
            foreach ($result['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }
    }
}
