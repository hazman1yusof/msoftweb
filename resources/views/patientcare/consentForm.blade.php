<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_consentForm" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_consentForm"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_consentForm"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_consentForm"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_consentForm"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="consentForm_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formConsentForm" class="floated ui form sixteen wide column">
                <div class='ui grid' style="padding: 15px 30px;">
                    <div class="sixteen wide column">
                        <div class="sixteen wide column">
                            <div class="field">
                                <label><center><h5>KEIZINAN MENJALANI PROSEDUR</h5></center></label>
                            </div>
                        </div>

                        <p>Saya <input type="text" name="guardianName" class="form-control input-sm" style="text-transform: none;"></p>
                        <p>beralamat <textarea id="address" name="address" rows="4" class="form-control input-sm"></textarea></p>
                        <p>dengan ini memberi keizinan <b>untuk menjalani prosedur</b> <input type="text" name="procedureName" class="form-control input-sm" style="text-transform: none;"></p>
                        <p><b>menyerahkan</b> 
                            <label class="radio-inline" style="padding-left: 30px;padding-bottom:5px;">
                                <input type="radio" name="guardianType" value="anak"><b>anak</b>
                            </label>
                            <label class="radio-inline" style="padding-bottom:5px;">
                                <input type="radio" name="guardianType" value="anak jagaan"><b>anak jagaan</b> &nbsp;
                            </label>
                        <b>saya</b> <input type="text" name="patientName" id="patientName" class="form-control input-sm" style="text-transform: none;"></p>
                        <p>Untuk menjalani prosedur radiologi <input type="text" name="procedureRadName" class="form-control input-sm" style="text-transform: none;"></p>
                        <p>Yang keadaan dan tujuan telah diterangkan kepada saya oleh Dr. <input type="text" name="doctorName" class="form-control input-sm" style="text-transform: none;"></p>

                        <table class="ui table" style="border-style:none;">
                            <tbody>
                                <tr>
                                    <td class="eight wide">
                                        <div class="inline field">
                                            <label>Tarikh: </label>
                                            <input type="date" id="dateConsentGuardian" name="dateConsentGuardian">
                                        </div>
                                    </td>
                                    <td class="eight wide">
                                        <div class="ui field">
                                            <label>
                                                Ditandatangani: <input type="text" name="guardianSign" class="form-control input-sm" style="text-transform: none;">
                                            </label>
                                        </div>
                                        <div class="inline field">
                                            <label class="radio-inline" style="padding-left: 30px;padding-bottom:5px;">
                                                <input type="radio" name="guardianSignType" value="Pesakit"><b>Pesakit</b>
                                            </label>
                                            <label class="radio-inline" style="padding-bottom:5px;">
                                                <input type="radio" name="guardianSignType" value="Ibu Bapa"><b>IbuBapa</b> &nbsp;
                                            </label>
                                            <label class="radio-inline" style="padding-bottom:5px;">
                                                <input type="radio" name="guardianSignType" value="Penjaga"><b>Penjaga</b> &nbsp;
                                            </label><br>
                                        </div>
                                        <div class="ui field">
                                            <label>
                                                Tali persaudaraan: <input type="text" name="relationship" class="form-control input-sm" style="text-transform: none;">
                                            </label><br>
                                            <label>
                                                No. Kad Pengenalan: <input type="text" name="guardianICNum" class="form-control input-sm">
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <p><b>Peringatan:</b> Jika seseorang itu memberi keizinan sebagai penjaga, hendaklah tali persaudaraan itu dijelaskan di bawah tandatangannya.</p>
                        <p>Saya mengaku bahawa saya telah menerangkan keadaan dan tujuan pembedahan ini kepada 
                            <label class="radio-inline" style="padding-left: 30px;padding-bottom:5px;">
                                <input type="radio" name="guardianSignTypeDoc" value="Pesakit"><b>Pesakit</b>
                            </label>
                            <label class="radio-inline" style="padding-bottom:5px;">
                                <input type="radio" name="guardianSignTypeDoc" value="Ibu Bapa"><b>IbuBapa</b> &nbsp;
                            </label>
                            <label class="radio-inline" style="padding-bottom:5px;">
                                <input type="radio" name="guardianSignTypeDoc" value="Penjaga"><b>Penjaga</b> &nbsp;
                            </label>
                        </p>

                        <table class="ui table" style="border-style:none;">
                            <tbody>
                                <tr>
                                    <td class="eight wide">
                                        <div class="inline field">
                                            <label>Tarikh: </label>
                                            <input type="date" id="dateConsentDoc" name="dateConsentDoc">
                                        </div>
                                    </td>
                                    <td class="eight wide">
                                        <div class="ui field">
                                            <label>
                                                Ditandatangani: <input type="text" name="doctorSign" class="form-control input-sm" style="text-transform: none;">
                                            </label>
                                            <label><center>(Pengamal Perubatan)</center></label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <p>Sebarang pemotongan dan tambahan atau pindahan kepada Borang ini hendaklah dibuat sebelum penerangan itu diberi dan borang itu dikemukakan untuk ditandatangani.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>