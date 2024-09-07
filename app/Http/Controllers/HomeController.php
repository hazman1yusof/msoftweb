<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class HomeController extends Controller
{   
    var $_x=-2;
    var $_menu_str = "";
    var $_arr = array();

    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $user = Auth::user();
        $menu = $this->create_main_menu();
        $units = DB::table('sysdb.sector')
                ->where('compcode','=',$user->compcode)
                ->get();
        $unit_user = '';
        if($user->dept != ''){
            $unit_user_ = DB::table('sysdb.department')
                ->where('compcode','=',$user->compcode)
                ->where('deptcode','=',$user->dept)
                ->first();
            $unit_user = $unit_user_->sector;
        }
        $title = DB::table('sysdb.company')->where('compcode',session('compcode'))->first()->name; 
        $dept_desc = $unit_user_->description;
        $shortcut=false;

        return view('init.container',compact('menu','units','unit_user','title','dept_desc','shortcut'));
    }

    public function ptcare(){
        $user = Auth::user();
        $menu = $this->create_ptcare_menu();
        $units = DB::table('sysdb.sector')
                ->where('compcode','=',$user->compcode)
                ->get();
        $unit_user = '';
        $title="Allied Healthcare";
        if($user->dept != ''){
            $unit_user_ = DB::table('sysdb.department')
                ->where('compcode','=',$user->compcode)
                ->where('deptcode','=',$user->dept)
                ->first();
            $unit_user = $unit_user_->sector;
        }
        $dept_desc = $unit_user_->description;
        return view('init.container_ptcare',compact('menu','units','unit_user','title','dept_desc'));
    }

    public function dialysis(){
        $user = Auth::user();
        $menu = $this->create_dialysis_menu();
        $units = DB::table('sysdb.sector')
                ->where('compcode','=',$user->compcode)
                ->get();
        $unit_user = '';
        $title="Dialysis";
        if($user->dept != ''){
            $unit_user_ = DB::table('sysdb.department')
                ->where('compcode','=',$user->compcode)
                ->where('deptcode','=',$user->dept)
                ->first();
            $unit_user = $unit_user_->sector;
        }
        $dept_desc = $unit_user_->description;
        return view('init.container_ptcare',compact('menu','units','unit_user','title','dept_desc'));
    }

    public function warehouse(){
        $user = Auth::user();
        $menu = $this->create_warehouse_menu();
        $units = DB::table('sysdb.sector')
                ->where('compcode','=',$user->compcode)
                ->get();
        $unit_user = '';
        if($user->dept != ''){
            $unit_user_ = DB::table('sysdb.department')
                ->where('compcode','=',$user->compcode)
                ->where('deptcode','=',$user->dept)
                ->first();
            $unit_user = $unit_user_->sector;
        }
        $title="Warehouse";
        $dept_desc = $unit_user_->description;
        $shortcut=true;

        return view('init.container',compact('menu','units','unit_user','title','dept_desc','shortcut'));
    }

    public function mobile(){
        $user = Auth::user();
        $menu = $this->create_mobile_menu();
        $units = DB::table('sysdb.sector')
                ->where('compcode','=',$user->compcode)
                ->get();
        $unit_user = '';
        $title="Primary Care";
        if($user->dept != ''){
            $unit_user_ = DB::table('sysdb.department')
                ->where('compcode','=',$user->compcode)
                ->where('deptcode','=',$user->dept)
                ->first();
            $unit_user = $unit_user_->sector;
        }
        $dept_desc = $unit_user_->description;
        return view('init.container_mobile',compact('menu','units','unit_user','title','dept_desc'));
    }


    public function create_main_menu(){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;

        $query = DB::table('sysdb.programtab as a')
                    ->join('sysdb.groupacc as b',function($join) {
                        $join->on('a.programmenu', '=', 'b.programmenu')
                        ->on('a.lineno', '=', 'b.lineno');
                    })
                    ->where('b.groupid','=',$groupid)
                    ->where('b.compcode','=',$company)
                    ->where('b.programmenu','=','main')
                    ->orderBy('b.lineno', 'asc');

        foreach ($query->get() as $key=>$value){
            $this->create_sub_menu($value,$this->_x,'main');
        }

        return $this->_menu_str;
    }

    public function create_ptcare_menu(){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;

        // $menu="<li><a style='padding-left:9px;' title='Patient List' class='clickable' programid='pat_list' targetURL='pat_mast?epistycode=OP&curpat=false&PatClass=HIS' ><span class='fa plus-minus left-floated' style='float: left;padding: 2px 10px;'></span>Patient List</a></li>";

        $menu="<li><a style='padding-left:9px;' title='Patient List' class='clickable' programid='pat_list' targetURL='pat_mast?epistycode=OP&curpat=false&PatClass=HIS' >Patient List</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Dashboard' class='clickable' programid='dashboard' targetURL='ptcare_dashboard' >Dashboard</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Document Upload' class='clickable' programid='docupload' targetURL='ptcare_emergency' >Document Upload</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Case Note' class='clickable' programid='casenote' targetURL='ptcare_doctornote' >Case Note</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Episode Statistics' class='clickable' programid='estats' targetURL='ptcare_eis' >Episode Statistics</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Revenue Statistics' class='clickable' programid='rstats' targetURL='ptcare_reveis' >Revenue Statistics</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Prescription' class='clickable' programid='prescription' targetURL='ptcare_prescription' >Prescription</a></li>";

        return $menu;
    }

    public function create_dialysis_menu(){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;

        // $menu="<li><a style='padding-left:9px;' title='Patient List' class='clickable' programid='pat_list' targetURL='pat_mast?epistycode=OP&curpat=false&PatClass=HIS' ><span class='fa plus-minus left-floated' style='float: left;padding: 2px 10px;'></span>Patient List</a></li>";

        $menu="<li><a style='padding-left:9px;' title='Patient List' class='clickable' programid='pat_list' targetURL='pat_mast?epistycode=OP&curpat=false&PatClass=HIS' >Patient List</a></li>";

        // $menu.="<li><a style='padding-left:9px;' title='Document Upload' class='clickable' programid='docupload' targetURL='ptcare_emergency' >Document Upload</a></li>";

        // $menu.="<li><a style='padding-left:9px;' title='Case Note' class='clickable' programid='casenote' targetURL='dialysis_doctornote' >Case Note</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Dialysis' class='clickable' programid='dialysis' targetURL='dialysis_dialysis' >Dialysis</a></li>";
        $menu.="<li><a style='padding-left:9px;' title='Enquiry' class='clickable' programid='enquiry_order' targetURL='dialysis_enquiry_order' >Order Enquiry</a></li>";
        $menu.="<li><a style='padding-left:9px;' title='Enquiry' class='clickable' programid='enquiry' targetURL='dialysis_enquiry' >Enquiry</a></li>";

        return $menu;
    }

    public function create_warehouse_menu(){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;

        $menu="<li><a style='padding-left:9px;' title='Purchase Request' class='clickable' programid='PurReq_dataentry' targetURL='purchaseRequest?scope=ALL' >Purchase Req.</a></li>";
        $menu.="<li><a style='padding-left:9px;' title='Dialysis' class='clickable' programid='purOrd_prepared' targetURL='purchaseOrder?scope=ALL' >Purchase Order</a></li>";
        $menu.="<li><a style='padding-left:9px' title='Delivery Order' class='clickable' programid='DeliveryOrd_dataentr' targeturl='deliveryOrder?scope=ALL'>Delivery Order</a></li>";
        $menu.="<li><a style='padding-left:9px' title='Prepared' class='clickable' programid='invap_dataentry' targeturl='invoiceAP?source=AP&amp;trantype=IN&amp;scope=ALL'>Invoice</a></li>";
        $menu.="<li><a style='padding-left:9px' title='Sales Order' class='clickable' programid='SalesOrder_scope_ALL' targeturl='./SalesOrder?scope=ALL'>Sales Order</a></li>";
        $menu.="<li><a style='padding-left:9px' title='Receipt' class='clickable' programid='ARreceipt' targeturl='./receipt'>Receipt</a></li>";
        $menu.="<li><a style='padding-left:9px' title='AR Enquiry' class='clickable' programid='arenquiry' targeturl='./arenquiry'>AR Enquiry</a></li>";
        $menu.="<li><a style='padding-left:9px' title='Till Enquiry' class='clickable' programid='tillenquiry' targeturl='./tillenquiry'>Till Enquiry</a></li>";
        $menu.="<li><a style='padding-left:9px' title='Close Till' class='clickable' programid='till_close' targeturl='./till_close'>Close Till</a></li>";
        $menu.="<li><a style='padding-left:9px' title='Charge Master' class='clickable' programid='chgmaster' targeturl='./chargemaster'>Charge Master</a></li>";
        $menu.="<li style='background:lightgray'><a style='padding-left:9px' title='Inv Transaction' class=''><b>Inventory Transaction</b></a></li>";
        $menu.="<li><a style='padding-left:21px' title='Inventory Transaction' class='clickable' programid='invtran' targeturl='./inventoryTransaction?scope=ALL'>Inventory Transaction</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Transafer(TUI/TUO)' class='clickable' programid='transfer_TUI_TUO' targeturl='./inventoryTransaction?scope=ALL&ttype=TUO'>Transafer(TUI/TUO)</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Good Return In' class='clickable' programid='goodReturnIn' targeturl='./inventoryTransaction?scope=ALL&ttype=GRI'>Good Return In</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Adjusment(AI/AO)' class='clickable' programid='adjustmenAIAO' targeturl='./inventoryTransaction?scope=ALL&ttype=AI'>Adjusment(AI/AO)</a></li>";
        $menu.="<li style='background:lightgray'><a style='padding-left:9px' title='Product' class=''><b>Product</b></a></li>";
        $menu.="<li><a style='padding-left:21px' title='Pharmacy' class='clickable' programid='stockPharmacy' targeturl='product?groupcode=Stock&amp;&amp;Class=Pharmacy'>Pharmacy</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Pharmacy' class='clickable' programid='stockNon-Pharmacy' targeturl='product?groupcode=Stock&&Class=Non-Pharmacy'>Non-Pharmacy</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Pharmacy' class='clickable' programid='stockConsignment' targeturl='product?groupcode=Consignment&&Class=Consignment'>Consignment</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Other' class='clickable' programid='productFin' targeturl='product?groupcode=Others&&Class=Others'>Others</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Asset' class='clickable' programid='asset' targeturl='./product?groupcode=Asset&&Class=Asset'>Asset</a></li>";
        $menu.="<li style='background:lightgray'><a style='padding-left:9px' title='Enquiry' class=''><b>Enquiry</b></a></li>";
        $menu.="<li><a style='padding-left:21px' title='Asset' class='clickable' programid='ItemPhar' targeturl='./itemEnquiry?Class=Pharmacy'>Pharmacy</a></li>";
        $menu.="<li><a style='padding-left:21px' title='Asset' class='clickable' programid='ItemNonPhar' targeturl='./itemEnquiry?Class=Non-Pharmacy'>Non-Pharmacy</a></li>";
        //GRI
        //IV
        //stock freeze
        //stock count

        return $menu;
    }

    public function create_mobile_menu(){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;

        $menu="<li><a style='padding-left:9px;' title='Alert' class='clickable' programid='alert' targetURL='' >Back</a></li>";

        $menu.="<li><a style='padding-left:9px;' title='Log Out' class='clickable' programid='logout' targetURL='' href='./logout' >Log Out</a></li>";

        return $menu;
    }

    public function create_sub_menu($rowX,$x,$class){
        $user = Auth::user();
        $groupid = $user->groupid;
        $company = $user->compcode;
        $this->_x = $this->_x+1;
        if($rowX->programtype=='M')
            {   
                
                if($class!='main')
                {
                    $this->_menu_str .= "
                    <li>
                        <a href='#' aria-expanded='false' style='padding-left:".$this->tab($this->_x)."'><span class='lilabel'>" .$rowX->programname."</span><span class='fa plus-minus'></span></a>
                        <ul aria-expanded='false'>";
                }
                else
                {
                    if($rowX->condition2=='img'){
                        $this->_menu_str .= "
                        <li>
                            <a href='#' aria-expanded='false' class='main' style='padding-left:".$this->tab($this->_x)."'><img src='./img/".$rowX->condition3."' class='iconmetis'></img><span class='lilabel'>". $rowX->programname ."</span><span class='glyphicon arrow'></span></a>
                            <ul aria-expanded='false'>";
                    }else{

                        $this->_menu_str .= "
                        <li>
                            <a href='#' aria-expanded='false' class='main' style='padding-left:".$this->tab($this->_x)."'><span class='fa " .$rowX->condition3 ." fa-2x' style='padding-right:5px'></span><span class='lilabel'>". $rowX->programname ."</span><span class='glyphicon arrow'></span></a>
                            <ul aria-expanded='false'>";
                    }
                }

                $query = DB::table('sysdb.programtab as a')
                    ->join('sysdb.groupacc as b',function($join) {
                        $join->on('a.programmenu', '=', 'b.programmenu')
                        ->on('a.lineno', '=', 'b.lineno');
                    })
                    ->where('b.groupid','=',$groupid)
                    ->where('b.compcode','=',$company)
                    ->where('b.programmenu','=',$rowX->programid)
                    ->orderBy('b.lineno', 'asc');

                foreach ($query->get() as $key=>$value){
                    $this->_class='notmain';
                    $this->create_sub_menu($value,$this->_x,$this->_class);
                }
                
                $this->_menu_str .= "</ul></li>";
                
            }
            else
            {   

                $url = $rowX->url;
                if (str_starts_with($rowX->url, '/')) {
                    $url = ltrim($rowX->url, '/');
                }

                if($class == 'main'){
                    $this->_menu_str .= "<li><a style='padding-left:".$this->tab($this->_x)."' title='".$rowX->programname."' class='clickable' programid='".$rowX->programid."' targetURL='".$url."' newtab='true'><span class='fa " .$rowX->condition3 ." fa-2x' style='padding-right:5px'></span><span class='lilabel'>".$rowX->programname."</span></a></li>";
                }else{
                    $this->_menu_str .= "<li><a style='padding-left:".$this->tab($this->_x)."' title='".$rowX->programname."' class='clickable' programid='".$rowX->programid."' targetURL='".$url."'><span class='lilabel'>".$rowX->programname."</span></a></li>";    
                }
            }

            $this->_x = $this->_x-1;
    }

    public function tab($loop){
        return (30 + (10 * $loop)) . 'px';
    }

    public function changeSessionUnit(Request $request){
        $request->session()->put('unit', $request->unit);
    }

    public function util(Request $request)
    {   
        switch($request->action){
            case 'loop_pv_pvno':
                return $this->loop_pv_pvno($request);
            default:
                return 'error happen..';
        }
    }

    public function loop_pv_pvno(Request $request){

        DB::beginTransaction();

        try {

            $pvs = DB::table('finance.apacthdr')
                    ->where('compcode','9A')
                    ->where('source','CM')
                    ->where('trantype','FT')
                    ->whereNull('pvno')
                    ->get();

            foreach ($pvs as $pv) {
                $pvno = $this->sysparamgetadd();

                DB::table('finance.apacthdr')
                    ->where('idno',$pv->idno)
                    ->where('compcode','9A')
                    ->where('source','CM')
                    ->where('trantype','FT')
                    ->update([
                        'pvno' => $pvno
                    ]);

                dump('edit apacthdr idno: '.$pv->idno.' pvno: '.$pvno);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return response($e->getMessage(), 500);
        }
    }

    public function sysparamgetadd(){

        //1. get pvalue 1
        $pvalue1 = DB::table('sysdb.sysparam')->select('pvalue1')
                    ->where('compcode','9A')
                    ->where('source','HIS')
                    ->where('trantype','PV')->first();
        
        //2. add 1 into the value
        $pvalue1 = intval($pvalue1->pvalue1) + 1;

        //3. update the value
        DB::table('sysdb.sysparam')
            ->where('compcode','9A')
            ->where('source','HIS')
            ->where('trantype','PV')
            ->update(array('pvalue1' => $pvalue1));

        //4. return pvalue1
        return $pvalue1;
    }




}
