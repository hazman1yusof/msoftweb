<?php

namespace App\Exports\Sheets;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;

class ContributionExportSheet implements FromQuery, WithTitle
{
    private $drcode;

    public function __construct(string $drcode)
    {
        $this->drcode = $drcode;
    }

    /**
     * @return Builder
     */
    public function query()
    {
        $drcode = DB::table('debtor.drcontrib')
            ->select('drcontrib.drcode', 'doctor.doctorname', 'drcontrib.chgcode')
            ->leftJoin('hisdb.doctor', function($join){
                $join = $join->on('doctor.doctorcode', '=', 'drcontrib.drcode');
                $join = $join->on('doctor.compcode', '=', 'drcontrib.compcode');
            })
            ->where('drcontrib.compcode','=',session('compcode'))
            ->get();
        }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->drcode;
    }
}