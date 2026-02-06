@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="container mx-auto px-4">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi biodata dan akun Anda</p>
    </div>

    <!-- Alert Message -->
    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border-l-4 border-green-500">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border-l-4 border-red-500">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 rounded-xl bg-red-50 border-l-4 border-red-500">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-700 mb-2">Mohon perbaiki kesalahan berikut:</p>
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Biodata Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-user-edit mr-2 text-purple-600"></i>Biodata Pegawai
                </h2>
                <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-medium">
                    <i class="fas fa-info-circle mr-1"></i>Isi dengan benar
                </span>
            </div>

            <form action="{{ route('pegawai.update.biodata') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nik" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-2 text-purple-600"></i>NIK
                        </label>
                        <input type="text" id="nik" value="{{ $pegawai->nik }}" readonly
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                    
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-purple-600"></i>Username
                        </label>
                        <input type="text" id="username" value="{{ $user->username }}" readonly
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-2 text-purple-600"></i>Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', $pegawai->nama) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Masukkan nama lengkap">
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="tgl_lahir" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-birthday-cake mr-2 text-purple-600"></i>Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', $pegawai->tgl_lahir) }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all">
                        @error('tgl_lahir')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jkel" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-2 text-purple-600"></i>Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="jkel" name="jkel" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all bg-white">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jkel', $pegawai->jkel) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jkel', $pegawai->jkel) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jkel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="skpd" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-building mr-2 text-purple-600"></i>SKPD
                    </label>
                    <input type="text" id="skpd" value="{{ $pegawai->skpd->nama ?? '-' }}" readonly
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-100 text-gray-600 cursor-not-allowed">
                </div>

                <div>
                    <label for="telp" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-purple-600"></i>Nomor Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="telp" name="telp" value="{{ old('telp', $pegawai->telp) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                        placeholder="Masukkan nomor telepon">
                    @error('telp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="3" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all resize-none"
                        placeholder="Masukkan alamat lengkap">{{ old('alamat', $pegawai->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-500 text-white font-semibold rounded-xl hover:from-purple-700 hover:to-pink-600 focus:ring-4 focus:ring-purple-300 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-save mr-2"></i>Simpan Biodata
                    </button>
                </div>
            </form>
        </div>

        <!-- Password Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-lock mr-2 text-purple-600"></i>Ubah Password
                </h2>
                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm font-medium">
                    <i class="fas fa-shield-alt mr-1"></i>Penting
                </span>
            </div>

            <form action="{{ route('pegawai.update.password') }}" method="POST" class="space-y-5">
                @csrf
                
                <div class="p-4 bg-blue-50 rounded-xl border border-blue-200 mb-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                <strong>Tips Password yang Aman:</strong>
                            </p>
                            <ul class="text-sm text-blue-600 mt-1 list-disc list-inside space-y-1">
                                <li>Minimal 8 karakter</li>
                                <li>Gunakan kombinasi huruf dan angka</li>
                                <li>Gunakan karakter khusus (!@#$%)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key mr-2 text-purple-600"></i>Password Saat Ini <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="current_password" name="current_password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            placeholder="Masukkan password saat ini">
                        <button type="button" onclick="togglePassword('current_password')" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="current_password_icon"></i>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-purple-600"></i>Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            placeholder="Masukkan password baru">
                        <button type="button" onclick="togglePassword('password')" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password_icon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-purple-600"></i>Konfirmasi Password Baru <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                            placeholder="Konfirmasi password baru">
                        <button type="button" onclick="togglePassword('password_confirmation')" 
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye" id="password_confirmation_icon"></i>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-4">
                    <button type="submit" 
                        class="w-full px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-xl hover:from-yellow-600 hover:to-orange-600 focus:ring-4 focus:ring-yellow-300 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <i class="fas fa-key mr-2"></i>Ubah Password
                    </button>
                </div>
            </form>

            <!-- Account Info -->
            <div class="mt-8 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-3">
                    <i class="fas fa-info-circle mr-2 text-gray-500"></i>Informasi Akun
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Username:</span>
                        <span class="font-medium text-gray-800">{{ $user->username }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Role:</span>
                        <span class="px-2 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium uppercase">{{ $user->role }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Terakhir Login:</span>
                        <span class="font-medium text-gray-800">{{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '_icon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection