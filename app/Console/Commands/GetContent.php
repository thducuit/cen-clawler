<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Sites\Mogi;
use App\Http\Sites\Propzy;

class GetContent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'content:get {site}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to get anchor and content';

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
        switch ($this->argument('site')) {
            case 'mogi':
                $site = new Mogi();
                break;
            case 'propzy':
                $site = new Propzy();
                break;

            default:
                # code...
                break;
        }
        
        try {
            $site->get_content();
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }
}
