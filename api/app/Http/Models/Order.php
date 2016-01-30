<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    public function insertOrder()
    {
        if (func_num_args() > 0)
        {
            $data = func_get_arg(0);

            $result = DB::table('orders')->insert($data);
            if ($result)
                return 1;
            else
                return 0;
        }
    }


}//END OF CLASS ORDER
