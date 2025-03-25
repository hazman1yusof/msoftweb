<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use DB;

class StockCountImport implements ToCollection, WithCalculatedFormulas{
    /**
     * @param array $row
     *
     * @return User|null
     */


    public function __construct($recno){
        $this->recno = $recno;
    }

    public function collection(Collection $rows){
        // DB::beginTransaction();

        // try {
            $phycnthd = DB::table('material.phycnthd')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$this->recno)
                            ->first();

            foreach ($rows as $key => $row) {
                if($row[11] === null || $key == 0){
                    continue;
                }

                $lineno_ = $row[0];
                $itemcode = $row[1];
                $phyqty = $row[11];
                
                $phycntdt = DB::table("material.phycntdt")
                        ->where('compcode',session('compcode'))
                        ->where('recno',$phycnthd->recno)
                        ->where('itemcode',$itemcode);
                        // ->where('lineno_',$lineno_)

                if($phycntdt->exists()){
                    $phycntdt = $phycntdt->first();

                    $vrqty = (int)$phyqty - (int)$phycntdt->thyqty;
                    
                    DB::table("material.phycntdt")
                            ->where('compcode',session('compcode'))
                            ->where('recno',$phycnthd->recno)
                            ->where('itemcode',$itemcode)
                            ->where('lineno_',$lineno_)
                            ->update([
                                'phyqty' => $phyqty,
                                'vrqty' => $vrqty
                            ]);
                }
            }

        //     DB::commit();
        // } catch (\Exception $e) {
        //     DB::rollback();

        //     return response($e->getMessage(), 500);
        // }
    }
}