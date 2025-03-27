<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list'  => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang'; // menu aktif

        // Ambil semua data kategori untuk dropdown filter
        $kategori = KategoriModel::all();

        return view('barang.index', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'kategori'   => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Mengambil data barang dalam bentuk JSON (untuk DataTables)
    public function list(Request $request)
    {
        // Select kolom yang ditampilkan di tabel list
        $barang = BarangModel::select(
            'barang_id',
            'kategori_id',
            'barang_kode',
            'barang_nama'
        )
        ->with('kategori'); // relasi ke tabel kategori

        // Filter data berdasarkan kategori_id
        if ($request->kategori_id) {
            $barang->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barang)
            ->addIndexColumn() // kolom DT_RowIndex
            ->addColumn('aksi', function ($brg) {
                // Tombol Detail, Edit, dan Hapus
                $btn = '<a href="'.url('/barang/' . $brg->barang_id).'"
                            class="btn btn-info btn-sm">Detail</a> ';

                $btn .= '<a href="'.url('/barang/' . $brg->barang_id . '/edit').'"
                            class="btn btn-warning btn-sm">Edit</a> ';

                $btn .= '<form class="d-inline-block" method="POST"
                            action="'.url('/barang/'.$brg->barang_id).'">'
                        . csrf_field()
                        . method_field('DELETE')
                        . '<button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">
                            Hapus
                          </button></form>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    // Menampilkan halaman form tambah barang
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list'  => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        // Ambil data kategori untuk select
        $kategori = KategoriModel::all();
        $activeMenu = 'barang';

        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'kategori'   => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data barang baru
    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'  => 'required|integer',
            'barang_kode'  => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama'  => 'required|string|max:100',
            'harga_jual'   => 'required|numeric',
            'harga_beli'   => 'required|numeric'
        ]);

        BarangModel::create([
            'kategori_id'  => $request->kategori_id,
            'barang_kode'  => $request->barang_kode,
            'barang_nama'  => $request->barang_nama,
            'harga_jual'   => $request->harga_jual,
            'harga_beli'   => $request->harga_beli
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    // Menampilkan detail barang (harga_jual dan harga_beli ikut ditampilkan)
    public function show(string $id)
    {
        // Gunakan with('kategori') agar bisa menampilkan info kategori di detail
        $barang = BarangModel::with('kategori')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list'  => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang'
        ];

        $activeMenu = 'barang';

        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'barang'     => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit barang
    public function edit(string $id)
    {
        $barang = BarangModel::find($id);

        // Ambil data kategori untuk select
        $kategori = KategoriModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list'  => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit barang'
        ];

        $activeMenu = 'barang';

        return view('barang.edit', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'barang'     => $barang,
            'kategori'   => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data barang
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_id'  => 'required|integer',
            'barang_kode'  => 'required|string|max:10|unique:m_barang,barang_kode,'.$id.',barang_id',
            'barang_nama'  => 'required|string|max:100',
            'harga_jual'   => 'required|numeric',
            'harga_beli'   => 'required|numeric'
        ]);

        $barang = BarangModel::find($id);
        if (!$barang) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        $barang->update([
            'kategori_id'  => $request->kategori_id,
            'barang_kode'  => $request->barang_kode,
            'barang_nama'  => $request->barang_nama,
            'harga_jual'   => $request->harga_jual,
            'harga_beli'   => $request->harga_beli
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    // Menghapus data barang
    public function destroy(string $id)
    {
        $check = BarangModel::find($id);
        if (!$check) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika ada constraint foreign key, dsb.
            return redirect('/barang')->with(
                'error',
                'Data barang gagal dihapus karena masih terdapat data lain yang terkait'
            );
        }
    }
}
