<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserModel;
use App\Models\StokModel;
use App\Models\BarangModel;
use App\Models\SupplierModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Stok Barang',
            'list'  => ['Home', 'Stok Barang']
        ];

        $page = (object) [
            'title' => 'Daftar stok barang yang terdaftar dalam sistem'
        ];

        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $user = UserModel::select('user_id', 'username')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();

        $activeMenu = 'stok'; // set menu yang sedang aktif

        return view('stok.index', [
            'breadcrumb' => $breadcrumb, 
            'barang'     => $barang, 
            'user'       => $user, 
            'supplier'   => $supplier, 
            'page'       => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        // Select kolom yang ditampilkan di tabel list
        $stok = StokModel::select(
            'stok_id',
            'barang_id',
            'supplier_id',
            'user_id',
            'stok_tanggal',
            'stok_jumlah',
        )
        ->with(['barang', 'supplier', 'user']);

        if ($request->barang_id) {
            $stok->where('barang_id', $request->barang_id);
        }

        if ($request->supplier_id) {
            $stok->where('supplier_id', $request->supplier_id);
        }

        if ($request->user_id) {
            $stok->where('user_id', $request->user_id);
        }

        return DataTables::of($stok)
            ->addIndexColumn() // kolom DT_RowIndex
            ->addColumn('aksi', function ($stok) {
                // Tombol Detail, Edit, dan Hapus
                /*$btn = '<a href="'.url('/barang/' . $brg->barang_id).'"
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
                          </button></form>';*/
                $btn  = '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/stok/' . $stok->stok_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Stok',
            'list'  => ['Home', 'Stok', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah stok baru'
        ];

        $activeMenu = 'stok';
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
        $user = UserModel::all();

        return view('stok.create', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'barang'     => $barang,
            'supplier'   => $supplier,
            'user'       => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id'     => 'required|integer',
            'supplier_id'   => 'required|integer',
            'user_id'       => 'required|integer',
            'stok_tanggal'  => 'required|date',
            'stok_jumlah'   => 'required|integer|min:1'
        ]);

        StokModel::create([
            'barang_id'     => $request->barang_id,
            'supplier_id'   => $request->supplier_id,
            'user_id'       => $request->user_id,
            'stok_tanggal'  => $request->stok_tanggal,
            'stok_jumlah'   => $request->stok_jumlah
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil disimpan');
    }

    public function show(string $id)
    {
        $stok = StokModel::with('barang', 'supplier', 'user')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Stok',
            'list'  => ['Home', 'Stok', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Stok'
        ];

        $activeMenu = 'stok';

        return view('stok.show', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'stok'       => $stok,
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $stok = StokModel::find($id);

        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
        $user = UserModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Stok',
            'list'  => ['Home', 'Stok', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit stok'
        ];

        $activeMenu = 'stok';

        return view('stok.edit', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'stok'       => $stok,
            'barang'     => $barang,
            'supplier'   => $supplier,
            'user'       => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'barang_id'     => 'required|integer',
            'supplier_id'   => 'required|integer',
            'user_id'       => 'required|integer',
            'stok_tanggal'  => 'required|date',
            'stok_jumlah'   => 'required|integer|min:1'
        ]);

        $stok = StokModel::find($id);
        if (!$stok) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        $stok->update([
            'barang_id'     => $request->barang_id,
            'supplier_id'   => $request->supplier_id,
            'user_id'       => $request->user_id,
            'stok_tanggal'  => $request->stok_tanggal,
            'stok_jumlah'   => $request->stok_jumlah
        ]);

        return redirect('/stok')->with('success', 'Data stok berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = StokModel::find($id);
        if (!$check) {
            return redirect('/stok')->with('error', 'Data stok tidak ditemukan');
        }

        try {
            StokModel::destroy($id);
            return redirect('/stok')->with('success', 'Data stok berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika ada constraint foreign key, dsb.
            return redirect('/stok')->with(
                'error',
                'Data stok gagal dihapus karena masih terdapat data lain yang terkait'
            );
        }
    }

    public function create_ajax()
    {
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'username')->get();

        return view('stok.create_ajax', [
            'barang' => $barang,
            'supplier' => $supplier,
            'user' => $user,
        ]);
    }

    public function show_ajax(string $id){
        $stok = StokModel::with(['user', 'barang', 'supplier'])->find($id);

        return view('stok.show_ajax', ['stok' => $stok]);
    }

    public function store_ajax(Request $request){
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'     => 'required|integer|exists:m_barang,barang_id',
                'supplier_id'   => 'required|integer|exists:m_supplier,supplier_id',
                'user_id'       => 'required|integer|exists:m_user,user_id',
                'stok_tanggal'  => 'required|date',
                'stok_jumlah'   => 'required|integer|min:1'
            ];            

            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);

            if($validator->fails()){
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            StokModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan'
            ]);

            redirect('/');
        }
    }

    //Menampilkan halaman form edit barang ajax
    public function edit_ajax(string $id)
    {
        $stok = StokModel::find($id);
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        $user = UserModel::select('user_id', 'username')->get();

        return view('stok.edit_ajax', [
            'stok'     => $stok,
            'barang'   => $barang, 
            'supplier' => $supplier, 
            'user'     => $user
        ]);
    }

    public function update_ajax(Request $request, $id){ 
        // cek apakah request dari ajax 
        if ($request->ajax() || $request->wantsJson()) { 
            $rules = [ 
                'barang_id'     => 'required|integer|exists:m_barang,barang_id',
                'supplier_id'   => 'required|integer|exists:m_supplier,supplier_id',
                'user_id'       => 'required|integer|exists:m_user,user_id',
                'stok_tanggal'  => 'required|date',
                'stok_jumlah'   => 'required|integer|min:1'
            ];

            // use Illuminate\Support\Facades\Validator; 
            $validator = Validator::make($request->all(), $rules); 
 
            if ($validator->fails()) { 
                return response()->json([ 
                    'status'   => false,    // respon json, true: berhasil, false: gagal 
                    'message'  => 'Validasi gagal.', 
                    'msgField' => $validator->errors()  // menunjukkan field mana yang error 
                ]); 
            } 
 
            $check = StokModel::find($id); 
            if ($check) { 
                $check->update($request->all()); 
                return response()->json([ 
                    'status'  => true, 
                    'message' => 'Data berhasil diupdate' 
                ]); 
            } else{ 
                return response()->json([ 
                    'status'  => false, 
                    'message' => 'Data tidak ditemukan' 
                ]); 
            } 
        } 
        return redirect('/'); 
    }

    public function confirm_ajax(string $id){
        $stok = StokModel::find($id);

        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                $stok->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            } 
        }
    }

    public function import() 
    { 
        return view('stok.import'); 
    }

    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_stok' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
 
            $file = $request->file('file_stok');  // ambil file dari request 
 
            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 
 
            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 
 
            $insert = []; 
            if(count($data) > 1){ // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati 

                        $tanggal = $value['D'];
                        if(is_numeric($tanggal)) {
                        $tanggal = Date::excelToDateTimeObject($tanggal)->format('Y-m-d H:i:s');
                        }

                        $insert[] = [ 
                            'barang_id'     => $value['A'],
                            'supplier_id'   => $value['B'],
                            'user_id'       => $value['C'],
                            'stok_tanggal'  => $tanggal,
                            'stok_jumlah'   => $value['E'],
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    StokModel::insertOrIgnore($insert);    
                } 
 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            }else{ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Tidak ada data yang diimport' 
                ]); 
            } 
        } 
        return redirect('/'); 
    }

    public function export_excel()
    {
        $stok = StokModel::select(
            'barang_id',
            'user_id',
            'supplier_id',
            'stok_tanggal',
            'stok_jumlah'
        )
        ->orderBy('stok_id')
        ->with('barang', 'supplier', 'user')
        ->get();
 
        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); //ambil sheet aktif
 
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Barang');
        $sheet->setCellValue('C1', 'Nama Supplier');
        $sheet->setCellValue('D1', 'Nama User');
        $sheet->setCellValue('E1', 'Tanggal Stok');
        $sheet->setCellValue('F1', 'Jumlah Stok');
 
        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // Set header bold
 
        $no = 1; //Nomor value dimulai dari 1
        $baris = 2; //Baris value dimulai dari 2
        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('C' . $baris, $value->supplier->supplier_nama);
            $sheet->setCellValue('D' . $baris, $value->user->username);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('F' . $baris, $value->stok_jumlah);
            $no++;
            $baris++;
        }
 
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); //set auto size untuk kolom
        }
 
        $sheet->setTitle('Data Stok'); //set judul sheet
        $writer = IOFactory ::createWriter($spreadsheet, 'Xlsx'); //set writer
        $filename = 'Data_Stok_' . date('Y-m-d_H-i-s') . '.xlsx'; //set nama file
 
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');
 
        $writer->save('php://output'); //simpan file ke output
        exit; //keluar dari scriptA
    }

    public function export_pdf()
    {
        $stok = StokModel::select(
            'barang_id',
            'user_id',
            'supplier_id',
            'stok_tanggal',
            'stok_jumlah'
        )
        ->orderBy('barang_id')
        ->orderBy('stok_tanggal')
        ->with(['barang', 'user', 'supplier'])
        ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = PDF::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('A4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render(); // render pdf

        return $pdf->stream('Data Stock Barang '.date('Y-m-d H-i-s').'.pdf');
    }
}
