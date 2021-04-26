<?php

namespace App\Console\Commands;

use App\Rate;
use AshAllenDesign\LaravelExchangeRates\Classes\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cookie;

class UpdateRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $exchangeRates = new ExchangeRate();
        $rate = $exchangeRates->convert(1, 'EUR', 'USD', Carbon::now());
        $m = new Rate();
        $m->rate = $rate;
        $m->save();
        return 0;
    }
}
