<div class="ui segments" style="position: relative;">
    <div class="ui secondary segment bluecloudsegment" style="height: 50px;">
        <div class="ui small blue icon buttons" id="btn_grp_edit_oswestryQuest" style="position: absolute;
            padding: 0 0 0 0;
            right: 40px;
            top: 9px;
            z-index: 2;">
            <button class="ui button" id="new_oswestryQuest"><span class="fa fa-plus-square-o"></span>New</button>
            <button class="ui button" id="edit_oswestryQuest"><span class="fa fa-edit fa-lg"></span>Edit</button>
            <button class="ui button" id="save_oswestryQuest"><span class="fa fa-save fa-lg"></span>Save</button>
            <button class="ui button" id="cancel_oswestryQuest"><span class="fa fa-ban fa-lg"></span>Cancel</button>
            <button class="ui button" id="oswestryQuest_chart"><span class="fa fa-print fa-lg"></span>Print</button>
        </div>
    </div>
    <div class="ui segment">
        <div class="ui grid">
            <form id="formOswestryQuest" class="floated ui form sixteen wide column">
                <input id="idno_oswestryQuest" name="idno_oswestryQuest" type="hidden">
                <div class="ui grid">
                    <div class='four wide column'>
                        <div class="ui segments">
                            <div class="ui segment">
                                <div class="ui grid">
                                    <table id="tbl_oswestryQuest_date" class="ui celled table" style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th class="scope">idno</th>
                                                <th class="scope">mrn</th>
                                                <th class="scope">episno</th>
                                                <th class="scope">Date</th>
                                                <th class="scope">dt</th>
                                                <th class="scope">Entered By</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class='twelve wide column'>
                        <div class="inline fields">
                            <label>Date</label>
                            <div class="field">
                                <input id="oswestryQuest_entereddate" name="entereddate" type="date">
                            </div>
                        </div>
                        
                        <div class="ui grid">
                            <div class='eight wide column' style="padding-right: 0px;">
                                <table class="ui striped table">
                                    <tbody>
                                        <tr height="310px">
                                            <th scope="row">1</th>
                                            <td>
                                                PAIN INTENSITY<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="painIntensity" value="0" id="painIntensity0" class="calc_oswestryQuest">
                                                            <label for="painIntensity0">I can tolerate the pain I have without having to use pain killers.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="painIntensity" value="1" id="painIntensity1" class="calc_oswestryQuest">
                                                            <label for="painIntensity1">The pain is bad but I manage without taking pain killers.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="painIntensity" value="2" id="painIntensity2" class="calc_oswestryQuest">
                                                            <label for="painIntensity2">Pain killers give complete relief from pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="painIntensity" value="3" id="painIntensity3" class="calc_oswestryQuest">
                                                            <label for="painIntensity3">Pain killers give moderate relief from pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="painIntensity" value="4" id="painIntensity4" class="calc_oswestryQuest">
                                                            <label for="painIntensity4">Pain killers give very little relief from pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="painIntensity" value="5" id="painIntensity5" class="calc_oswestryQuest">
                                                            <label for="painIntensity5">Pain killers have no effect on the pain and I do not use them.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="310px">
                                            <th scope="row">2</th>
                                            <td>
                                                PERSONAL CARE (e.g. Washing, Dressing)<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="personalCare" value="0" id="personalCare0" class="calc_oswestryQuest">
                                                            <label for="personalCare0">I can look after myself normally without causing extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="personalCare" value="1" id="personalCare1" class="calc_oswestryQuest">
                                                            <label for="personalCare1">I can look after myself normally but it causes extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="personalCare" value="2" id="personalCare2" class="calc_oswestryQuest">
                                                            <label for="personalCare2">It is painful to look after myself and I am slow and careful.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="personalCare" value="3" id="personalCare3" class="calc_oswestryQuest">
                                                            <label for="personalCare3">I need some help but manage most of my personal care.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="personalCare" value="4" id="personalCare4" class="calc_oswestryQuest">
                                                            <label for="personalCare4">I need help every day in most aspects of self care.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="personalCare" value="5" id="personalCare5" class="calc_oswestryQuest">
                                                            <label for="personalCare5">I dont get dressed, I was with difficulty and stay in bed.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="330px">
                                            <th scope="row">3</th>
                                            <td>
                                                LIFTING<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="lifting" value="0" id="lifting0" class="calc_oswestryQuest">
                                                            <label for="lifting0">I can lift heavy weights without extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="lifting" value="1" id="lifting1" class="calc_oswestryQuest">
                                                            <label for="lifting1">I can lift heavy weights but it gives extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="lifting" value="2" id="lifting2" class="calc_oswestryQuest">
                                                            <label for="lifting2">Pain prevents me from lifting heavy weights off the floor, but I can manage if they are conveniently positioned, i.e. on a table.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="lifting" value="3" id="lifting3" class="calc_oswestryQuest">
                                                            <label for="lifting3">Pain prevents me from lifting heavy weights, but I can manage light to medium weights if they are conveniently positioned.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="lifting" value="4" id="lifting4" class="calc_oswestryQuest">
                                                            <label for="lifting4">I can lift very light weights.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="lifting" value="5" id="lifting5" class="calc_oswestryQuest">
                                                            <label for="lifting5">I cannot lift or carry anything at all.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="300px">
                                            <th scope="row">4</th>
                                            <td>
                                                WALKING<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="walking" value="0" id="walking0" class="calc_oswestryQuest">
                                                            <label for="walking0">Pain does not prevent me walking any distance.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="walking" value="1" id="walking1" class="calc_oswestryQuest">
                                                            <label for="walking1">Pain prevents me walking more than one mile.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="walking" value="2" id="walking2" class="calc_oswestryQuest">
                                                            <label for="walking2">Pain prevents me walking more than 1/2 mile.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="walking" value="3" id="walking3" class="calc_oswestryQuest">
                                                            <label for="walking3">Pain prevents me walking more than 1/4 mile.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="walking" value="4" id="walking4" class="calc_oswestryQuest">
                                                            <label for="walking4">I can only walk using a stick or crutches.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="walking" value="5" id="walking5" class="calc_oswestryQuest">
                                                            <label for="walking5">I am in bed most of the time and have to crawl to the toilet.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="340px">
                                            <th scope="row">5</th>
                                            <td>
                                                SITTING<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitting" value="0" id="sitting0" class="calc_oswestryQuest">
                                                            <label for="sitting0">I can sit in any chair as long as I like.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitting" value="1" id="sitting1" class="calc_oswestryQuest">
                                                            <label for="sitting1">I can only sit in my favorite chair as long as I like.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitting" value="2" id="sitting2" class="calc_oswestryQuest">
                                                            <label for="sitting2">Pain prevents me from sitting more than one hour.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitting" value="3" id="sitting3" class="calc_oswestryQuest">
                                                            <label for="sitting3">Pain prevents me from sitting more than 1/2 hour.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitting" value="4" id="sitting4" class="calc_oswestryQuest">
                                                            <label for="sitting4">Pain prevents me from sitting more than 10 minutes.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sitting" value="5" id="sitting5" class="calc_oswestryQuest">
                                                            <label for="sitting5">Pain prevents me from sitting at all.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class='eight wide column' style="padding-left: 0px;">
                                <table class="ui striped table">
                                    <tbody>
                                        <tr height="310px">
                                            <th scope="row">6</th>
                                            <td>
                                                STANDING<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standing" value="0" id="standing0" class="calc_oswestryQuest">
                                                            <label for="standing0">I can stand as long as I want without extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standing" value="1" id="standing1" class="calc_oswestryQuest">
                                                            <label for="standing1">I can stand as long as I want but it gives me extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standing" value="2" id="standing2" class="calc_oswestryQuest">
                                                            <label for="standing2">Pain prevents me from standing for more than one hour.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standing" value="3" id="standing3" class="calc_oswestryQuest">
                                                            <label for="standing3">Pain prevents me from standing for more than 30 minutes.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standing" value="4" id="standing4" class="calc_oswestryQuest">
                                                            <label for="standing4">Pain prevents me from standing for more than 10 minutes.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="standing" value="5" id="standing5" class="calc_oswestryQuest">
                                                            <label for="standing5">Pain prevents me from standing at all.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="310px">
                                            <th scope="row">7</th>
                                            <td>
                                                SLEEPING<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sleeping" value="0" id="sleeping0" class="calc_oswestryQuest">
                                                            <label for="sleeping0">Pain does not prevent me from sleeping well.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sleeping" value="1" id="sleeping1" class="calc_oswestryQuest">
                                                            <label for="sleeping1">I can sleep well only by using medication.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sleeping" value="2" id="sleeping2" class="calc_oswestryQuest">
                                                            <label for="sleeping2">Even when I take medication, I have less than 6 hrs sleep.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sleeping" value="3" id="sleeping3" class="calc_oswestryQuest">
                                                            <label for="sleeping3">Even when I take medication, I have less than 4 hrs sleep.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sleeping" value="4" id="sleeping4" class="calc_oswestryQuest">
                                                            <label for="sleeping4">Even when I take medication, I have less than 2 hrs sleep.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="sleeping" value="5" id="sleeping5" class="calc_oswestryQuest">
                                                            <label for="sleeping5">Pain prevents me from sleeping at all.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="330px">
                                            <th scope="row">8</th>
                                            <td>
                                                SOCIAL LIFE<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="socialLife" value="0" id="socialLife0" class="calc_oswestryQuest">
                                                            <label for="socialLife0">My social life is normal and gives me no extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="socialLife" value="1" id="socialLife1" class="calc_oswestryQuest">
                                                            <label for="socialLife1">My social life is normal but increases the degree of pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="socialLife" value="2" id="socialLife2" class="calc_oswestryQuest">
                                                            <label for="socialLife2">Pain has no significant effect on my social life apart from limiting my more energetic interests, i.e. dancing, etc.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="socialLife" value="3" id="socialLife3" class="calc_oswestryQuest">
                                                            <label for="socialLife3">Pain has restricted my social life and I do not go out as often.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="socialLife" value="4" id="socialLife4" class="calc_oswestryQuest">
                                                            <label for="socialLife4">Pain has restricted my social life to my home.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="socialLife" value="5" id="socialLife5" class="calc_oswestryQuest">
                                                            <label for="socialLife5">I have no social life because of pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="300px">
                                            <th scope="row">9</th>
                                            <td>
                                                TRAVELLING<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="travelling" value="0" id="travelling0" class="calc_oswestryQuest">
                                                            <label for="travelling0">I can travel anywhere without extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="travelling" value="1" id="travelling1" class="calc_oswestryQuest">
                                                            <label for="travelling1">I can travel anywhere but it gives me extra pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="travelling" value="2" id="travelling2" class="calc_oswestryQuest">
                                                            <label for="travelling2">Pain is bad, but I manage journeys over 2 hours.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="travelling" value="3" id="travelling3" class="calc_oswestryQuest">
                                                            <label for="travelling3">Pain restricts me to journeys of less than 1 hour.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="travelling" value="4" id="travelling4" class="calc_oswestryQuest">
                                                            <label for="travelling4">Pain restricts me to short necessary journeys under 30 minutes.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="travelling" value="5" id="travelling5" class="calc_oswestryQuest">
                                                            <label for="travelling5">Pain prevents me from travelling except to the doctor or hospital.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr height="340px">
                                            <th scope="row">10</th>
                                            <td>
                                                EMPLOYMENT / HOMEMAKING<br>
                                                
                                                <div class="inline fields" style="padding-top: 5px;">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="employHomemaking" value="0" id="employHomemaking0" class="calc_oswestryQuest">
                                                            <label for="employHomemaking0">My normal homemaking / job activities do not cause pain.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="employHomemaking" value="1" id="employHomemaking1" class="calc_oswestryQuest">
                                                            <label for="employHomemaking1">My normal homemaking / job activities increase my pain, but I can still perform all that is required of me.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="employHomemaking" value="2" id="employHomemaking2" class="calc_oswestryQuest">
                                                            <label for="employHomemaking2">I can perform most of my homemaking / job duties, but pain prevents me from performing more physically stressful activities (e.g. lifting, vacuuming).</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="employHomemaking" value="3" id="employHomemaking3" class="calc_oswestryQuest">
                                                            <label for="employHomemaking3">Pain prevents me from doing anything but light duties.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="employHomemaking" value="4" id="employHomemaking4" class="calc_oswestryQuest">
                                                            <label for="employHomemaking4">Pain prevents me from doing even light duties.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="inline fields">
                                                    <div class="field">
                                                        <div class="ui radio checkbox">
                                                            <input type="radio" name="employHomemaking" value="5" id="employHomemaking5" class="calc_oswestryQuest">
                                                            <label for="employHomemaking5">Pain prevents me from performing any job or homemaking chores.</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <div class="inline fields" style="padding-top: 15px;">
                            <label>TOTAL SCORE</label>
                            <div class="field">
                                <input id="oswestryQuest_totalScore" name="totalScore" type="text" rdonly>
                            </div>
                            <span id="oswestryQuest_disabilityLevel" name="disabilityLevel"></span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>