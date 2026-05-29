<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\Models\Artikel;
use App\Models\Faq;
use App\Models\CustomerStory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // AKUN ADMIN (kelola akun sendiri + buat akun baru)
    public function kelolaAkun()
    {
        return view('admin.kelola_akun');
    }

    public function updateAkun(Request $request)
    {
        $admin = auth()->user();

        $data = $request->validateWithBag('updateAkun', [
            'username' => 'required|unique:admin_users,username,' . $admin->id,
            'password' => 'nullable|min:4|confirmed',
        ]);

        $admin->username = $data['username'];

        if (!empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();
        return redirect('/admin/kelola_akun')->with('success', 'Akun berhasil diperbarui');
    }

    public function storeAkun(Request $request)
    {
        $data = $request->validateWithBag('storeAkun', [
            'username' => 'required|unique:admin_users,username',
            'password' => 'required|min:4|confirmed',
        ]);

        $data['password'] = Hash::make($data['password']);

        AdminUser::create($data);
        return redirect('/admin/kelola_akun')->with('success', 'Akun admin baru berhasil ditambahkan');
    }

    public function dashboard()
    {
        $total_artikel = Artikel::count();
        $total_faq = Faq::count();
        $total_customer_stories = CustomerStory::count();
        return view('admin.dashboard', compact('total_artikel', 'total_faq', 'total_customer_stories'));
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

    // CUSTOMER STORIES
    public function kelolaCustomerStories()
    {
        $stories = CustomerStory::orderBy('id', 'DESC')->get();
        return view('admin.kelola_customer_stories', compact('stories'));
    }

    public function storeCustomerStories(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'visitor_name' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        CustomerStory::create($data);
        return redirect('/admin/kelola_customer_stories')->with('success', 'Customer story berhasil ditambahkan');
    }

    public function editCustomerStories($id)
    {
        $story = CustomerStory::findOrFail($id);
        return view('admin.edit_customer_stories', compact('story'));
    }

    public function updateCustomerStories(Request $request, $id)
    {
        $story = CustomerStory::findOrFail($id);

        $data = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'visitor_name' => 'required'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $data['image'] = $filename;
        } elseif ($request->filled('image_url')) {
            $data['image'] = $request->input('image_url');
        }

        $story->update($data);
        return redirect('/admin/kelola_customer_stories')->with('success', 'Customer story berhasil diubah');
    }

    public function destroyCustomerStories($id)
    {
        $story = CustomerStory::findOrFail($id);
        $story->delete();
        return redirect('/admin/kelola_customer_stories')->with('success', 'Customer story berhasil dihapus');
    }
}
