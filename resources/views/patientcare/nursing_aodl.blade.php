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
            			<textarea id="br_breathingdesc" name="br_breathingdesc" type="text" rows="3"></textarea>
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
            			<textarea id="br_coughdesc" name="br_coughdesc" type="text" rows="3"></textarea>
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
            			<textarea id="br_breathingdesc" name="br_smokedesc" type="text" rows="3"></textarea>
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
            			<textarea id="ed_eatdrinkdesc" name="ed_eatdrinkdesc" type="text" rows="3"></textarea>
            		</div>
            	</div>
           	</div>
        </div>   	

		<div class="ui segments">
            <div class="ui secondary segment">ELIMINATION BOWEL</div>
            <div class="ui segment">
            	<div class="fields">
            		<div class="six wide field"><label>Have Notice Any Changes In Bowel Habits Lately?</label></div>
            		<div class="ten wide inline field">
            			<label class="normal_label"><input type="radio" name="eb_bowelhabit" value='1' > Yes</label>
            			<label class="normal_label"><input type="radio" name="eb_bowelhabit" value='0' > No</label>
            		</div>
            	</div>
            	<div class="fields">
            		<div class="six wide field"><label>Take Any Medication For Bowel Movement?</label></div>
            		<div class="ten wide inline field">
            			<label class="normal_label"><input type="radio" name="eb_bowelmove" value='1' > Yes</label>
            			<label class="normal_label"><input type="radio" name="eb_bowelmove" value='0' > No</label>
            		</div>
            	</div>
            	<div class="fields">
            		<div class="six wide field"><label>If Yes, Describe:</label></div>
            		<div class="ten wide inline field">
            			<textarea id="eb_bowelmovedesc" name="eb_bowelmovedesc" type="text" rows="3"></textarea>
            		</div>
            	</div>
           	</div>
        </div>   		
	</div>

	<div class="nine wide column">
		<div class="ui segments">
            <div class="ui secondary segment">SLEEPING</div>
            <div class="ui segment">
            	<div class="fields">
            		<div class="six wide field"><label>Required Medication To Sleep?</label></div>
            		<div class="ten wide inline field">
            			<label class="normal_label"><input type="radio" name="sl_sleep" value='1' > Yes</label>
            			<label class="normal_label"><input type="radio" name="sl_sleep" value='0' > No</label>
            		</div>
            	</div>
           	</div>
        </div>

        <div class="ui equal width grid">
        	<div class="column">
				<div class="ui segments">
		            <div class="ui secondary segment">MOBILITY</div>
		            <div class="ui segment">
		            	<div class="field">
                            <label>
                                <input type="checkbox" id="mobilityambulan" name="mobilityambulan" value="1">
                                 Ambulant 
                            </label>
                        </div>
                        <div class="field">
                            <label>
                                <input type="checkbox" id="mobilityassistaid" name="mobilityassistaid" value="1">
                                 Assist With AIDS 
                            </label>
                        </div>
                        <div class="field">
                            <label>
                                <input type="checkbox" id="mobilitybedridden" name="mobilitybedridden" value="1">
                                 Bedridden 
                            </label>
                        </div>
		           	</div>
		        </div>
		    </div>

        	<div class="column">
				<div class="ui segments">
		            <div class="ui secondary segment">PERSONAL HYGIENE</div>
		            <div class="ui segment">
		            	<div class="field">
                            <label>
                                <input type="checkbox" id="phygiene_self" name="phygiene_self" value="1">
                                 Self 
                            </label>
                        </div>
                        <div class="field">
                            <label>
                                <input type="checkbox" id="phygiene_needassist" name="phygiene_needassist" value="1">
                                 Need Assistant 
                            </label>
                        </div>
                        <div class="field">
                            <label>
                                <input type="checkbox" id="phygiene_dependant" name="phygiene_dependant" value="1">
                                 Totally Dependant 
                            </label>
                        </div>
		           	</div>
		        </div>
		    </div>

        	<div class="column">
				<div class="ui segments">
		            <div class="ui secondary segment">SAFE ENVIRONMENT</div>
		            <div class="ui segment">
		            	<div class="field">
                            <label>
                                <input type="checkbox" id="safeenv_siderail" name="safeenv_siderail" value="1">
                                 Siderail 
                            </label>
                        </div>
                        <div class="field">
                            <label>
                                <input type="checkbox" id="safeenv_restraint" name="safeenv_restraint" value="1">
                                 Restraint
                            </label>
                        </div>
		           	</div>
		        </div>
		    </div>
        </div>

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

        <div class="ui segments">
            <div class="ui secondary segment">BLADDER</div>
            <div class="ui segment">
            	<div class="fields">
	        		<div class="six wide field"><label>Have Any Problem Passing Urine?</label></div>
	        		<div class="ten wide inline field">
	        			<label class="normal_label"><input type="radio" name="bl_urine" value='1' > Yes</label>
	        			<label class="normal_label"><input type="radio" name="bl_urine" value='0' > No</label>
	        		</div>
	        	</div>

            	<div class="fields">
            		<div class="six wide field"><label>If Yes, Describe:</label></div>
            		<div class="ten wide inline field">
            			<textarea id="bl_urinedesc" name="bl_urinedesc" type="text" rows="3"></textarea>
            		</div>
            	</div>

            	<div class="fields">
            		<div class="six wide field"><label>How Often Get Up At Night To Pass Urine?</label></div>
            		<div class="ten wide inline field">
            			<textarea id="bl_urinefreq" name="bl_urinefreq" type="text" rows="3"></textarea>
            		</div>
            	</div>
           	</div>
        </div>

	</div>
</div>