<?php
/**
 * Created by PhpStorm.
 * User: jyl
 * Date: 2019/5/7
 * Time: 5:24 PM
 */

namespace Jyil\AliwareMQ\Commands;

use Illuminate\Console\Command;

class ReceiveCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aliwaremq:receive {queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'aliwaremq receive';

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
        $queue = $this->argument('queue');

        app('aliwaremq')->receive($queue);
    }
}