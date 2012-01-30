{if $logged_in}

    {include file="$template/header.tpl"}
    {include file="$template/navigation.tpl"}

    {* Location Links *}
    <span class="location">&nbsp;&nbsp;<a href="main.php">{$lang.admin_home}</a> / <a href="clients.php">{$lang.clients_nav_client_accounts}</a> / {$lang.addclient_nav_add_client_account}</span>
    
    <br />
    
    {* Across-Page bar *}
    {include file="$template/bar.tpl"}
      
    <br /><br />
    
    
    {literal}
    <script language="JavaScript">
    document.addclient.username.style.display = 'none';
    </script>
    {/literal}
    
    <form action="addclient.php" method="post" name="addclient">
    <table border="0" cellpadding="1" cellspacing="0" width="400" align="center" class="tablez">
      <tr class="table_title" height="20">
        <td align="center" colspan="2">{$lang.addclient_title}</td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><img src="templates/{$template}/img/icons/clients_add-64px.png" border="0" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.username}:&nbsp;</td>
        <td><input type="text" name="username" id="username" class="textbox_important" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.password}:&nbsp;</td>
        <td><input type="password" name="password" class="textbox_important" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.password_confirm}:&nbsp;</td>
        <td><input type="password" name="password_confirm" class="textbox_important" style="width:170px"></td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.first_name}:&nbsp;</td>
        <td><input type="text" name="first_name" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.middle_name}:&nbsp;</td>
        <td><input type="text" name="middle_name" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.last_name}:&nbsp;</td>
        <td><input type="text" name="last_name" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.company}:&nbsp;</td>
        <td><input type="text" name="company" class="textbox_normal" style="width:170px"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="right" class="description">{$lang.email}:&nbsp;</td>
        <td><input type="text" name="email_address" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.phone}:&nbsp;</td>
        <td><input type="text" name="phone_number" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.address}:&nbsp;</td>
        <td><input type="text" name="street_address1" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.address2}:&nbsp;</td>
        <td><input type="text" name="street_address2" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.city}:&nbsp;</td>
        <td><input type="text" name="city" class="textbox_normal" style="width:170px"></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.state}:&nbsp;</td>
        <td>
        <select name="state" id="state" style="width:170px">
				<option value='' selected>{$lang.addclient_signup_state_choose}</option>
				<option value='AK'>AK</option>
				<option value='AL'>AL</option>
				<option value='AR'>AR</option>
				<option value='AZ'>AZ</option>

				<option value='CA'>CA</option>
				<option value='CO'>CO</option>
				<option value='CT'>CT</option>
				<option value='DC'>DC</option>
				<option value='DE'>DE</option>
				<option value='FL'>FL</option>

				<option value='GA'>GA</option>
				<option value='HI'>HI</option>
				<option value='IA'>IA</option>
				<option value='ID'>ID</option>
				<option value='IL'>IL</option>
				<option value='IN'>IN</option>

				<option value='KS'>KS</option>
				<option value='KY'>KY</option>
				<option value='LA'>LA</option>
				<option value='MA'>MA</option>
				<option value='MD'>MD</option>
				<option value='ME'>ME</option>

				<option value='MI'>MI</option>
				<option value='MN'>MN</option>
				<option value='MO'>MO</option>
				<option value='MS'>MS</option>
				<option value='MT'>MT</option>
				<option value='NC'>NC</option>

				<option value='ND'>ND</option>
				<option value='NE'>NE</option>
				<option value='NH'>NH</option>
				<option value='NJ'>NJ</option>
				<option value='NM'>NM</option>
				<option value='NV'>NV</option>

				<option value='NY'>NY</option>
				<option value='OH'>OH</option>
				<option value='OK'>OK</option>
				<option value='OR'>OR</option>
				<option value='PA'>PA</option>
				<option value='RI'>RI</option>

				<option value='SC'>SC</option>
				<option value='SD'>SD</option>
				<option value='TN'>TN</option>
				<option value='TX'>TX</option>
				<option value='UT'>UT</option>
				<option value='VA'>VA</option>

				<option value='VT'>VT</option>
				<option value='WA'>WA</option>
				<option value='WI'>WI</option>
				<option value='WV'>WV</option>
				<option value='WY'>WY</option>
			</select></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.country}:&nbsp;</td>
        <td><select name="country" style="width:170px">
<option value="" selected>{$lang.addclient_signup_country_choose}</option>
<option value="">----------</option>
<option value="US">United States</option>
<option value="CA">Canada</option>
<option value="">----------</option>
<option value="AF">Afghanistan</option>
<option value="AL">Albania</option>
<option value="DZ">Algeria</option>
<option value="AS">American Samoa</option>
<option value="AD">Andorra</option>
<option value="AO">Angola</option>
<option value="AI">Anguilla</option>
<option value="AQ">Antarctica</option>
<option value="AG">Antigua and Barbuda</option>
<option value="AR">Argentina</option>
<option value="AM">Armenia</option>
<option value="AW">Aruba</option>
<option value="AU">Australia</option>
<option value="AT">Austria</option>
<option value="AZ">Azerbaidjan</option>
<option value="BS">Bahamas</option>
<option value="BH">Bahrain</option>
<option value="BD">Bangladesh</option>
<option value="BB">Barbados</option>
<option value="BY">Belarus</option>
<option value="BE">Belgium</option>
<option value="BZ">Belize</option>
<option value="BJ">Benin</option>
<option value="BM">Bermuda</option>
<option value="BT">Bhutan</option>
<option value="BO">Bolivia</option>
<option value="BA">Bosnia-Herzegovina</option>
<option value="BW">Botswana</option>
<option value="BV">Bouvet Island</option>
<option value="BR">Brazil</option>
<option value="IO">British Indian Ocean Territory</option>
<option value="BN">Brunei Darussalam</option>
<option value="BG">Bulgaria</option>
<option value="BF">Burkina Faso</option>
<option value="BI">Burundi</option>
<option value="KH">Cambodia</option>
<option value="CM">Cameroon</option>
<option value="CV">Cape Verde</option>
<option value="KY">Cayman Islands</option>
<option value="CF">Central African Republic</option>
<option value="TD">Chad</option>
<option value="CL">Chile</option>
<option value="CN">China</option>
<option value="CX">Christmas Island</option>
<option value="CC">Cocos (Keeling) Islands</option>
<option value="CO">Colombia</option>
<option value="KM">Comoros</option>
<option value="CG">Congo</option>
<option value="CK">Cook Islands</option>
<option value="CR">Costa Rica</option>
<option value="HR">Croatia</option>
<option value="CU">Cuba</option>
<option value="CY">Cyprus</option>
<option value="CZ">Czech Republic</option>
<option value="DK">Denmark</option>
<option value="DJ">Djibouti</option>
<option value="DM">Dominica</option>
<option value="DO">Dominican Republic</option>
<option value="TP">East Timor</option>
<option value="EC">Ecuador</option>
<option value="EG">Egypt</option>
<option value="SV">El Salvador</option>
<option value="GQ">Equatorial Guinea</option>
<option value="ER">Eritrea</option>
<option value="EE">Estonia</option>
<option value="ET">Ethiopia</option>
<option value="FK">Falkland Islands</option>
<option value="FO">Faroe Islands</option>
<option value="FJ">Fiji</option>
<option value="FI">Finland</option>
<option value="CS">Former Czechoslovakia</option>
<option value="SU">Former USSR</option>
<option value="FR">France</option>
<option value="FX">France (European Territory)</option>
<option value="GF">French Guyana</option>
<option value="TF">French Southern Territories</option>
<option value="GA">Gabon</option>
<option value="GM">Gambia</option>
<option value="GE">Georgia</option>
<option value="DE">Germany</option>
<option value="GH">Ghana</option>
<option value="GI">Gibraltar</option>
<option value="GB">Great Britain</option>
<option value="GR">Greece</option>
<option value="GL">Greenland</option>
<option value="GD">Grenada</option>
<option value="GP">Guadeloupe (French)</option>
<option value="GU">Guam (USA)</option>
<option value="GT">Guatemala</option>
<option value="GN">Guinea</option>
<option value="GW">Guinea Bissau</option>
<option value="GY">Guyana</option>
<option value="HT">Haiti</option>
<option value="HM">Heard and McDonald Islands</option>
<option value="HN">Honduras</option>
<option value="HK">Hong Kong</option>
<option value="HU">Hungary</option>
<option value="IS">Iceland</option>
<option value="IN">India</option>
<option value="ID">Indonesia</option>
<option value="INT">International</option>
<option value="IR">Iran</option>
<option value="IQ">Iraq</option>
<option value="IE">Ireland</option>
<option value="IL">Israel</option>
<option value="IT">Italy</option>
<option value="CI">Ivory Coast (Cote D&#39;Ivoire)</option>
<option value="JM">Jamaica</option>
<option value="JP">Japan</option>
<option value="JO">Jordan</option>
<option value="KZ">Kazakhstan</option>
<option value="KE">Kenya</option>
<option value="KI">Kiribati</option>
<option value="KW">Kuwait</option>
<option value="KG">Kyrgyzstan</option>
<option value="LA">Laos</option>
<option value="LV">Latvia</option>
<option value="LB">Lebanon</option>
<option value="LS">Lesotho</option>
<option value="LR">Liberia</option>
<option value="LY">Libya</option>
<option value="LI">Liechtenstein</option>
<option value="LT">Lithuania</option>
<option value="LU">Luxembourg</option>
<option value="MO">Macau</option>
<option value="MK">Macedonia</option>
<option value="MG">Madagascar</option>
<option value="MW">Malawi</option>
<option value="MY">Malaysia</option>
<option value="MV">Maldives</option>
<option value="ML">Mali</option>
<option value="MT">Malta</option>
<option value="MH">Marshall Islands</option>
<option value="MQ">Martinique (French)</option>
<option value="MR">Mauritania</option>
<option value="MU">Mauritius</option>
<option value="YT">Mayotte</option>
<option value="MX">Mexico</option>
<option value="FM">Micronesia</option>
<option value="MD">Moldavia</option>
<option value="MC">Monaco</option>
<option value="MN">Mongolia</option>
<option value="MS">Montserrat</option>
<option value="MA">Morocco</option>
<option value="MZ">Mozambique</option>
<option value="MM">Myanmar</option>
<option value="NA">Namibia</option>
<option value="NR">Nauru</option>
<option value="NP">Nepal</option>
<option value="NL">Netherlands</option>
<option value="AN">Netherlands Antilles</option>
<option value="NT">Neutral Zone</option>
<option value="NC">New Caledonia (French)</option>
<option value="NZ">New Zealand</option>
<option value="NI">Nicaragua</option>
<option value="NE">Niger</option>
<option value="NG">Nigeria</option>
<option value="NU">Niue</option>
<option value="NF">Norfolk Island</option>
<option value="KP">North Korea</option>
<option value="MP">Northern Mariana Islands</option>
<option value="NO">Norway</option>
<option value="OM">Oman</option>
<option value="PK">Pakistan</option>
<option value="PW">Palau</option>
<option value="PA">Panama</option>
<option value="PG">Papua New Guinea</option>
<option value="PY">Paraguay</option>
<option value="PE">Peru</option>
<option value="PH">Philippines</option>
<option value="PN">Pitcairn Island</option>
<option value="PL">Poland</option>
<option value="PF">Polynesia (French)</option>
<option value="PT">Portugal</option>
<option value="PR">Puerto Rico</option>
<option value="QA">Qatar</option>
<option value="RE">Reunion (French)</option>
<option value="RO">Romania</option>
<option value="RU">Russian Federation</option>
<option value="RW">Rwanda</option>
<option value="GS">S. Georgia & S. Sandwich Isls.</option>
<option value="SH">Saint Helena</option>
<option value="KN">Saint Kitts & Nevis Anguilla</option>
<option value="LC">Saint Lucia</option>
<option value="PM">Saint Pierre and Miquelon</option>
<option value="ST">Saint Tome (Sao Tome) and Principe</option>
<option value="VC">Saint Vincent & Grenadines</option>
<option value="WS">Samoa</option>
<option value="SM">San Marino</option>
<option value="SA">Saudi Arabia</option>
<option value="SN">Senegal</option>
<option value="SC">Seychelles</option>
<option value="SL">Sierra Leone</option>
<option value="SG">Singapore</option>
<option value="SK">Slovak Republic</option>
<option value="SI">Slovenia</option>
<option value="SB">Solomon Islands</option>
<option value="SO">Somalia</option>
<option value="ZA">South Africa</option>
<option value="KR">South Korea</option>
<option value="ES">Spain</option>
<option value="LK">Sri Lanka</option>
<option value="SD">Sudan</option>
<option value="SR">Suriname</option>
<option value="SJ">Svalbard and Jan Mayen Islands</option>
<option value="SZ">Swaziland</option>
<option value="SE">Sweden</option>
<option value="CH">Switzerland</option>
<option value="SY">Syria</option>
<option value="TJ">Tadjikistan</option>
<option value="TW">Taiwan</option>
<option value="TZ">Tanzania</option>
<option value="TH">Thailand</option>
<option value="TG">Togo</option>
<option value="TK">Tokelau</option>
<option value="TO">Tonga</option>
<option value="TT">Trinidad and Tobago</option>
<option value="TN">Tunisia</option>
<option value="TR">Turkey</option>
<option value="TM">Turkmenistan</option>
<option value="TC">Turks and Caicos Islands</option>
<option value="TV">Tuvalu</option>
<option value="UG">Uganda</option>
<option value="UA">Ukraine</option>
<option value="AE">United Arab Emirates</option>
<option value="GB">United Kingdom</option>
<option value="UY">Uruguay</option>
<option value="MIL">USA Military</option>
<option value="UM">USA Minor Outlying Islands</option>
<option value="UZ">Uzbekistan</option>
<option value="VU">Vanuatu</option>
<option value="VA">Vatican City State</option>
<option value="VE">Venezuela</option>
<option value="VN">Vietnam</option>
<option value="VG">Virgin Islands (British)</option>
<option value="VI">Virgin Islands (USA)</option>
<option value="WF">Wallis and Futuna Islands</option>
<option value="EH">Western Sahara</option>
<option value="YE">Yemen</option>
<option value="YU">Yugoslavia</option>
<option value="ZR">Zaire</option>
<option value="ZM">Zambia</option>
<option value="ZW">Zimbabwe</option>
</select></td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.zip}:&nbsp;</td>
        <td><input type="text" name="zip_code" class="textbox_normal" style="width:170px"></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>

      <tr>
        <td align="right" class="description">{$lang.language}:&nbsp;</td>
        <td>
          <select name="language" style="width:170px">
            {section name=langz loop=$languages}
                {if $languages[langz] == $default_language}
                    <option value="{$languages[langz]}" selected>{$languages[langz]|ucwords}</option>
                {else}
                    <option value="{$languages[langz]}">{$languages[langz]|ucwords}</option>
                {/if}
            {/section}
          </select>
        </td>
      </tr>
      <tr>
        <td align="right" class="description">{$lang.status}:&nbsp;</td>
        <td>
          <select name="status" class="dropdown" style="width:170px">
            <option value="active" selected>{$lang.status_active}</option>
            <option value="suspended">{$lang.status_suspended}</option>
          </select>
        </td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td align="center" colspan="2" class="description">{$lang.private_notes}<br /><textarea name="notes" style="width:95%;height:100px"></textarea></td>
      </tr>

      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
      
      <tr>
        <td colspan="2" align="center"><input type="submit" name="create" value=" " class="button_create" /></td>
      </tr>
      
      <tr>
        <td colspan="2">&nbsp;</td>
      </tr>
    </table>
    </form>

    {include file="$template/footer.tpl"}

{/if}
