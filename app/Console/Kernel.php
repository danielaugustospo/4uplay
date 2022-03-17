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
            
            $listaTotensAssociadosALicenciado = DB::select("select n_serie, idlicenciado from totem t where t.excluidototem = 0 and t.idlicenciado != ''");
            
            foreach ($listaTotensAssociadosALicenciado as $listaTotens) {
                $consultaHistoricoTotem  = DB::select("SELECT distinct ht.* from historico_totemcliente ht
                where 
                    (ht.h_excluidototem = 'Cadastro' 
                    or ht.h_excluidototem = 'Associado' 
                    or ht.h_excluidototem = 'Atualização' 
                    or ht.h_excluidototem = 'Atualização Mensal')
                    and
                    h_idtotem = '". $listaTotens->n_serie ."'
                           
                
                group by  updated_at
                order by updated_at desc
                limit 1");

             foreach ($consultaHistoricoTotem as $consultaHistoricoTotem) {

                $ultimaData = date("m", strtotime($consultaHistoricoTotem->updated_at));
                $mesHoje = date("m");
                $dataatualizacao = $anoAtual . "-" . $ultimaData ."-01";
                print($ultimaData. "Id ". $consultaHistoricoTotem->id ." - Ultima Data " ."\n");
                print($mesHoje. "Mes Hoje "."\n");
                print($dataatualizacao. "Data Atualização "."\n");

                if ($ultimaData !=  $mesHoje) {

                    $tipooperacao = 'Atualização Mensal';
                    $dataatualizacao = $anoAtual . "-" . $ultimaData ."-01";
                    $dataoperacao = date('Y-m-d');
                    DB::beginTransaction();

                    DB::insert("INSERT INTO historico_totemcliente
                        (id, h_idcliente, h_idtotem, h_totemassociado, h_dtassociado, h_excluidototem, updated_at)
                        VALUES(NULL, '" . $consultaHistoricoTotem->h_idcliente  . "', '" . $consultaHistoricoTotem->h_idtotem . "', '" . $consultaHistoricoTotem->h_totemassociado . "', '" . $dataoperacao . "',   '" . $tipooperacao . "',  '" . $dataoperacao . "')");
                    DB::commit();
                }
            }
                // var_dump($consultaHistoricoTotem);
            }


            // foreach ($consultaHistoricoTotem as $consultaHistoricoTotem) {

            //     $ultimaData = date("m", strtotime($consultaHistoricoTotem->updated_at));
            //     $mesHoje = date("m");
            //     $dataatualizacao = $anoAtual . "-" . $ultimaData ."-01";
            //     print($ultimaData. "Id ". $consultaHistoricoTotem->id ." - Ultima Data " ."\n");
            //     print($mesHoje. "Mes Hoje "."\n");
            //     print($dataatualizacao. "Data Atualização "."\n");

            //     // if ($ultimaData !=  $mesHoje) {

            //     //     $tipooperacao = 'Atualização Mensal';
            //     //     $dataatualizacao = $anoAtual . "-" . $ultimaData ."-01";
            //     //     $dataoperacao = date('Y-m-d');
            //     //     DB::beginTransaction();

            //     //     DB::insert("INSERT INTO historico_totemcliente
            //     //         (id, h_idcliente, h_idtotem, h_totemassociado, h_dtassociado, h_excluidototem, updated_at)
            //     //         VALUES(NULL, '" . $consultaHistoricoTotem->h_idcliente  . "', '" . $consultaHistoricoTotem->h_idtotem . "', '" . $consultaHistoricoTotem->h_totemassociado . "', '" . $dataoperacao . "',   '" . $tipooperacao . "',  '" . $dataoperacao . "')");
            //     //     DB::commit();
            //     // }
            // }
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
                SET valor= $mensalidade, dtreajuste='" . date("Y-m-d H:i:s") . "'
                WHERE id= $reajuste->id and dtreajuste not like '$anoAtual%';
                ");

                $reconsultareajuste     = DB::select("SELECT id, idusuario, valor from tb_reajuste where excluidoreajuste = 0 order by dtreajuste desc limit 1");
                if (($reconsultareajusteAntigo[0]->id != $reconsultareajuste[0]->id) || ($reconsultareajusteAntigo[0]->valor != $reconsultareajuste[0]->valor)) {

                    $reconsultamensalidade  = DB::select("SELECT id from mensalidade where excluidomensalidade = 0 order by id desc limit 1");


                    DB::insert("INSERT INTO historicomensalidade
                 (id, idmensalidade, ano, valor, id_usr_criador, operacao, dataoperacao, excluidomensalidade, created_at, updated_at)
                 VALUES (NULL, '" . $reconsultamensalidade[0]->id . "', '" . $anoAtual . "', '" . $mensalidade . "', '" . $reconsultareajuste[0]->idusuario . "', 'Reajuste', '" . $dataAtualSemFormat . "', '0', '" . $dataAtualSemFormat . "', '" . $dataAtualSemFormat . "')");
                    DB::commit();
                } else {
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
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
