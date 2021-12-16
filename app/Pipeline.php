<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Pipeline extends Model
{


    protected $table = 'pipeline';

    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'cliente',
        'qualificacao',
        'proposta',
        'fechamento',
        'negociacao',
        'idautor',
        'id_ult_alterador',
        'excluidopipeline',

    ];

    public function consultaPipeline($id)
    {   
        $string = '';
        if($id){ $string .= ' AND id='.$id; }else { $string .= ''; }

        $stringQuery = "SELECT * from pipeline
                        WHERE excluidopipeline = 0" . $string;

        return $stringQuery;
    }
}
