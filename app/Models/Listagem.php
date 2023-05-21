<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Base;
use Illuminate\Support\Facades\DB;

class Listagem extends Base
{
    protected $table = "listagem";

    public function getListagem($recordsPerPage = 20)
    {
        $dados = Listagem::select(
            'listagem.id',
            'listagem.created_at',
            DB::raw('COALESCE(e.total, 0)  AS lidos'),
            DB::raw('COALESCE(li.total, 0) AS validados')
        )
            ->leftJoin(DB::raw('(SELECT COUNT(*) AS total, id_listagem FROM endereco GROUP BY id_listagem) AS e'), 'listagem.id', '=', 'e.id_listagem')
            ->leftJoin(DB::raw('(SELECT COUNT(*) AS total, id_listagem FROM endereco WHERE responses=1 GROUP BY id_listagem) AS li'), 'li.id_listagem', '=', 'e.id_listagem')
            ->paginate($recordsPerPage);

        return $dados;
    }

}
