@extends('admin.layouts.admin')

@section('title', 'Kelola FAQ - Ijen Geopark')

@section('content')
    <div class="card">
        <h2>Kelola FAQ</h2>
        <a href="#form-tambah" class="action-btn btn-add">+ Tambah FAQ Baru</a>

        <!-- Daftar FAQ -->
        <table>
            <tr>
                <th>ID</th>
                <th>Pertanyaan</th>
                <th>Jawaban</th>
                <th>Aksi</th>
            </tr>
            @foreach($faqs as $faq)
            <tr>
                <td>{{ $faq->id }}</td>
                <td>{{ htmlspecialchars($faq->pertanyaan) }}</td>
                <td>{{ \Illuminate\Support\Str::limit(htmlspecialchars($faq->jawaban), 50) }}</td>
                <td>
                    <form action="{{ url('admin/faq/'.$faq->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus FAQ ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn btn-delete">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @if(count($faqs) == 0)
            <tr><td colspan="4" style="text-align:center;">Belum ada FAQ.</td></tr>
            @endif
        </table>
    </div>

    <!-- Form Tambah -->
    <div class="card" id="form-tambah" style="margin-top: 30px;">
        <h3>Tambah FAQ Baru</h3>
        
        @if($errors->any())
            <div style="color:red; margin-bottom:15px;">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('admin/faq') }}">
            @csrf
            <div class="form-group">
                <label>Pertanyaan</label>
                <input type="text" name="pertanyaan" required>
            </div>
            <div class="form-group">
                <label>Jawaban</label>
                <textarea name="jawaban" style="height: 100px;" required></textarea>
            </div>
            <button type="submit">Simpan FAQ</button>
        </form>
    </div>
@endsection
