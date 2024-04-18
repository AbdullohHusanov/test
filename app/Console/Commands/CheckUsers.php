<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $u_W_T = User::with('tokens')->get();

        foreach ($u_W_T as $t) {
            $c = count($t['tokens']) - 1;
            if ($t['tokens'][$c]['expires_at'] <= Carbon::now()->subDays(3)) {
                $t['status'] = 'block';
                $t->save();
            }
        }
    }
}
