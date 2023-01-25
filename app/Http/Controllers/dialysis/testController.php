<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use stdClass;
use DB;
use Carbon\Carbon;
use Response;
use Auth;
use Storage;

class testController extends Controller
{   
    
    public function __construct()
    {

    }

    // public function test(Request $request){

    //     $array = [
    //         ['670729095079','JHEV'],
    //         ['950516106375','MAIS'],
    //         ['771114055591','RAMPAI'],
    //         ['530925086669','BAITULMAL'],
    //         ['950406125604','BAITULMAL'],
    //         ['511217105677','BAITULMAL'],
    //         ['760806145818','BAITULMAL'],
    //         ['590130105672','JPA'],
    //         ['870813145661','BAITULMAL'],
    //         ['770701016517','KPM'],
    //         ['750830086089','BAITULMAL'],
    //         ['560906086409','BAITULMAL'],
    //         ['550615075495','BAITULMAL'],
    //         ['860323295035','BAITULMAL'],
    //         ['920609035847','BAITULMAL'],
    //         ['680705715228','BAITULMAL'],
    //         ['770806715031','BAITULMAL'],
    //         ['670509105554','BAITULMAL'],
    //         ['550124115368','MAIS'],
    //         ['620519715758','BAITULMAL'],
    //         ['580310086502','BAITULMAL'],
    //         ['580704085318','BAITULMAL'],
    //         ['690402085053','BAITULMAL'],
    //         ['850715145248','BAITULMAL'],
    //         ['550225035471','UTM'],
    //         ['680808105094','BAITULMAL'],
    //         ['550612055181','MAIS'],
    //         ['521024085947','JHEV'],
    //         ['740517025408','PERKESO'],
    //         ['700727135836','BAITULMAL'],
    //         ['850404086594','BAITULMAL'],
    //         ['760320105867','PERKESO'],
    //         ['780821615046','BAITULMAL'],
    //         ['580905715427','MAIS'],
    //         ['771113145448','BAITULMAL'],
    //         ['800414145548','BAITULMAL'],
    //         ['420515085711','JPA'],
    //         ['520701105809','JHEV'],
    //         ['570514105155','PERKESO'],
    //         ['581020025351','JPA'],
    //         ['830309145017','MAIS'],
    //         ['760814035651','MAIS'],
    //         ['430909055151','JPA'],
    //         ['850913086795','BAITULMAL'],
    //         ['540315035337','BAITULMAL'],
    //         ['660219085277','BAITULMAL'],
    //         ['660101066927','BAITULMAL'],
    //         ['470129085153','BAITULMAL'],
    //         ['640930715017','BAITULMAL'],
    //         ['900718095197','BAITULMAL'],
    //         ['860121145645','PERKESO'],
    //         ['910626105705','BAITULMAL'],
    //         ['880922565453','BAITULMAL'],
    //         ['920412045048','BAITULMAL'],
    //         ['910124146400','BAITULMAL'],
    //         ['470330055394','JHEV'],
    //         ['560326105562','BAITULMAL'],
    //         ['830609145414','PERKESO'],
    //         ['561213105026','JPA'],
    //         ['660612086532','BAITULMAL'],
    //         ['480302105488','JPA'],
    //         ['640904065612','BAITULMAL'],
    //         ['581123085074','JPA'],
    //         ['850520015312','BAITULMAL'],
    //         ['860401436156','BAITULMAL'],
    //         ['640821107055','BAITULMAL'],
    //         ['620406086084','BAITULMAL'],
    //         ['560510016252','JPA'],
    //         ['571001055054','BAITULMAL'],
    //         ['580331025784','BAITULMAL'],
    //         ['641010107670','PERKESO'],
    //         ['760810086654','BAITULMAL'],
    //         ['590622065064','PERKESO'],
    //         ['510618715068','BAITULMAL'],
    //         ['671016105841','BAITULMAL'],
    //         ['610509015597','JPA'],
    //         ['670903106264','BAITULMAL'],
    //         ['620128106400','BAITULMAL'],
    //         ['620730045348','BAITULMAL'],
    //         ['640809105022','PERKESO'],
    //         ['521001105757','MAIS'],
    //         ['520925105074','JHEV'],
    //         ['720927145156','BAITULMAL'],
    //         ['641207106102','MAIS'],
    //         ['690824025325','BAITULMAL'],
    //         ['M 5065820','BAITULMAL'],
    //         ['810913145592','BAITULMAL'],
    //         ['550124085568','BAITULMAL'],
    //         ['700612105864','BAITULMAL'],
    //         ['990415145058','BAITULMAL'],
    //         ['610408075320','DBKL'],
    //         ['691110106304','JPA'],
    //         ['710617106101','BAITULMAL'],
    //         ['720818095049','PERKESO'],
    //         ['521027055241','JHEV'],
    //         ['750304086396','PERKESO'],
    //         ['501109085761','JHEV'],
    //         ['660605715150','BAITULMAL'],
    //         ['570323045604','JPA'],
    //         ['600217106236','BAITULMAL'],
    //         ['391219105300','UKM'],
    //         ['580501036066','BAITULMAL'],
    //         ['811024086603','PERKESO']
    //     ];

    //     DB::beginTransaction();

    //     try {

    //         foreach ($array as $key => $value) {
    //             $debtorcode = trim($value[1]);
    //             $newic = trim($value[0]);
    //             echo $newic;
    //             switch ($debtorcode) {
    //                 case 'BAITULMAL':
    //                         $epis_fin = 'BM';
    //                     break;
    //                 case 'JPA':
    //                         $epis_fin = 'JK';
    //                     break;
    //                 case 'PERKESO':
    //                         $epis_fin = 'PS';
    //                     break;
    //                 case 'DBKL':
    //                         $epis_fin = 'JK';
    //                     break;
    //                 case 'UKM':
    //                         $epis_fin = 'JK';
    //                     break;
    //                 case 'JHEV':
    //                         $epis_fin = 'JK';
    //                     break;
    //                 case 'MAIS':
    //                         $epis_fin = 'MA';
    //                     break;
    //                 case 'RAMPAI':
    //                         $epis_fin = 'JK';
    //                     break;
    //                 case 'KPM':
    //                         $epis_fin = 'JK';
    //                     break;
    //                 default:
    //                         $epis_fin = 'JK';
    //                     break;
    //             }

    //             $pat_mast = DB::table('hisdb.pat_mast')
    //                             ->where('Newic',$newic)
    //                             ->where('compcode','13A')
    //                             ->orderBy('idno','DESC');

    //             if($pat_mast->exists()){
    //                 $pat_mast_data = $pat_mast->first();
    //             }

    //             $newepisno = intval($pat_mast_data->Episno) + 1;
    //             $name = $pat_mast_data->Name;
    //             $mrn = $pat_mast_data->MRN;

    //             $episode = DB::table('hisdb.episode')
    //                             ->where('compcode','13A')
    //                             ->where('mrn',$mrn)
    //                             ->where('episno',$newepisno);

    //             if($episode->exists()){
    //                 continue;
    //             }

    //             $pat_mast
    //                 ->update([
    //                     'episno' => $newepisno,
    //                     'patstatus' => 1,
    //                     'last_visit_date' => '2022-10-01',
    //                     'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
    //                     'LastUser' => 'system'
    //                 ]);

    //             DB::table("hisdb.episode")
    //                 ->insert([
    //                     "compcode" => '13A',
    //                     "mrn" => $mrn,
    //                     "episno" => $newepisno,
    //                     "epistycode" => 'OP',
    //                     "reg_date" => '2022-10-01',
    //                     "reg_time" => Carbon::now("Asia/Kuala_Lumpur"),
    //                     "regdept" => 'BM',
    //                     "admsrccode" => 'APPT',
    //                     "case_code" => 'HDS',
    //                     "admdoctor" => 'HALIM',
    //                     "attndoctor" => 'AZMAN',
    //                     "pay_type" => $epis_fin,
    //                     "pyrmode" => 'PANEL',
    //                     "billtype" => 'OP',
    //                     "payer" => $debtorcode,
    //                     "followupNP" => 1,
    //                     "adddate" => Carbon::now("Asia/Kuala_Lumpur"),
    //                     "adduser" => 'system',
    //                     "episactive" => 1,
    //                     "allocpayer" => 1,
    //                     'episstatus' => 'CURRENT',
    //                 ]);

    //             DB::table('hisdb.epispayer')
    //                 ->insert([
    //                     'CompCode' => '13A',
    //                     'MRN' => $mrn,
    //                     'Episno' => $newepisno,
    //                     'EpisTyCode' => 'OP',
    //                     'LineNo' => '1',
    //                     'BillType' => 'OP',
    //                     'PayerCode' => $debtorcode,
    //                     'Pay_Type' => $epis_fin,
    //                     'AddDate' => Carbon::now("Asia/Kuala_Lumpur"),
    //                     'AddUser' => 'system',
    //                     'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur"),
    //                     'LastUser' => 'system'
    //                 ]);

    //             $queue_obj = DB::table('sysdb.sysparam')
    //                     ->where('compcode','=','13A')
    //                     ->where('source','=','QUE')
    //                     ->where('trantype','=','OP');

    //             $queue_data = $queue_obj->first();

    //             //ni start kosong balik bila hari baru
    //             if($queue_data->pvalue2 != Carbon::now("Asia/Kuala_Lumpur")->toDateString()){
    //                 $queue_obj
    //                     ->update([
    //                         'pvalue1' => 1,
    //                         'pvalue2' => Carbon::now("Asia/Kuala_Lumpur")->toDateString()
    //                     ]);

    //                 $current_pvalue1 = 1;
    //             }else{
    //                 $current_pvalue1 = intval($queue_data->pvalue1);
    //             }


    //             //tambah satu dkt queue sysparam
    //             $queue_obj
    //                 ->update([
    //                     'pvalue1' => $current_pvalue1+1
    //                 ]);

    //             DB::table('hisdb.queue')
    //                 ->insert([
    //                     'AdmDoctor' => 'HALIM',
    //                     'AttnDoctor' => 'AZMAN',
    //                     'BedType' => '',
    //                     'Case_Code' => "MED",
    //                     'CompCode' => '13A',
    //                     'Episno' => $newepisno,
    //                     'EpisTyCode' => 'OP',
    //                     'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
    //                     'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
    //                     'Lastuser' => 'system',
    //                     'MRN' => $mrn,
    //                     'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
    //                     'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
    //                     'Bed' => '',
    //                     'Room' => '',
    //                     'QueueNo' => $current_pvalue1,
    //                     'Deptcode' => 'ALL',
    //                     // 'DOB' => $this->null_date($patmast_data->DOB),
    //                     'NAME' => $name,
    //                     'Newic' => $newic,
    //                     // 'Oldic' => $patmast_data->Oldic,
    //                     // 'Sex' => $patmast_data->Sex,
    //                     // 'Religion' => $patmast_data->Religion,
    //                     // 'RaceCode' => $patmast_data->RaceCode,
    //                     'EpisStatus' => '',
    //                     'chggroup' => 'OP'
    //                 ]);

    //             DB::table('hisdb.queue')
    //                 ->insert([
    //                     'AdmDoctor' => 'HALIM',
    //                     'AttnDoctor' => 'AZMAN',
    //                     'BedType' => '',
    //                     'Case_Code' => "MED",
    //                     'CompCode' => '13A',
    //                     'Episno' => $newepisno,
    //                     'EpisTyCode' => "OP",
    //                     'LastTime' => Carbon::now("Asia/Kuala_Lumpur")->toTimeString(),
    //                     'Lastupdate' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
    //                     'Lastuser' => session('username'),
    //                     'MRN' => $mrn,
    //                     'Reg_Date' => Carbon::now("Asia/Kuala_Lumpur")->toDateString(),
    //                     'Reg_Time' => Carbon::now("Asia/Kuala_Lumpur")->toDateTimeString(),
    //                     'Bed' => '',
    //                     'Room' => '',
    //                     'QueueNo' => $current_pvalue1,
    //                     'Deptcode' => 'SPEC',
    //                     // 'DOB' => $this->null_date($patmast_data->DOB),
    //                     'NAME' => $name,
    //                     'Newic' => $newic,
    //                     // 'Oldic' => $patmast_data->Oldic,
    //                     // 'Sex' => $patmast_data->Sex,
    //                     // 'Religion' => $patmast_data->Religion,
    //                     // 'RaceCode' => $patmast_data->RaceCode,
    //                     'EpisStatus' => '',
    //                     'chggroup' => 'OP'
    //                 ]);

    //         }

    //         DB::commit();
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         dd($e);
    //         // return response('Error'.$e, 500);
    //     }

    // }

    // public function test2(Request $request){

    //     $dialysis = DB::table('hisdb.dialysis')
    //                     ->whereNull('compcode')
    //                     ->whereNull('visit_date')
    //                     ->whereNotNull('visit_date_2')
    //                     ->get();

    //     foreach ($dialysis as $key => $value) {
    //         if(empty($value->visit_date_2)){
    //             continue;
    //         }
    //         $newvisit = explode('/',$value->visit_date_2);
    //         $visit_date = $newvisit[2].'-'.$newvisit[1].'-'.$newvisit[0];
            
    //         DB::table('hisdb.dialysis')
    //                 ->where('idno',$value->idno)
    //                 ->update([
    //                     'visit_date' => $visit_date
    //                 ]);
    //     }

    // }

    // public function test3(Request $request){

    //     $array = [
    //         ['302','66.5','4'],
    //         ['274','55.5','4'],
    //         ['177','52','4'],
    //         ['301','47.5','4'],
    //         ['290','72.5','4'],
    //         ['186','49','4'],
    //         ['201','49.5','4'],
    //         ['250','68','4'],
    //         ['187','71.5','4'],
    //         ['248','74.5','4'],
    //         ['188','61.5','4'],
    //         ['282','40.5','4'],
    //         ['207','76','4'],
    //         ['277','71.5','4'],
    //         ['208','71','4'],
    //         ['270','88','4'],
    //         ['179','57.5','4'],
    //         ['238','74','4'],
    //         ['156','82.5','4'],
    //         ['296','68.5','4'],
    //         ['289','69','4'],
    //         ['299','40.5','4'],
    //         ['158','56','4'],
    //         ['241','65','4'],
    //         ['284','79.5','4'],
    //         ['223','51','4'],
    //         ['199','48.5','4'],
    //         ['234','109','4'],
    //         ['269','66','4'],
    //         ['168','56','4'],
    //         ['246','49.5','4'],
    //         ['245','52','4'],
    //         ['225','59.5','4'],
    //         ['172','63.5','4'],
    //         ['235','62.5','4'],
    //         ['278','82.5','4'],
    //         ['185','57.5','4'],
    //         ['305','56.5','4'],
    //         ['257','64.5','4'],
    //         ['262','54','4'],
    //         ['165','60','4'],
    //         ['212','55','4'],
    //         ['203','62','4'],
    //         ['279','117','4']
    //     ];

    //     DB::beginTransaction();

    //     try {

    //         foreach ($array as $key => $value) {
    //             $mrn = trim($value[0]);
    //             $dry_weight = trim($value[1]);
    //             $duration_hd = trim($value[2]);

    //             $episode = DB::table('hisdb.episode')
    //                         ->where('compcode','13A')
    //                         ->where('mrn',$mrn);

    //             if($episode->exists()){
    //                 $episode->update([
    //                     'dry_weight' => $dry_weight,
    //                     'duration_hd' => $duration_hd
    //                 ]);
    //             }
    //         }

    //         DB::commit();
    //     } catch (Exception $e) {
    //         DB::rollback();
    //         dd($e);
    //         // return response('Error'.$e, 500);
    //     }
    // }

    public function test(Request $request){
        dd('sdsd');
        return view('test.test');
    }

}