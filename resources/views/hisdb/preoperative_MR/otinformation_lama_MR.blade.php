<table class="ui very basic table" style="padding-top: 15px; padding-bottom: 15px;">
    <tbody>
        <tr>
            <td>
                <div class="form-inline"> Temperature <span style="margin-left: 48px;"> :  &nbsp;
                    <div class="form-group">
                        <input type="text" class="form-control" id="preop_info_temperature" name="info_temperature" size="4">
                    </div> &nbsp; °C &nbsp; &nbsp; <span style="margin-left: 60px;">
                    Humidity &nbsp; &nbsp; &nbsp; :  &nbsp;
                    <div class="form-group">
                        <input type="text" class="form-control" id="preop_info_humidity" name="info_humidity" size="4">
                    </div> &nbsp; <span class="glyphicon glyphicon-tint"> &nbsp; &nbsp;
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-inline"> OT Room <span style="margin-left: 69px;"> :  &nbsp;
                    <div class="form-group">
                        <select name="info_otroom" id="preop_info_otroom" class="form-control input-sm">
                            <option value=""></option>
                            @foreach($otroom as $obj)
                                <option value="{{$obj->resourcecode}}">{{$obj->description}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-inline"> Anaesthetist(s) <span style="margin-left: 34px;"> :  &nbsp;
                    <div class="input-group">
                        <input type="text" class="form-control" name="desc_anaesthetist" id="desc_anaesthetist" size="40" tabindex=4>
                        <input type="hidden" id="info_anaesthetist" name="info_anaesthetist"/>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="btn_anaesthetist" data-toggle="modal" onclick_xguna="pop_item_select('anaesthetist');"><span class="fa fa-ellipsis-h"></span></button>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-inline"> Surgeon <span style="margin-left: 76px;"> :  &nbsp;
                    <div class="input-group">
                        <input type="text" class="form-control" name="desc_surgeon" id="desc_surgeon" size="40" tabindex=4>
                        <input type="hidden" id="info_surgeon" name="info_surgeon"/>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="btn_surgeon" data-toggle="modal" onclick_xguna="pop_item_select('surgeon');"><span class="fa fa-ellipsis-h"></span></button>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
        <tr>
            <td>
                <div class="form-inline"> Asst. Surgeon <span style="margin-left: 44px;"> :  &nbsp;
                    <div class="input-group">
                        <input type="text" class="form-control" name="desc_asstsurgeon" id="desc_asstsurgeon" size="40" tabindex=4>
                        <input type="hidden" id="info_asstsurgeon" name="info_asstsurgeon"/>
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="btn_asstsurgeon" data-toggle="modal" onclick_xguna="pop_item_select('asstsurgeon');"><span class="fa fa-ellipsis-h"></span></button>
                        </span>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>