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
            <label for="prenom" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
            <input id="prenom" type="text" name="prenom" value="{{ old('prenom') }}" required
                   class="input-linpay" placeholder="Jean">
            @error('prenom') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Adresse email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                   class="input-linpay" placeholder="jean@exemple.com">
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Pays -->
        <div class="mb-4">
            <label for="pays" class="block text-sm font-medium text-gray-700 mb-1">Pays</label>
            <select id="pays" name="pays" required class="input-linpay bg-white">
                <option value="" disabled selected>-- Sélectionnez votre pays --</option>

                <!-- AFRIQUE -->
                <optgroup label="🌍 Afrique">
                    <option value="Afrique du Sud" data-dial="+27">Afrique du Sud</option>
                    <option value="Algérie" data-dial="+213">Algérie</option>
                    <option value="Angola" data-dial="+244">Angola</option>
                    <option value="Bénin" data-dial="+229">Bénin</option>
                    <option value="Botswana" data-dial="+267">Botswana</option>
                    <option value="Burkina Faso" data-dial="+226">Burkina Faso</option>
                    <option value="Burundi" data-dial="+257">Burundi</option>
                    <option value="Cameroun" data-dial="+237">Cameroun</option>
                    <option value="Cap-Vert" data-dial="+238">Cap-Vert</option>
                    <option value="Côte d'Ivoire" data-dial="+225">Côte d'Ivoire</option>
                    <option value="Djibouti" data-dial="+253">Djibouti</option>
                    <option value="Égypte" data-dial="+20">Égypte</option>
                    <option value="Érythrée" data-dial="+291">Érythrée</option>
                    <option value="Éthiopie" data-dial="+251">Éthiopie</option>
                    <option value="Gabon" data-dial="+241">Gabon</option>
                    <option value="Gambie" data-dial="+220">Gambie</option>
                    <option value="Ghana" data-dial="+233">Ghana</option>
                    <option value="Guinée" data-dial="+224">Guinée</option>
                    <option value="Guinée-Bissau" data-dial="+245">Guinée-Bissau</option>
                    <option value="Kenya" data-dial="+254">Kenya</option>
                    <option value="Lesotho" data-dial="+266">Lesotho</option>
                    <option value="Liberia" data-dial="+231">Liberia</option>
                    <option value="Libye" data-dial="+218">Libye</option>
                    <option value="Madagascar" data-dial="+261">Madagascar</option>
                    <option value="Malawi" data-dial="+265">Malawi</option>
                    <option value="Mali" data-dial="+223">Mali</option>
                    <option value="Maroc" data-dial="+212">Maroc</option>
                    <option value="Maurice" data-dial="+230">Maurice</option>
                    <option value="Mauritanie" data-dial="+222">Mauritanie</option>
                    <option value="Mozambique" data-dial="+258">Mozambique</option>
                    <option value="Namibie" data-dial="+264">Namibie</option>
                    <option value="Niger" data-dial="+227">Niger</option>
                    <option value="Nigéria" data-dial="+234">Nigéria</option>
                    <option value="Ouganda" data-dial="+256">Ouganda</option>
                    <option value="République démocratique du Congo" data-dial="+243">République démocratique du Congo</option>
                    <option value="Rwanda" data-dial="+250">Rwanda</option>
                    <option value="Sénégal" data-dial="+221">Sénégal</option>
                    <option value="Seychelles" data-dial="+248">Seychelles</option>
                    <option value="Sierra Leone" data-dial="+232">Sierra Leone</option>
                    <option value="Somalie" data-dial="+252">Somalie</option>
                    <option value="Soudan" data-dial="+249">Soudan</option>
                    <option value="Soudan du Sud" data-dial="+211">Soudan du Sud</option>
                    <option value="Eswatini" data-dial="+268">Eswatini</option>
                    <option value="Tanzanie" data-dial="+255">Tanzanie</option>
                    <option value="Tchad" data-dial="+235">Tchad</option>
                    <option value="Togo" data-dial="+228">Togo</option>
                    <option value="Tunisie" data-dial="+216">Tunisie</option>
                    <option value="Zambie" data-dial="+260">Zambie</option>
                    <option value="Zimbabwe" data-dial="+263">Zimbabwe</option>
                </optgroup>

                <!-- AMÉRIQUES -->
                <optgroup label="🌎 Amériques">
                    <option value="Antigua-et-Barbuda" data-dial="+1-268">Antigua-et-Barbuda</option>
                    <option value="Argentine" data-dial="+54">Argentine</option>
                    <option value="Bahamas" data-dial="+1-242">Bahamas</option>
                    <option value="Barbade" data-dial="+1-246">Barbade</option>
                    <option value="Belize" data-dial="+501">Belize</option>
                    <option value="Bolivie" data-dial="+591">Bolivie</option>
                    <option value="Brésil" data-dial="+55">Brésil</option>
                    <option value="Canada" data-dial="+1">Canada</option>
                    <option value="Chili" data-dial="+56">Chili</option>
                    <option value="Colombie" data-dial="+57">Colombie</option>
                    <option value="Costa Rica" data-dial="+506">Costa Rica</option>
                    <option value="Cuba" data-dial="+53">Cuba</option>
                    <option value="Dominique" data-dial="+1-767">Dominique</option>
                    <option value="Équateur" data-dial="+593">Équateur</option>
                    <option value="États-Unis" data-dial="+1">États-Unis</option>
                    <option value="Grenade" data-dial="+1-473">Grenade</option>
                    <option value="Guatemala" data-dial="+502">Guatemala</option>
                    <option value="Guyana" data-dial="+592">Guyana</option>
                    <option value="Haïti" data-dial="+509">Haïti</option>
                    <option value="Honduras" data-dial="+504">Honduras</option>
                    <option value="Jamaïque" data-dial="+1-876">Jamaïque</option>
                    <option value="Mexique" data-dial="+52">Mexique</option>
                    <option value="Nicaragua" data-dial="+505">Nicaragua</option>
                    <option value="Panama" data-dial="+507">Panama</option>
                    <option value="Paraguay" data-dial="+595">Paraguay</option>
                    <option value="Pérou" data-dial="+51">Pérou</option>
                    <option value="République dominicaine" data-dial="+1-809">République dominicaine</option>
                    <option value="Saint-Christophe-et-Niévès" data-dial="+1-869">Saint-Christophe-et-Niévès</option>
                    <option value="Saint-Vincent-et-les-Grenadines" data-dial="+1-784">Saint-Vincent-et-les-Grenadines</option>
                    <option value="Sainte-Lucie" data-dial="+1-758">Sainte-Lucie</option>
                    <option value="Suriname" data-dial="+597">Suriname</option>
                    <option value="Trinité-et-Tobago" data-dial="+1-868">Trinité-et-Tobago</option>
                    <option value="Uruguay" data-dial="+598">Uruguay</option>
                    <option value="Venezuela" data-dial="+58">Venezuela</option>
                </optgroup>

                <!-- ASIE -->
                <optgroup label="🌏 Asie">
                    <option value="Afghanistan" data-dial="+93">Afghanistan</option>
                    <option value="Arabie saoudite" data-dial="+966">Arabie saoudite</option>
                    <option value="Arménie" data-dial="+374">Arménie</option>
                    <option value="Azerbaïdjan" data-dial="+994">Azerbaïdjan</option>
                    <option value="Bahreïn" data-dial="+973">Bahreïn</option>
                    <option value="Bangladesh" data-dial="+880">Bangladesh</option>
                    <option value="Birmanie" data-dial="+95">Birmanie</option>
                    <option value="Brunei" data-dial="+673">Brunei</option>
                    <option value="Cambodge" data-dial="+855">Cambodge</option>
                    <option value="Chine" data-dial="+86">Chine</option>
                    <option value="Corée du Sud" data-dial="+82">Corée du Sud</option>
                    <option value="Émirats arabes unis" data-dial="+971">Émirats arabes unis</option>
                    <option value="Inde" data-dial="+91">Inde</option>
                    <option value="Indonésie" data-dial="+62">Indonésie</option>
                    <option value="Iran" data-dial="+98">Iran</option>
                    <option value="Irak" data-dial="+964">Irak</option>
                    <option value="Israël" data-dial="+972">Israël</option>
                    <option value="Japon" data-dial="+81">Japon</option>
                    <option value="Jordanie" data-dial="+962">Jordanie</option>
                    <option value="Kazakhstan" data-dial="+7">Kazakhstan</option>
                    <option value="Koweït" data-dial="+965">Koweït</option>
                    <option value="Kirghizistan" data-dial="+996">Kirghizistan</option>
                    <option value="Laos" data-dial="+856">Laos</option>
                    <option value="Liban" data-dial="+961">Liban</option>
                    <option value="Malaisie" data-dial="+60">Malaisie</option>
                    <option value="Maldives" data-dial="+960">Maldives</option>
                    <option value="Mongolie" data-dial="+976">Mongolie</option>
                    <option value="Népal" data-dial="+977">Népal</option>
                    <option value="Oman" data-dial="+968">Oman</option>
                    <option value="Ouzbékistan" data-dial="+998">Ouzbékistan</option>
                    <option value="Pakistan" data-dial="+92">Pakistan</option>
                    <option value="Philippines" data-dial="+63">Philippines</option>
                    <option value="Qatar" data-dial="+974">Qatar</option>
                    <option value="Singapour" data-dial="+65">Singapour</option>
                    <option value="Sri Lanka" data-dial="+94">Sri Lanka</option>
                    <option value="Syrie" data-dial="+963">Syrie</option>
                    <option value="Taïwan" data-dial="+886">Taïwan</option>
                    <option value="Tadjikistan" data-dial="+992">Tadjikistan</option>
                    <option value="Thaïlande" data-dial="+66">Thaïlande</option>
                    <option value="Timor oriental" data-dial="+670">Timor oriental</option>
                    <option value="Turkménistan" data-dial="+993">Turkménistan</option>
                    <option value="Turquie" data-dial="+90">Turquie</option>
                    <option value="Vietnam" data-dial="+84">Vietnam</option>
                    <option value="Yémen" data-dial="+967">Yémen</option>
                </optgroup>

                <!-- EUROPE -->
                <optgroup label="🇪🇺 Europe">
                    <option value="Allemagne" data-dial="+49">Allemagne</option>
                    <option value="Autriche" data-dial="+43">Autriche</option>
                    <option value="Belgique" data-dial="+32">Belgique</option>
                    <option value="Bulgarie" data-dial="+359">Bulgarie</option>
                    <option value="Croatie" data-dial="+385">Croatie</option>
                    <option value="Danemark" data-dial="+45">Danemark</option>
                    <option value="Espagne" data-dial="+34">Espagne</option>
                    <option value="Estonie" data-dial="+372">Estonie</option>
                    <option value="Finlande" data-dial="+358">Finlande</option>
                    <option value="France" data-dial="+33">France</option>
                    <option value="Grèce" data-dial="+30">Grèce</option>
                    <option value="Hongrie" data-dial="+36">Hongrie</option>
                    <option value="Irlande" data-dial="+353">Irlande</option>
                    <option value="Islande" data-dial="+354">Islande</option>
                    <option value="Italie" data-dial="+39">Italie</option>
                    <option value="Lettonie" data-dial="+371">Lettonie</option>
                    <option value="Liechtenstein" data-dial="+423">Liechtenstein</option>
                    <option value="Lituanie" data-dial="+370">Lituanie</option>
                    <option value="Luxembourg" data-dial="+352">Luxembourg</option>
                    <option value="Macédoine du Nord" data-dial="+389">Macédoine du Nord</option>
                    <option value="Malte" data-dial="+356">Malte</option>
                    <option value="Moldavie" data-dial="+373">Moldavie</option>
                    <option value="Monaco" data-dial="+377">Monaco</option>
                    <option value="Monténégro" data-dial="+382">Monténégro</option>
                    <option value="Norvège" data-dial="+47">Norvège</option>
                    <option value="Pays-Bas" data-dial="+31">Pays-Bas</option>
                    <option value="Pologne" data-dial="+48">Pologne</option>
                    <option value="Portugal" data-dial="+351">Portugal</option>
                    <option value="Roumanie" data-dial="+40">Roumanie</option>
                    <option value="Royaume-Uni" data-dial="+44">Royaume-Uni</option>
                    <option value="Serbie" data-dial="+381">Serbie</option>
                    <option value="Slovaquie" data-dial="+421">Slovaquie</option>
                    <option value="Slovénie" data-dial="+386">Slovénie</option>
                    <option value="Suède" data-dial="+46">Suède</option>
                    <option value="Suisse" data-dial="+41">Suisse</option>
                    <option value="Tchéquie" data-dial="+420">Tchéquie</option>
                    <option value="Ukraine" data-dial="+380">Ukraine</option>
                    <option value="Biélorussie" data-dial="+375">Biélorussie</option>
                    <option value="Bosnie-Herzégovine" data-dial="+387">Bosnie-Herzégovine</option>
                    <option value="Chypre" data-dial="+357">Chypre</option>
                    <option value="Vatican" data-dial="+379">Vatican</option>
                    <option value="Saint-Marin" data-dial="+378">Saint-Marin</option>
                    <option value="Albanie" data-dial="+355">Albanie</option>
                    <option value="Andorre" data-dial="+376">Andorre</option>
                </optgroup>
            </select>
            @error('pays') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- Téléphone -->
        <div class="mb-4">
            <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone (optionnel)</label>
            <div class="flex items-stretch">
                <span id="dial-code" class="inline-flex items-center px-3 bg-gray-100 border border-r-0 border-gray-300 rounded-l-xl text-gray-600 text-sm">+??</span>
                <input id="telephone" type="tel" name="telephone" value="{{ old('telephone') }}"
                       class="input-linpay rounded-l-none" placeholder="6XX XXX XXX">
            </div>
            @error('telephone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- CODE DE PARRAINAGE -->
        <div class="mb-4">
            <label for="ref" class="block text-sm font-medium text-gray-700 mb-1">Code de parrainage (optionnel)</label>
            <input id="ref" type="text" name="ref" value="{{ old('ref', request()->get('ref')) }}"
                   class="input-linpay" placeholder="Ex: LIN-ABC123">
            @error('ref') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <!-- CODE OTP -->
        <div class="mb-4">
            <label for="otp" class="block text-sm font-medium text-gray-700 mb-1">Code de vérification (OTP) *</label>
            <div class="flex gap-2">
                <input id="otp" type="text" name="otp" value="{{ old('otp') }}" required
                       class="input-linpay flex-1" placeholder="Ex: 123456">
                <button type="button" id="sendOtpBtn" class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition whitespace-nowrap">
                    Envoyer le code
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Un code de vérification vous sera envoyé par email.</p>
            @error('otp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
        if (paysSelect.value) updateDialCode();

        // Envoi OTP
        document.getElementById('sendOtpBtn')?.addEventListener('click', function() {
            const email = document.getElementById('email').value;
            if (!email) {
                alert('Veuillez d’abord saisir votre adresse email.');
                return;
            }

            this.textContent = 'Envoi...';
            this.disabled = true;

            fetch('{{ route("otp.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.textContent = 'Code envoyé ✓';
                    this.classList.remove('bg-emerald-600');
                    this.classList.add('bg-gray-400');
                    alert('Code de vérification envoyé à votre email.');
                } else {
                    this.textContent = 'Réessayer';
                    this.disabled = false;
                    alert(data.message || 'Erreur lors de l\'envoi.');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                this.textContent = 'Réessayer';
                this.disabled = false;
                alert('Erreur réseau. Veuillez réessayer.');
            });
        });
    </script>
</x-guest-layout>