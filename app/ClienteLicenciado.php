<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class ClienteLicenciado extends Model
{

    protected $table = 'clienteslicenciado';

    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'id',
        'c_idlicenciado',
        'c_nome',
        'c_cnpj',
        'c_email',
        'c_endereco',
        'c_telefone',
        'c_estado',
        'c_municipio',
        'c_excluido',

    ];

    public function consultaClienteLicenciado($permiteListagemCompleta, $id)
    {   
        if($permiteListagemCompleta == 1){
            $complemento = "";
        }
        else{
            $complemento = " and c.c_idlicenciado = " .$id;
        }
        $stringQuery = "SELECT  c.*, u.name  from clienteslicenciado c, users u   
            where c.c_excluido = 0 and u.id = c_idlicenciado ". $complemento;

        return $stringQuery;
    }

    public function consultaHistoricoClienteLicenciado($id)
    {   
        $string = '';
        if($id): $string .= ' AND u.id='.$id; else : $string .= ''; endif;

        $stringQuery = "SELECT h.id, h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao,
        date_format(h_dt_proposta,'%Y-%m-%d')as h_dt_proposta, date_format(h_dtoperacao,'%Y-%m-%d')as h_dtoperacao, u.name as licenciado
        
        from historicopipeline h, users u 
        where u.id = h_id_ult_alterador " . $string;

        return $stringQuery;
    }

    public function insereHistorico($dadosrequisicao, $idlicenciado, $tipo)
    {
        if ($tipo == 1){
            $tipooperacao = 'Cadastro';
            $dadosrequisicao[0]->updated_at = $dadosrequisicao[0]->created_at;
            $campo_h_dt_proposta ='h_dt_proposta,';

            $stringQuery = "INSERT INTO historicocriativo
            (hc_id, hc_idcriativo, hc_cliente, hc_tipocriativo, hc_quantidade, hc_valunit, hc_valtotal, hc_tipooperacao, hc_idlicenciado, hc_id_ult_alterador, hc_datacricao, hc_dataalteracao)
            VALUES(NULL, '".$dadosrequisicao[0]->id."', '".$dadosrequisicao[0]->cliente."', '".$dadosrequisicao[0]->tipocriativo."', '".$dadosrequisicao[0]->quantidade."', '".$dadosrequisicao[0]->valunit."', '".$dadosrequisicao[0]->valtotal."', '".$tipooperacao."', '".$idlicenciado."', '".$idlicenciado."', '".$dadosrequisicao[0]->created_at."', '".$dadosrequisicao[0]->updated_at."')";

        }

        if($tipo == 2){
            $tipooperacao = 'Atualização';
            $dataoperacao = date('Y-m-d');
            $campo_h_dt_proposta ='';
            $criadoem = "";
            $idpipeline = $dadosrequisicao[0]->id;

            $stringQuery = "INSERT INTO historicocriativo
            (hc_id, hc_idcriativo, hc_cliente, hc_tipocriativo, hc_quantidade, hc_valunit, hc_valtotal, hc_tipooperacao, hc_idlicenciado, hc_id_ult_alterador, hc_dataalteracao)
            VALUES(NULL, '".$dadosrequisicao[0]->id."', '".$dadosrequisicao[0]->cliente."', '".$dadosrequisicao[0]->tipocriativo."', '".$dadosrequisicao[0]->quantidade."', '".$dadosrequisicao[0]->valunit."', '".$dadosrequisicao[0]->valtotal."', '".$tipooperacao."', '".$idlicenciado."', '".$idlicenciado."', '".$dataoperacao."')";

        }
        
        if($tipo == 3){
            $tipooperacao = 'Exclusão';
            $dataoperacao = date('Y-m-d');
            $campo_h_dt_proposta ='h_dt_proposta,';
            $criadoem = $dadosrequisicao->created_at ."','";

            $stringQuery = "INSERT INTO historicocriativo
            (hc_id, hc_idcriativo, hc_cliente, hc_tipocriativo, hc_quantidade, hc_valunit, hc_valtotal, hc_tipooperacao, hc_idlicenciado, hc_id_ult_alterador, hc_dataalteracao)
            VALUES(NULL, '".$dadosrequisicao->id."', '".$dadosrequisicao->cliente."', '".$dadosrequisicao->tipocriativo."', '".$dadosrequisicao->quantidade."', '".$dadosrequisicao->valunit."', '".$dadosrequisicao->valtotal."', '".$tipooperacao."', '".$dadosrequisicao->idlicenciado."', '".$dadosrequisicao->id_ult_alterador."', '".$dataoperacao."')";

        }
        return $stringQuery;


        // if($tipo == 4){
        //     $tipooperacao = 'Visualização de Pipeline';
        //     $dataoperacao = date('Y-m-d');
            
        //     $stringQuery = "INSERT INTO historicopipeline
        //     (id, h_idpipeline, h_cliente, h_qualificacao, h_proposta, h_negociacao, h_fechamento, h_tipooperacao, h_dt_proposta, h_dtoperacao, h_id_ult_alterador)
        //     VALUES(NULL, '".$pipelineatualizado->id."', '".$dadosrequisicao[0]->cliente."', '".$dadosrequisicao[0]->qualificacao."', '".$dadosrequisicao[0]->proposta."', '".$dadosrequisicao[0]->fechamento."', '".$dadosrequisicao[0]->negociacao."', '".$tipooperacao."', '".$dadosrequisicao[0]->created_at."', '".$dataoperacao."', '".$idlicenciado."')";
        // }
        // die($stringQuery);

    }
}
