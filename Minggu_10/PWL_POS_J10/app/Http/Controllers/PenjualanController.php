<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserModel;
use App\Models\PenjualanModel;
use App\Models\BarangModel;
use App\Models\PenjualanDetailModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Penjualan',
            'list'  => ['Home', 'Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $user = UserModel::all();

        $activeMenu = 'penjualan'; // set menu yang sedang aktif

        return view('penjualan.index', [
            'breadcrumb' => $breadcrumb,
            'user'  => $user,
            'page'       => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        // Select kolom yang ditampilkan di tabel list
        $penjualan = PenjualanModel::select(
            'penjualan_id',
            'user_id',
            'pembeli',
            'penjualan_kode',
            'penjualan_tanggal'
        )
        ->with('user');


        if ($request->user_id) {
            $penjualan->where('user_id', $request->user_id);
        }

        return DataTables::of($penjualan)
            ->addIndexColumn() // kolom DT_RowIndex
            ->addColumn('aksi', function ($penjualan) {
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
                $btn  = '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/penjualan/' . $penjualan->penjualan_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Penjualan',
            'list'  => ['Home', 'Penjualan', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah penjualan baru'
        ];

        $activeMenu = 'penjualan';
        $user = UserModel::all();

        return view('penjualan.create', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'user'       => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|integer',
            'pembeli'          => 'required|string|max:100',
            'penjualan_kode'   => 'required|string|max:20|unique:t_penjualan,penjualan_kode',
            'penjualan_tanggal'=> 'required|date'
        ]);

        PenjualanModel::create([
            'user_id'          => $request->user_id,
            'pembeli'          => $request->pembeli,
            'penjualan_kode'   => $request->penjualan_kode,
            'penjualan_tanggal'=> $request->penjualan_tanggal
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil disimpan');
    }

    public function show(string $id)
    {
        $penjualan = PenjualanModel::with('user')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Penjualan',
            'list'  => ['Home', 'Penjualan', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.show', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'penjualan'       => $penjualan,
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit(string $id)
    {
        $penjualan = PenjualanModel::find($id);

        $user = UserModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Penjualan',
            'list'  => ['Home', 'Penjualan', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit penjualan'
        ];

        $activeMenu = 'penjualan';

        return view('penjualan.edit', [
            'breadcrumb' => $breadcrumb,
            'page'       => $page,
            'penjualan'  => $penjualan,
            'user'       => $user,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'user_id'          => 'required|integer',
            'pembeli'          => 'required|string|max:100',
            'penjualan_kode'   => 'required|string|max:20|unique:t_penjualan,penjualan_kode,'.$id.',penjualan_id',
            'penjualan_tanggal'=> 'required|date'
        ]);

        $penjualan = PenjualanModel::find($id);
        if (!$penjualan) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        $penjualan->update([
            'user_id'          => $request->user_id,
            'pembeli'          => $request->pembeli,
            'penjualan_kode'   => $request->penjualan_kode,
            'penjualan_tanggal'=> $request->penjualan_tanggal
        ]);

        return redirect('/penjualan')->with('success', 'Data penjualan berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = PenjualanModel::find($id);
        if (!$check) {
            return redirect('/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        try {
            PenjualanModel::destroy($id);
            return redirect('/penjualan')->with('success', 'Data penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            // Jika ada constraint foreign key, dsb.
            return redirect('/penjualan')->with(
                'error',
                'Data penjualan gagal dihapus karena masih terdapat data lain yang terkait'
            );
        }
    }

    public function create_ajax()
    {
        return view('penjualan.create_ajax');
    }

    public function show_ajax(string $id)
     {
         $penjualan = PenjualanModel::with('user')->find($id);
        
         return view('penjualan.show_ajax', ['penjualan' => $penjualan]);
     }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $rules = [
                'pembeli'           => ['required', 'string', 'max:100'],
                'penjualan_kode'    => ['required', 'string', 'max:20', 'unique:t_penjualan,penjualan_kode'],
                'penjualan_tanggal' => ['required', 'date'],
            ];
            


            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $data = $request->all();
            $data['user_id'] = auth()->id();

            PenjualanModel::create($data);



            return response()->json([
                'status'  => true,
                'message' => 'Data stok berhasil disimpan.',
            ]);
        }
    }

    public function edit_ajax(string $id)
    {
        $penjualan = PenjualanModel::find($id);
        $user = UserModel::select('user_id', 'nama')->get();

        return view('penjualan.edit_ajax', [
            'penjualan' => $penjualan,
            'user' => $user,
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'penjualan_kode'    => ['required', 'string','max:10'],
                'pembeli'  => ['required', 'string'], 
                'penjualan_tanggal' => ['required', 'date'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $penjualan = PenjualanModel::find($id);
            if ($penjualan) {
                $penjualan->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data Penjualan berhasil diupdate.',
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }
    }

    public function confirm_ajax(string $id)
     {
         $penjualan = PenjualanModel::with('user')->find($id);
 
         return view('penjualan.confirm_ajax', ['penjualan' => $penjualan]);
     }

     public function delete_ajax(Request $request, $id)
     {
         // Mengecek apakah request dari ajax
         if ($request->ajax() || $request->wantsJson()) {
             $penjualan = PenjualanModel::find($id);
             if ($penjualan) {
                 try {
                     $penjualan->delete();
                     return response()->json([
                         'status' => true,
                         'message' => 'Data berhasil dihapus'
                     ]);
                 } catch (\Illuminate\Database\QueryException $e) {
                     return response()->json([
                         'status' => false,
                         'message' => 'Data tidak bisa dihapus'
                     ]);
                 }
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         return redirect('/');
     }

    public function import() 
    { 
        return view('penjualan.import'); 
    }

    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_penjualan' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
 
            $file = $request->file('file_penjualan');  // ambil file dari request 
 
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
                            'user_id'          => $value['A'],
                            'pembeli'          => $value['B'],
                            'penjualan_kode'   => $value['C'],
                            'penjualan_tanggal'=> $tanggal,
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    PenjualanModel::insertOrIgnore($insert);    
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

    public function export_excel(){
        // ambil data barang yang akan di export
        $barang = PenjualanModel::with('user', 'detail.barang') // tambahkan barang di dalam detail
                    ->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
                    ->orderBy('penjualan_id')
                    ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Penjual');
        $sheet->setCellValue('C1', 'Pembeli');
        $sheet->setCellValue('D1', 'Harga');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'Total');
        $sheet->setCellValue('G1', 'Kode Transaksi');
        $sheet->setCellValue('H1', 'Tanggal Transaksi');

        $sheet->getStyle('A1:H1')->getFont()->setBold(true); // bold header

        $no = 1;
        $baris = 2;
        foreach ($barang as $key => $value) {
            foreach ($value->detail as $d) { // LOOP detail
                $sheet->setCellValue('A' . $baris, $no);
                $sheet->setCellValue('B' . $baris, $value->user->username);
                $sheet->setCellValue('C' . $baris, $value->pembeli);
                $sheet->setCellValue('D' . $baris, $d->harga); 
                $sheet->setCellValue('E' . $baris, $d->jumlah); 
                $sheet->setCellValue('F' . $baris, $d->harga * $d->jumlah);
                $sheet->setCellValue('G' . $baris, $value->penjualan_kode);
                $sheet->setCellValue('H' . $baris, $value->penjualan_tanggal);

                $baris++;
                $no++;
            }
        }


        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Penjualan'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Penjualan_' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $barang = PenjualanModel::with('user', 'penjualanDetail.barang') // tambahkan barang di dalam detail
                    ->select('penjualan_id', 'user_id', 'pembeli', 'penjualan_kode', 'penjualan_tanggal')
                    ->orderBy('penjualan_id')
                    ->get();

        $pdf = Pdf::loadView('penjualan.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isRemoteEnabled' => true]);
        $pdf->render();

        return $pdf->stream('Data Penjualan_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
