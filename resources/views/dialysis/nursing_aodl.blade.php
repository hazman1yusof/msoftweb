<div class="ui grid">
	<div class="seven wide column">
		<div class="ui segments">
            <div class="ui secondary segment">BREATHING</div>
            <div class="ui segment">
            	<div class="fields">
            		<div class="six wide field"><label>Any Difficulties In Breathing?</label></div>
            		<div class="ten wide inline field">
            			<label class="normal_label"><input type="radio" name="br_breathing" value='1' > Yes</label>
            			<label class="normal_label"><input type="radio" name="br_breathing" value='0' > No</label>
            		</div>
            	</div>
            	<div class="fields">
            		<div class="six wide field"><label>If Yes, Describe:</label></div>
            		<div class="ten wide inline field">
            			<textarea id="br_breathingdesc" name="br_breathingdesc" type="text"></textarea>
            		</div>
            	</div>

            	<div class="fields">
            		<div class="six wide field"><label>Have Any Cough?</label></div>
            		<div class="ten wide inline field">
            			<label class="normal_label"><input type="radio" name="br_cough" value='1' > Yes</label>
            			<label class="normal_label"><input type="radio" name="br_cough" value='0' > No</label>
            		</div>
            	</div>
            	<div class="fields">
            		<div class="six wide field"><label>If Yes, Describe:</label></div>
            		<div class="ten wide inline field">
            			<textarea id="br_coughdesc" name="br_coughdesc" type="text"></textarea>
            		</div>
            	</div>

            	<div class="fields">
            		<div class="six wide field"><label>Does He/She Smoke?</label></div>
            		<div class="ten wide inline field">
            			<label class="normal_label"><input type="radio" name="br_smoke" value='1' > Yes</label>
            			<label class="normal_label"><input type="radio" name="br_smoke" value='0' > No</label>
            		</div>
            	</div>
            	<div class="fields">
            		<div class="six wide field"><label>If Yes, Describe:</label></div>
            		<div class="ten wide inline field">
            			<textarea id="br_breathingdesc" name="br_smokedesc" type="text"></textarea>
            		</div>
            	</div>
           	</div>
        </div>

		<div class="ui segments">
            <div class="ui secondary segment">EATING/DRINKING</div>
            <div class="ui segment">
            	<div class="fields">
            		<div class="six wide field"><label>Any Problem with Eating/Drinking?</label></div>
            		<div class="ten wide inline field">
            			<label class="normal_label"><input type="radio" name="ed_eatdrink" value='1' > Yes</label>
            			<label class="normal_label"><input type="radio" name="ed_eatdrink" value='0' > No</label>
            		</div>
            	</div>
            	<div class="fields">
            		<div class="six wide field"><label>If Yes, Describe:</label></div>
            		<div class="ten wide inline field">
            			<textarea id="ed_eatdrinkdesc" name="ed_eatdrinkdesc" type="text"></textarea>
            		</div>
            	</div>
           	</div>
        </div>   		
	</div>

	<div class="nine wide column">
		<div class="ui segments">
            <div class="ui secondary segment">COMMUNICATION</div>
            <div class="ui segment">
				<div class="ui equal width grid">
		        	<div class="column">
						<div class="ui segments">
				            <div class="ui secondary segment">SPEECH</div>
				            <div class="ui segment">
				            	<div class="field">
		                            <label>
		                                <input type="checkbox" id="cspeech_normal" name="cspeech_normal" value="1">
		                                 Normal
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="cspeech_slurred" name="cspeech_slurred" value="1">
		                                 Slurred
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="cspeech_impaired" name="cspeech_impaired" value="1">
		                                 Impaired
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="cspeech_mute" name="cspeech_mute" value="1">
		                                 Mute
		                            </label>
		                        </div>
				           	</div>
				        </div>
				    </div>

		        	<div class="column">
						<div class="ui segments">
				            <div class="ui secondary segment">VISION</div>
				            <div class="ui segment">
				            	<div class="field">
		                            <label>
		                                <input type="checkbox" id="cvision_normal" name="cvision_normal" value="1">
		                                 Normal
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="cvision_blurring" name="cvision_blurring" value="1">
		                                 Blurring
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="cvision_doublev" name="cvision_doublev" value="1">
		                                 Double Vision
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="cvision_blind" name="cvision_blind" value="1">
		                                 Blind
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="cvision_visualaids" name="cvision_visualaids" value="1">
		                                 Visual Aids
		                            </label>
		                        </div>
				           	</div>
				        </div>
				    </div>

		        	<div class="column">
						<div class="ui segments">
				            <div class="ui secondary segment">HEARING</div>
				            <div class="ui segment">
				            	<div class="field">
		                            <label>
		                                <input type="checkbox" id="chearing_normal" name="chearing_normal" value="1">
		                                 Normal
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="chearing_deaf" name="chearing_deaf" value="1">
		                                 Deaf
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="chearing_hardhear" name="chearing_hardhear" value="1">
		                                 Hard of Hearing
		                            </label>
		                        </div>
		                        <div class="field">
		                            <label>
		                                <input type="checkbox" id="chearing_hearaids" name="chearing_hearaids" value="1">
		                                 Hearing Aids
		                            </label>
		                        </div>
				           	</div>
				        </div>
				    </div>
		        </div>
           	</div>
        </div>

	</div>
</div>