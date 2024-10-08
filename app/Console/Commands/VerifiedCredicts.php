<?php

namespace App\Console\Commands;

use App\Models\Credit;
use App\Models\PaymentHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VerifiedCredicts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credits:verification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verified all Open credits if they have his payments up today';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $credits = Credit::where('credit_status_id','=' ,'1')->get();

            Log::channel('cronJobs')->info('start');

            foreach ($credits as $credit){
                Log::channel('cronJobs')->info('credit = ' . $credit->id);

                $paymentHistories = PaymentHistory::where('credit_id','=', $credit->id)->orderBy('day', 'desc')->limit(1)->get();
                $starDate = Carbon::parse($credit->date);

                Log::channel('cronJobs')->info('$starDate = ' . $starDate);
                Log::channel('cronJobs')->info('billing_schedule_id = ' . $credit->billingSchedule->option );

                switch ($credit->billing_schedule_id){
                    case 1: // Semanal
                        $star = Carbon::now('America/Costa_Rica')->startOfWeek();
                        $end = Carbon::now('America/Costa_Rica')->endOfWeek();

                        $isNew = $starDate->addDay(7) >= Carbon::now();


                        break;
                    case 2: //Quincenal

                        if(Carbon::now()->day >= 15){
                            $star = Carbon::now('America/Costa_Rica')->day(1);
                            $end = Carbon::now('America/Costa_Rica')->day(15);
                        }else{
                            $star = Carbon::now('America/Costa_Rica')->subMonth(1)->day(15);
                            $end = Carbon::now('America/Costa_Rica')->subMonth(1)->lastOfMonth();
                        }

                        $isNew = $starDate->addDay(15) > Carbon::now('America/Costa_Rica');


                        break;
                    case 3: //Mensual
                        $star = Carbon::now('America/Costa_Rica')->subMonth(1)->day($starDate->day);
                        $end = Carbon::now('America/Costa_Rica')->day($starDate->day)->addDay(1);

                        $isNew = $end->month < ($starDate->addMonth()->month);


                        break;
                }

                Log::channel('cronJobs')->info('$isNew = '. $isNew);
                Log::channel('cronJobs')->info('$star = '. $star);
                Log::channel('cronJobs')->info('$end = ' .$end);
                Log::channel('cronJobs')->info('payment Day = ' .$paymentHistories[0]->day);

                if(!$isNew){

                    if( $paymentHistories->isEmpty() ){

                        $credit->update([
                            'credit_status_id' => 3
                        ]);
                        Log::channel('cronJobs')->info('1');
                        Log::channel('cronJobs')->info('No tiene historial');

                    }elseif( Carbon::parse($paymentHistories[0]->day)->between($star, $end)  ){ //$star >= Carbon::parse($paymentHistories[0]->day) and  Carbon::parse($paymentHistories[0]->day)  <= $end

                        $credit->update([
                            'credit_status_id' => 1
                        ]);

                        Log::channel('cronJobs')->info('2');
                        Log::channel('cronJobs')->info($star . ' >= ' . Carbon::parse($paymentHistories[0]->day) . ' and ' .  Carbon::parse($paymentHistories[0]->day)  . ' <= ' . $end);

                    }elseif ( Carbon::parse($paymentHistories[0]->day)->between($end, Carbon::now('America/Costa_Rica'))   ){ //Carbon::parse($paymentHistories[0]->day) <= Carbon::now('America/Costa_Rica')

                        $credit->update([
                            'credit_status_id' => 1
                        ]);

                        Log::channel('cronJobs')->info('3');
                        Log::channel('cronJobs')->info($end . ' >= ' . Carbon::parse($paymentHistories[0]->day) . ' and ' .  Carbon::parse($paymentHistories[0]->day)  . ' <= ' . Carbon::now('America/Costa_Rica'));

                    }else{

                        $credit->update([
                            'credit_status_id' => 3
                        ]);

                        Log::channel('cronJobs')->info('4');
                        Log::channel('cronJobs')->info('Else');

                    }
                    $credit->save() ;

                }
            }

        }catch (\Exception $exception){

            Log::channel('cronJobs')->info('Error');
            Log::channel('cronJobs')->error($exception);
            Log::channel('cronJobs')->info('end');
            Log::channel('cronJobs')->info(' ');

            return Command::FAILURE;
        }
        return Command::SUCCESS;
    }
}
