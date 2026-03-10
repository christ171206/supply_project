@extends('vendeur.layout-dashboard')

@section('content')
<div class="p-8 bg-white min-h-screen">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-serif text-[#0a0a0a]">Profil Vendeur</h1>
            <p class="text-[13px] text-[#666660] font-light mt-2">Gérez vos informations personnelles</p>
        </div>

        <!-- Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-[#fef2f2] border border-[#fecaca] rounded-lg">
                <p class="text-[#dc2626] font-medium text-[13px] mb-2">Erreurs détectées</p>
                <ul class="text-[#dc2626] text-[12px] space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 p-4 bg-[#f0fdf4] border border-[#bbf7d0] rounded-lg">
                <p class="text-[#15803d] font-medium text-[13px]">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Section Photo de Profil -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-8 mb-8">
            <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Photo de Profil</h2>

            <form action="{{ route('vendeur.profil.photo') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

                <!-- Aperçu Photo -->
                <div class="flex flex-col items-center mb-6">
                    @if(Auth::user()->profile_photo)
                        <img id="vendor-photo-preview" src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Photo" class="w-32 h-32 rounded-lg object-cover border border-[#e0e0dc]">
                    @else
                        <div class="w-32 h-32 rounded-lg bg-[#f7f7f5] flex items-center justify-center border border-[#e0e0dc]">
                            <span class="text-[#a0a09a] text-[12px]">Pas de photo</span>
                        </div>
                    @endif
                </div>

                <!-- Zone de dépôt -->
                <div class="border-2 border-dashed border-[#e0e0dc] rounded-lg p-6 text-center hover:border-[#a0a09a] transition cursor-pointer">
                    <input type="file" id="vendor-profile-photo" name="profile_photo" accept="image/*" class="hidden" onchange="previewVendorPhoto(event)">

                    <p class="text-[#0a0a0a] font-medium text-[13px]">Glissez votre photo ou cliquez</p>
                    <p class="text-[#a0a09a] text-[12px] mt-1">JPG, PNG • Max 2 MB</p>
                </div>

                @error('profile_photo')
                    <p class="text-[#dc2626] text-[12px]">{{ $message }}</p>
                @enderror

                <button type="submit" class="w-full bg-[#0a0a0a] text-white px-6 py-2.5 rounded-lg hover:opacity-85 transition font-medium text-[13px]">
                    Enregistrer
                </button>
            </form>
        </div>

        <!-- Section Informations Personnelles -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-8 mb-8">
            <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Informations Personnelles</h2>

            <form action="{{ route('vendeur.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Nom</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none text-[13px]">
                        @error('name')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none text-[13px]">
                        @error('email')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#0a0a0a] text-white px-6 py-2.5 rounded-lg hover:opacity-85 transition font-medium text-[13px]">
                    Mettre à jour
                </button>
            </form>
        </div>

        <!-- Section Informations Boutique -->
        <div class="bg-white border border-[#e0e0dc] rounded-lg p-8">
            <h2 class="text-lg font-medium text-[#0a0a0a] mb-6">Informations Boutique</h2>

            <form action="{{ route('vendeur.profil.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom Boutique -->
                    <div>
                        <label for="shop_name" class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Nom Boutique</label>
                        <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name', Auth::user()->shop_name ?? '') }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none text-[13px]">
                        @error('shop_name')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label for="phone" class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Téléphone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone ?? '') }}"
                               class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none text-[13px]">
                        @error('phone')
                            <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Adresse -->
                <div>
                    <label for="address" class="block text-[13px] font-medium text-[#0a0a0a] mb-2">Adresse</label>
                    <textarea id="address" name="address" rows="3"
                              class="w-full px-4 py-2 border border-[#e0e0dc] rounded-lg focus:border-[#0a0a0a] focus:outline-none text-[13px]">{{ old('address', Auth::user()->address ?? '') }}</textarea>
                    @error('address')
                        <p class="text-[#dc2626] text-[12px] mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-[#0a0a0a] text-white px-6 py-2.5 rounded-lg hover:opacity-85 transition font-medium text-[13px]">
                    Mettre à jour
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function previewVendorPhoto(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('vendor-photo-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
                    @error('address')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-900 mb-3">Description de Votre Boutique</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        placeholder="Parlez de votre boutique, vos spécialités, votre expérience..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition bg-gray-50"
                    >{{ old('description', Auth::user()->description ?? '') }}</textarea>
                    <p class="text-gray-500 text-xs mt-2">Cette description sera affichée sur votre profil</p>
                    @error('description')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-xl font-bold transition duration-200 shadow-lg hover:shadow-xl flex items-center justify-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    <span>Mettre à jour la boutique</span>
                </button>
            </form>
        </div>

        <!-- Section Sécurité -->
        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-2xl shadow-xl border border-red-200 p-10 mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-lock-closed class="w-6 h-6 text-red-600" />
                </div>
                Sécurité & Confidentialité
            </h2>

            <div class="space-y-4">
                <p class="text-gray-700">Gérez la sécurité de votre compte et changez votre mot de passe.</p>
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white px-8 py-3 rounded-xl font-bold transition duration-200 shadow-lg hover:shadow-xl">
                    <x-heroicon-o-key class="w-5 h-5" />
                    <span>Changer mon mot de passe</span>
                </a>
            </div>
        </div>

        <!-- Conseil -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl border-2 border-blue-300 p-8">
            <div class="flex gap-4">
                <x-heroicon-o-light-bulb class="w-8 h-8 text-blue-600 flex-shrink-0 mt-1" />
                <div>
                    <p class="text-blue-900 font-bold text-lg mb-2">Conseil Important</p>
                    <p class="text-blue-800">Maintenez vos informations à jour pour que vos clients puissent vous trouver facilement et vous contacter. Une boutique bien complétée inspire confiance et obtient plus de commandes!</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Drag & Drop
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('vendor-profile-photo');

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('border-purple-500', 'bg-purple-50');
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.classList.remove('border-purple-500', 'bg-purple-50');
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('border-purple-500', 'bg-purple-50');
            fileInput.files = e.dataTransfer.files;
            previewVendorPhoto({ target: { files: e.dataTransfer.files } });
        });
    }

    function previewVendorPhoto(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('vendor-photo-preview');
            if (preview) {
                preview.src = e.target.result;
                preview.className = 'w-48 h-48 rounded-2xl object-cover shadow-2xl border-4 border-purple-300 transition-transform hover:scale-105';
            }
        };
        reader.readAsDataURL(file);
    }
</script>
@endsection
