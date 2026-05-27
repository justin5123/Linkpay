<x-guest-layout>
    <div class="text-center mb-2">
        <div class="form-title">Créer un compte</div>
        <div class="form-subtitle">Inscrivez-vous en quelques secondes</div>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nom -->
        <div class="mb-4">
            <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom complet</label>
            <input id="nom" type="text" name="nom" value="{{ old('nom') }}" required autofocus
                   class="input-linpay" placeholder="Jean Dupont">
            @error('nom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label for="prenom"
                class="block text-sm font-medium text-gray-700 mb-1">
                Prénom
            </label>

            <input
                id="prenom"
                type="text"
                name="prenom"
                value="{{ old('prenom') }}"
                required
                class="input-linpay"
                placeholder="Jean">

            @error('prenom')
                <p class="text-red-500 text-xs mt-1">
                    {{ $message }}
                </p>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="input-linpay" placeholder="jean@exemple.com">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Pays (select avec tous les pays) -->
        <div class="mb-4">
            <label for="pays" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
            <select id="pays" name="pays" required class="input-linpay bg-white">
                <option value="" disabled selected>-- Sélectionnez votre pays --</option>

                <!-- AFRIQUE (40 pays) -->
                <optgroup label="🌍 Afrique">
                    <option value="ZA" data-dial="+27">Afrique du Sud</option>
                    <option value="DZ" data-dial="+213">Algérie</option>
                    <option value="AO" data-dial="+244">Angola</option>
                    <option value="BJ" data-dial="+229">Bénin</option>
                    <option value="BW" data-dial="+267">Botswana</option>
                    <option value="BF" data-dial="+226">Burkina Faso</option>
                    <option value="BI" data-dial="+257">Burundi</option>
                    <option value="CM" data-dial="+237">Cameroun</option>
                    <option value="CV" data-dial="+238">Cap-Vert</option>
                    <option value="CI" data-dial="+225">Côte d'Ivoire</option>
                    <option value="DJ" data-dial="+253">Djibouti</option>
                    <option value="EG" data-dial="+20">Égypte</option>
                    <option value="ER" data-dial="+291">Érythrée</option>
                    <option value="ET" data-dial="+251">Éthiopie</option>
                    <option value="GA" data-dial="+241">Gabon</option>
                    <option value="GM" data-dial="+220">Gambie</option>
                    <option value="GH" data-dial="+233">Ghana</option>
                    <option value="GN" data-dial="+224">Guinée</option>
                    <option value="GW" data-dial="+245">Guinée-Bissau</option>
                    <option value="KE" data-dial="+254">Kenya</option>
                    <option value="LS" data-dial="+266">Lesotho</option>
                    <option value="LR" data-dial="+231">Liberia</option>
                    <option value="LY" data-dial="+218">Libye</option>
                    <option value="MG" data-dial="+261">Madagascar</option>
                    <option value="MW" data-dial="+265">Malawi</option>
                    <option value="ML" data-dial="+223">Mali</option>
                    <option value="MA" data-dial="+212">Maroc</option>
                    <option value="MU" data-dial="+230">Maurice</option>
                    <option value="MR" data-dial="+222">Mauritanie</option>
                    <option value="MZ" data-dial="+258">Mozambique</option>
                    <option value="NA" data-dial="+264">Namibie</option>
                    <option value="NE" data-dial="+227">Niger</option>
                    <option value="NG" data-dial="+234">Nigéria</option>
                    <option value="UG" data-dial="+256">Ouganda</option>
                    <option value="CD" data-dial="+243">République démocratique du Congo</option>
                    <option value="RW" data-dial="+250">Rwanda</option>
                    <option value="SN" data-dial="+221">Sénégal</option>
                    <option value="SC" data-dial="+248">Seychelles</option>
                    <option value="SL" data-dial="+232">Sierra Leone</option>
                    <option value="SO" data-dial="+252">Somalie</option>
                    <option value="SD" data-dial="+249">Soudan</option>
                    <option value="SS" data-dial="+211">Soudan du Sud</option>
                    <option value="SZ" data-dial="+268">Eswatini</option>
                    <option value="TZ" data-dial="+255">Tanzanie</option>
                    <option value="TD" data-dial="+235">Tchad</option>
                    <option value="TG" data-dial="+228">Togo</option>
                    <option value="TN" data-dial="+216">Tunisie</option>
                    <option value="ZM" data-dial="+260">Zambie</option>
                    <option value="ZW" data-dial="+263">Zimbabwe</option>
                </optgroup>

                <!-- AMÉRIQUES (35 pays) -->
                <optgroup label="🌎 Amériques">
                    <option value="AG" data-dial="+1-268">Antigua-et-Barbuda</option>
                    <option value="AR" data-dial="+54">Argentine</option>
                    <option value="BS" data-dial="+1-242">Bahamas</option>
                    <option value="BB" data-dial="+1-246">Barbade</option>
                    <option value="BZ" data-dial="+501">Belize</option>
                    <option value="BO" data-dial="+591">Bolivie</option>
                    <option value="BR" data-dial="+55">Brésil</option>
                    <option value="CA" data-dial="+1">Canada</option>
                    <option value="CL" data-dial="+56">Chili</option>
                    <option value="CO" data-dial="+57">Colombie</option>
                    <option value="CR" data-dial="+506">Costa Rica</option>
                    <option value="CU" data-dial="+53">Cuba</option>
                    <option value="DM" data-dial="+1-767">Dominique</option>
                    <option value="EC" data-dial="+593">Équateur</option>
                    <option value="US" data-dial="+1">États-Unis</option>
                    <option value="GD" data-dial="+1-473">Grenade</option>
                    <option value="GT" data-dial="+502">Guatemala</option>
                    <option value="GY" data-dial="+592">Guyana</option>
                    <option value="HT" data-dial="+509">Haïti</option>
                    <option value="HN" data-dial="+504">Honduras</option>
                    <option value="JM" data-dial="+1-876">Jamaïque</option>
                    <option value="MX" data-dial="+52">Mexique</option>
                    <option value="NI" data-dial="+505">Nicaragua</option>
                    <option value="PA" data-dial="+507">Panama</option>
                    <option value="PY" data-dial="+595">Paraguay</option>
                    <option value="PE" data-dial="+51">Pérou</option>
                    <option value="DO" data-dial="+1-809">République dominicaine</option>
                    <option value="KN" data-dial="+1-869">Saint-Christophe-et-Niévès</option>
                    <option value="VC" data-dial="+1-784">Saint-Vincent-et-les-Grenadines</option>
                    <option value="LC" data-dial="+1-758">Sainte-Lucie</option>
                    <option value="SR" data-dial="+597">Suriname</option>
                    <option value="TT" data-dial="+1-868">Trinité-et-Tobago</option>
                    <option value="UY" data-dial="+598">Uruguay</option>
                    <option value="VE" data-dial="+58">Venezuela</option>
                </optgroup>

                <!-- ASIE (40 pays) -->
                <optgroup label="🌏 Asie">
                    <option value="AF" data-dial="+93">Afghanistan</option>
                    <option value="SA" data-dial="+966">Arabie saoudite</option>
                    <option value="AM" data-dial="+374">Arménie</option>
                    <option value="AZ" data-dial="+994">Azerbaïdjan</option>
                    <option value="BH" data-dial="+973">Bahreïn</option>
                    <option value="BD" data-dial="+880">Bangladesh</option>
                    <option value="MM" data-dial="+95">Birmanie</option>
                    <option value="BN" data-dial="+673">Brunei</option>
                    <option value="KH" data-dial="+855">Cambodge</option>
                    <option value="CN" data-dial="+86">Chine</option>
                    <option value="KR" data-dial="+82">Corée du Sud</option>
                    <option value="AE" data-dial="+971">Émirats arabes unis</option>
                    <option value="IN" data-dial="+91">Inde</option>
                    <option value="ID" data-dial="+62">Indonésie</option>
                    <option value="IR" data-dial="+98">Iran</option>
                    <option value="IQ" data-dial="+964">Irak</option>
                    <option value="IL" data-dial="+972">Israël</option>
                    <option value="JP" data-dial="+81">Japon</option>
                    <option value="JO" data-dial="+962">Jordanie</option>
                    <option value="KZ" data-dial="+7">Kazakhstan</option>
                    <option value="KW" data-dial="+965">Koweït</option>
                    <option value="KG" data-dial="+996">Kirghizistan</option>
                    <option value="LA" data-dial="+856">Laos</option>
                    <option value="LB" data-dial="+961">Liban</option>
                    <option value="MY" data-dial="+60">Malaisie</option>
                    <option value="MV" data-dial="+960">Maldives</option>
                    <option value="MN" data-dial="+976">Mongolie</option>
                    <option value="NP" data-dial="+977">Népal</option>
                    <option value="OM" data-dial="+968">Oman</option>
                    <option value="UZ" data-dial="+998">Ouzbékistan</option>
                    <option value="PK" data-dial="+92">Pakistan</option>
                    <option value="PH" data-dial="+63">Philippines</option>
                    <option value="QA" data-dial="+974">Qatar</option>
                    <option value="SG" data-dial="+65">Singapour</option>
                    <option value="LK" data-dial="+94">Sri Lanka</option>
                    <option value="SY" data-dial="+963">Syrie</option>
                    <option value="TW" data-dial="+886">Taïwan</option>
                    <option value="TJ" data-dial="+992">Tadjikistan</option>
                    <option value="TH" data-dial="+66">Thaïlande</option>
                    <option value="TL" data-dial="+670">Timor oriental</option>
                    <option value="TM" data-dial="+993">Turkménistan</option>
                    <option value="TR" data-dial="+90">Turquie</option>
                    <option value="VN" data-dial="+84">Vietnam</option>
                    <option value="YE" data-dial="+967">Yémen</option>
                </optgroup>

                <!-- EUROPE (45 pays) -->
                <optgroup label="🇪🇺 Europe">
                    <option value="DE" data-dial="+49">Allemagne</option>
                    <option value="AT" data-dial="+43">Autriche</option>
                    <option value="BE" data-dial="+32">Belgique</option>
                    <option value="BG" data-dial="+359">Bulgarie</option>
                    <option value="HR" data-dial="+385">Croatie</option>
                    <option value="DK" data-dial="+45">Danemark</option>
                    <option value="ES" data-dial="+34">Espagne</option>
                    <option value="EE" data-dial="+372">Estonie</option>
                    <option value="FI" data-dial="+358">Finlande</option>
                    <option value="FR" data-dial="+33">France</option>
                    <option value="GR" data-dial="+30">Grèce</option>
                    <option value="HU" data-dial="+36">Hongrie</option>
                    <option value="IE" data-dial="+353">Irlande</option>
                    <option value="IS" data-dial="+354">Islande</option>
                    <option value="IT" data-dial="+39">Italie</option>
                    <option value="LV" data-dial="+371">Lettonie</option>
                    <option value="LI" data-dial="+423">Liechtenstein</option>
                    <option value="LT" data-dial="+370">Lituanie</option>
                    <option value="LU" data-dial="+352">Luxembourg</option>
                    <option value="MK" data-dial="+389">Macédoine du Nord</option>
                    <option value="MT" data-dial="+356">Malte</option>
                    <option value="MD" data-dial="+373">Moldavie</option>
                    <option value="MC" data-dial="+377">Monaco</option>
                    <option value="ME" data-dial="+382">Monténégro</option>
                    <option value="NO" data-dial="+47">Norvège</option>
                    <option value="NL" data-dial="+31">Pays-Bas</option>
                    <option value="PL" data-dial="+48">Pologne</option>
                    <option value="PT" data-dial="+351">Portugal</option>
                    <option value="RO" data-dial="+40">Roumanie</option>
                    <option value="GB" data-dial="+44">Royaume-Uni</option>
                    <option value="RS" data-dial="+381">Serbie</option>
                    <option value="SK" data-dial="+421">Slovaquie</option>
                    <option value="SI" data-dial="+386">Slovénie</option>
                    <option value="SE" data-dial="+46">Suède</option>
                    <option value="CH" data-dial="+41">Suisse</option>
                    <option value="CZ" data-dial="+420">Tchéquie</option>
                    <option value="UA" data-dial="+380">Ukraine</option>
                    <option value="BY" data-dial="+375">Biélorussie</option>
                    <option value="BA" data-dial="+387">Bosnie-Herzégovine</option>
                    <option value="CY" data-dial="+357">Chypre</option>
                    <option value="VA" data-dial="+379">Vatican</option>
                    <option value="SM" data-dial="+378">Saint-Marin</option>
                    <option value="AL" data-dial="+355">Albanie</option>
                    <option value="AD" data-dial="+376">Andorre</option>
                </optgroup>
            </select>
            @error('pays') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Téléphone avec indicatif dynamique -->
        <div class="mb-4">
            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone (optionnel)</label>
            <div class="flex items-stretch">
                <span id="dial-code" class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm">+??</span>
                <input id="telephone" type="tel" name="telephone" value="{{ old('telephone') }}"
                       class="input-linpay rounded-l-none" placeholder="6XX XXX XXX">
            </div>
            @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Mot de passe -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mot de passe</label>
            <input id="password" type="password" name="password" required class="input-linpay" placeholder="••••••••">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Confirmation -->
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required class="input-linpay" placeholder="••••••••">
        </div>

        <button type="submit" class="btn-linpay">
            S'inscrire
        </button>

        <div class="mt-6 text-center text-sm text-gray-600">
            Déjà membre ? <a href="{{ route('login') }}" class="text-emerald-600 font-medium hover:underline">Connectez-vous</a>
        </div>
    </form>

    <script>
        const paysSelect = document.getElementById('pays');
        const dialSpan = document.getElementById('dial-code');

        function updateDialCode() {
            const selectedOption = paysSelect.options[paysSelect.selectedIndex];
            const dial = selectedOption.getAttribute('data-dial');
            dialSpan.textContent = dial || '+??';
        }

        paysSelect.addEventListener('change', updateDialCode);
        // initialisation si déjà une valeur (après erreur de validation)
        if (paysSelect.value) updateDialCode();
    </script>
</x-guest-layout>