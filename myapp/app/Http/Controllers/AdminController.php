<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use App\Models\Faq;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $total_artikel = Artikel::count();
        $total_faq = Faq::count();
        return view('admin.dashboard', compact('total_artikel', 'total_faq'));
    }

    // ARTIKEL
    public function kelolaArtikel()
    {
        $artikel = Artikel::orderBy('id', 'DESC')->get();
        return view('admin.kelola_artikel', compact('artikel'));
    }

    public function storeArtikel(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required',
            'label' => 'nullable',
            'ringkasan' => 'nullable',
            'konten' => 'nullable',
            'css_width' => 'numeric',
            'css_height' => 'numeric',
            'css_left' => 'numeric',
            'css_top' => 'numeric',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['gambar'] = $filename;
        } elseif ($request->filled('gambar_url')) {
            $data['gambar'] = $request->input('gambar_url');
        }

        $data['css_width'] = $data['css_width'] ?? 100;
        $data['css_height'] = $data['css_height'] ?? 100;
        $data['css_left'] = $data['css_left'] ?? 0;
        $data['css_top'] = $data['css_top'] ?? 0;

        Artikel::create($data);
        return redirect('/admin/kelola_artikel')->with('success', 'Artikel berhasil ditambahkan');
    }

    public function editArtikel($id)
    {
        $artikel = Artikel::findOrFail($id);
        return view('admin.edit_artikel', compact('artikel'));
    }

    public function updateArtikel(Request $request, $id)
    {
        $artikel = Artikel::findOrFail($id);
        
        $data = $request->validate([
            'judul' => 'required',
            'label' => 'nullable',
            'ringkasan' => 'nullable',
            'konten' => 'nullable',
            'css_width' => 'numeric',
            'css_height' => 'numeric',
            'css_left' => 'numeric',
            'css_top' => 'numeric',
        ]);

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['gambar'] = $filename;
        } elseif ($request->filled('gambar_url')) {
            $data['gambar'] = $request->input('gambar_url');
        }

        $data['css_width'] = $data['css_width'] ?? 100;
        $data['css_height'] = $data['css_height'] ?? 100;
        $data['css_left'] = $data['css_left'] ?? 0;
        $data['css_top'] = $data['css_top'] ?? 0;

        $artikel->update($data);
        return redirect('/admin/kelola_artikel')->with('success', 'Artikel berhasil diubah');
    }

    public function destroyArtikel($id)
    {
        $artikel = Artikel::findOrFail($id);
        $artikel->delete();
        return redirect('/admin/kelola_artikel')->with('success', 'Artikel berhasil dihapus');
    }

    // FAQ
    public function kelolaFaq()
    {
        $faqs = Faq::orderBy('id', 'DESC')->get();
        return view('admin.kelola_faq', compact('faqs'));
    }

    public function storeFaq(Request $request)
    {
        $data = $request->validate([
            'pertanyaan' => 'required',
            'jawaban' => 'required'
        ]);
        Faq::create($data);
        return redirect('/admin/kelola_faq')->with('success', 'FAQ berhasil ditambahkan');
    }

    public function destroyFaq($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
        return redirect('/admin/kelola_faq')->with('success', 'FAQ berhasil dihapus');
    }
}
