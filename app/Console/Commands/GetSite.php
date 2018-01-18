<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Sites\Mogi;

class GetSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'site:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Anchor in Home Site';

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
     * @return mixed
     */
    public function handle()
    {
        $mogi = new Mogi();
        try {
            $mogi->get_anchor_home_page();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
        
    }
}
