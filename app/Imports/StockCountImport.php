<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;

class StockCountImport implements ToCollection{
    /**
     * @param array $row
     *
     * @return User|null
     */


    public function __construct($recno){
        $this->recno = $recno;
    }

    public function collection(Collection $rows){
        DB::beginTransaction();

        try {
            $phycnthd = DB::table('material.phycnthd')
                            ->where('compcode',session('compcode'))
                            ->where('recno',$this->recno)
                            ->first();

            foreach ($rows as $key => $row) {
                if($row[11] == null || $key == 0){
                    continue;
                }
                $lineno_ = $row[0];
                $itemcode = $row[1];
                $phyqty = $row[11];
                
                DB::table("material.phycntdt")
                        ->where('compcode',session('compcode'))
                        ->where('recno',$phycnthd->recno)
                        ->where('itemcode',$itemcode)
                        ->where('lineno_',$lineno_)
                        ->update([
                            'phyqty' => $phyqty,
                        ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response($e->getMessage(), 500);
        }
    }
}