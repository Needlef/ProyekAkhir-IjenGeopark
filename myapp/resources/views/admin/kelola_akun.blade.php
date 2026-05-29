@extends('admin.layouts.admin')

@section('title', 'Kelola Akun - Ijen Geopark')

@section('content')
    <!-- Kelola Akun Sendiri -->
    <div class="card">
        <h2>Kelola Akun Saya</h2>
        <p style="color:#666; margin-bottom:20px;">Ubah username atau password akun Anda yang sedang login.</p>

        @if($errors->updateAkun->any())
            <div style="color:red; margin-bottom:15px;">
                <ul>
                    @foreach($errors->updateAkun->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('admin/akun') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="{{ auth()->user()->username }}" required autocomplete="off">
            </div>

            <div class="form-group">
                <label>Password Baru <small style="color:#888;">(kosongkan jika tidak ingin mengubah password)</small></label>
                <input type="password" name="password" placeholder="••••••••" autocomplete="new-password">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password Baru</label>
                <input type="password" name="password_confirmation" placeholder="••••••••" autocomplete="new-password">
            </div>

            <button type="submit">💾 Simpan Perubahan</button>
        </form>
    </div>

    <!-- Tambah Akun Admin Baru -->
    <div class="card" id="form-tambah" style="margin-top: 30px;">
        <h2>Tambah Akun Admin Baru</h2>

        @if($errors->storeAkun->any())
            <div style="color:red; margin-bottom:15px;">
                <ul>
                    @foreach($errors->storeAkun->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('admin/akun') }}">
            @csrf
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required autocomplete="off">
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required autocomplete="new-password">
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required autocomplete="new-password">
            </div>
            <button type="submit">➕ Tambah Akun Baru</button>
        </form>
    </div>
@endsection
