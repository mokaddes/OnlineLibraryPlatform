<?php

namespace App\Console\Commands;

use App\Http\Controllers\HomeController;
use App\Models\BorrowedBook;
use App\Models\ProductView;
use App\Traits\Notification;
use Illuminate\Console\Command;

class SendBookReadNotifications extends Command
{
    use Notification;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:book-remainder';

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
     * @return bool
     */
    public function handle()
    {
       $home = new HomeController();
       return $home->bookRemainder();
    }
}
