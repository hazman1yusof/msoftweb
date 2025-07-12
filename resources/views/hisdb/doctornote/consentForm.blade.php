 <form class='form-horizontal' style='width: 99%;' id='formConsentForm'>
    <div class='col-md-12'>
        <div class="panel panel-default">
            <div class="panel-heading text-center" style="position: sticky; top: 0px; z-index: 3; height: 40px;">
                <div class="btn-group btn-group-sm pull-right" role="group" aria-label="..." 
                    id="btn_grp_edit_consentForm" 
                    style="position: absolute; 
                            padding: 0 0 0 0; 
                            right: 40px; 
                            top: 5px;">
                    <button type="button" class="btn btn-default" id="new_consentForm">
                        <span class="fa fa-plus-square-o"></span> New 
                    </button>
                    <button type="button" class="btn btn-default" id="edit_consentForm">
                        <span class="fa fa-edit fa-lg"></span> Edit 
                    </button>
                    <button type="button" class="btn btn-default" data-oper='add' id="save_consentForm">
                        <span class="fa fa-save fa-lg"></span> Save 
                    </button>
                    <button type="button" class="btn btn-default" id="cancel_consentForm">
                        <span class="fa fa-ban fa-lg" aria-hidden="true"> </span> Cancel 
                    </button>
                    <button type="button" class="btn btn-default" id="consentForm_chart">
                        <span class="fa fa-print fa-lg"></span> Print 
                    </button>
                </div>
            </div>
                        
            <div class="panel-body">
                <div class='col-md-12'>
                    <div class="panel panel-info">
                        <div class="panel-body">
                           <center><h5>KEIZINAN MENJALANI PROSEDUR</h5></center>
                            <p>Saya <input type="text" name="guardianName" class="form-control input-sm" style="text-transform: none;"></p>
                            <p>beralamat <textarea id="address" name="address" rows="4" class="form-control input-sm"></textarea></p>
                            <p>dengan ini memberi keizinan <b>untuk menjalani prosedur</b> <input type="text" name="procedureName" class="form-control input-sm" style="text-transform: none;"></p>
                            <p><b>menyerahkan</b> 
                                <label class="radio-inline" style="padding-left: 30px;padding-bottom:10px;">
                                    <input type="radio" name="guardianType" value="anak"><b>anak</b>
                                </label>
                                <label class="radio-inline" style="padding-bottom:10px;">
                                    <input type="radio" name="guardianType" value="anak jagaan"><b>anak jagaan</b> &nbsp;
                                </label>
                            <b>saya</b> <input type="text" name="patientName" id="patientName" class="form-control input-sm" style="text-transform: none;"></p>
                            <p>Untuk menjalani prosedur radiologi <input type="text" name="procedureRadName" class="form-control input-sm" style="text-transform: none;"></p>
                            <p>Yang keadaan dan tujuan telah diterangkan kepada saya oleh Dr. <input type="text" name="doctorName" class="form-control input-sm" style="text-transform: none;"></p>

                            <div class='col-md-6'>
                                <div class="form-group">
                                <p>Tarikh: <input id="dateConsentGuardian" name="dateConsentGuardian" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" style="width: 200px !important;"></p>

                                </div>
                            </div>
                            
                            <div class='col-md-6'>
                                <p>Ditandatangani: <input type="text" name="guardianSign" class="form-control input-sm" style="text-transform: none;"></p>
                                <p>
                                    <label class="radio-inline" style="padding-left: 30px;padding-bottom:10px;">
                                        <input type="radio" name="guardianSignType" value="Pesakit"><b>Pesakit</b>
                                    </label>
                                    <label class="radio-inline" style="padding-bottom:10px;">
                                        <input type="radio" name="guardianSignType" value="Ibu Bapa"><b>IbuBapa</b> &nbsp;
                                    </label>
                                    <label class="radio-inline" style="padding-bottom:10px;">
                                        <input type="radio" name="guardianSignType" value="Penjaga"><b>Penjaga</b> &nbsp;
                                    </label>
                                </p>
                                <p>Tali persaudaraan: <input type="text" name="relationship" class="form-control input-sm" style="text-transform: none;"></p>
                                <p>No. Kad Pengenalan: <input type="text" name="guardianICNum" class="form-control input-sm"></p>
                            </div>

                            <div class='col-md-12'>
                                <p><b>Peringatan:</b> Jika seseorang itu memberi keizinan sebagai penjaga, hendaklah tali persaudaraan itu dijelaskan di bawah tandatangannya.</p>
                                <p>Saya mengaku bahawa saya telah menerangkan keadaan dan tujuan pembedahan ini kepada 
                                    <label class="radio-inline" style="padding-left: 30px;padding-bottom:10px;">
                                        <input type="radio" name="guardianSignTypeDoc" value="Pesakit"><b>Pesakit</b>
                                    </label>
                                    <label class="radio-inline" style="padding-bottom:10px;">
                                        <input type="radio" name="guardianSignTypeDoc" value="Ibu Bapa"><b>IbuBapa</b> &nbsp;
                                    </label>
                                    <label class="radio-inline" style="padding-bottom:10px;">
                                        <input type="radio" name="guardianSignTypeDoc" value="Penjaga"><b>Penjaga</b> &nbsp;
                                    </label>
                                </p>
                            </div>

                            <div class='col-md-6'>
                                <p>Tarikh: <input id="dateConsentDoc" name="dateConsentDoc" type="date" class="form-control input-sm" value="{{Carbon\Carbon::now()->format('Y-m-d')}}" style="width: 200px !important;"</p>
                            </div>
                            <div class='col-md-6'>
                                <p>Ditandatangani: <input type="text" name="doctorSign" class="form-control input-sm" style="text-transform: none;"></p>
                                <p><center>(Pengamal Perubatan)</center></p>
                            </div>
                            <div class='col-md-12'>
                                <p>Sebarang pemotongan dan tambahan atau pindahan kepada Borang ini hendaklah dibuat sebelum penerangan itu diberi dan borang itu dikemukakan untuk ditandatangani.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</form>