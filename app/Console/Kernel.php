<?php

namespace App\Console;
use DB;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {

            $anoAtual = date('Y');
            $dadosmensalidade    = DB::select("SELECT valor from mensalidade where ano = $anoAtual");
            $dadosreajuste  = DB::select("SELECT * from tb_reajuste where excluidoreajuste = 0");
            
            $mensalidade = $dadosmensalidade[0]->valor;
            foreach ($dadosreajuste as $reajuste) {

                $reconsultareajusteAntigo     = DB::select("SELECT id, idusuario, valor from tb_reajuste where excluidoreajuste = 0 order by id desc limit 1");
                
                $dataUltimoReajuste = new \DateTime($reajuste->dtreajuste);
                $dataAtual = new \DateTime(date('Y-m-d'));
                $dataAtualSemFormat = date('Y-m-d');
                $diferenca = $dataUltimoReajuste->diff($dataAtual);
                $tempoEmAnosSemReajuste = $diferenca->y;
                print($tempoEmAnosSemReajuste);
    
                DB::beginTransaction();

                DB::statement("UPDATE tb_reajuste
                SET valor= $mensalidade, dtreajuste='".date("Y-m-d H:i:s")."'
                WHERE id= $reajuste->id and dtreajuste not like '$anoAtual%';
                ");
                
                $reconsultareajuste     = DB::select("SELECT id, idusuario, valor from tb_reajuste where excluidoreajuste = 0 order by dtreajuste desc limit 1");
                if(($reconsultareajusteAntigo[0]->id != $reconsultareajuste[0]->id) || ($reconsultareajusteAntigo[0]->valor != $reconsultareajuste[0]->valor)){
                    
                    $reconsultamensalidade  = DB::select("SELECT id from mensalidade where excluidomensalidade = 0 order by id desc limit 1");
                    
                    
                    DB::insert("INSERT INTO historicomensalidade
                 (id, idmensalidade, ano, valor, id_usr_criador, operacao, dataoperacao, excluidomensalidade, created_at, updated_at)
                 VALUES (NULL, '".$reconsultamensalidade[0]->id."', '".$anoAtual."', '".$mensalidade."', '".$reconsultareajuste[0]->idusuario."', 'Reajuste', '".$dataAtualSemFormat."', '0', '".$dataAtualSemFormat."', '".$dataAtualSemFormat."')");
                    DB::commit();
                }
                 else {
                    DB::rollBack();
                }
            }
     
        //  })->dailyAt('06:00');
         })->everyMinute();


        // $schedule->command('inspire')
        //          ->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
