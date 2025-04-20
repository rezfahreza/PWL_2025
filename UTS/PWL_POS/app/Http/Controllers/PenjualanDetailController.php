<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenjualanDetailModel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\BarangModel;
use App\Models\PenjualanModel;

class PenjualanDetailController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Detail Penjualan',
            'list'  => ['Home', 'Detail Penjualan']
        ];

        $page = (object) [
            'title' => 'Daftar penjualan yang terdaftar dalam sistem'
        ];

        $detail = PenjualanDetailModel::all();

        $activeMenu = 'penjualan_detail'; // set menu yang sedang aktif

        return view('penjualan_detail.index', [
            'breadcrumb' => $breadcrumb,
            'detail'  => $detail,
            'page'       => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        // Select kolom yang ditampilkan di tabel list
        $detail = PenjualanDetailModel::select(
            'detail_id',
            'penjualan_id',
            'barang_id',
            'harga',
            'jumlah'
        )
        ->with(['barang', 'penjualan']);


        if ($request->penjualan_id) {
            $detail->where('penjualan_id', $request->penjualan_id);
        }
        
        if ($request->barang_id) {
            $detail->where('barang_id', $request->barang_id);
        }

        return DataTables::of($detail)
            ->addIndexColumn() // kolom DT_RowIndex
            ->addColumn('aksi', function ($detail) {
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
                $btn  = '<button onclick="modalAction(\''.url('/penjualan_detail/' . $detail->detail_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/penjualan_detail/' . $detail->detail_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/penjualan_detail/' . $detail->detail_id . '/delete_ajax').'\')"  class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create()
    {
        $breadcrumb = (object)[
            'title' => 'Tambah Detail Penjualan',
            'list' => ['Home', 'Detail Penjualan', 'Tambah']
        ];

        $page = (object)[
            'title' => 'Tambah detail penjualan'
        ];

        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.create', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penjualan_id' => 'required|numeric',
            'barang_id' => 'required|numeric',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric',
        ]);

        PenjualanDetailModel::create($request->all());

        return redirect('/penjualan_detail')->with('success', 'Detail penjualan berhasil disimpan');
    }

    public function show($id)
    {
        $detail = PenjualanDetailModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Detail Data Penjualan',
            'list' => ['Home', 'Detail Penjualan', 'Lihat']
        ];

        $page = (object)[
            'title' => 'Lihat detail penjualan'
        ];

        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.show', compact('breadcrumb', 'page', 'detail', 'activeMenu'));
    }

    public function edit($id)
    {
        $detail = PenjualanDetailModel::find($id);

        $breadcrumb = (object)[
            'title' => 'Edit Detail Penjualan',
            'list' => ['Home', 'Detail Penjualan', 'Edit']
        ];

        $page = (object)[
            'title' => 'Edit detail penjualan'
        ];

        $activeMenu = 'penjualan_detail';

        return view('penjualan_detail.edit', compact('breadcrumb', 'page', 'detail', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'penjualan_id' => 'required|numeric',
            'barang_id' => 'required|numeric',
            'harga' => 'required|numeric',
            'jumlah' => 'required|numeric',
        ]);

        PenjualanDetailModel::find($id)->update($request->all());

        return redirect('/penjualan_detail')->with('success', 'Detail penjualan berhasil diubah');
    }

    public function destroy($id)
    {
        $check = PenjualanDetailModel::find($id);

        if (!$check) {
            return redirect('/penjualan_detail')->with('error', 'Data tidak ditemukan');
        }

        try {
            PenjualanDetailModel::destroy($id);
            return redirect('/penjualan_detail')->with('success', 'Detail penjualan berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/penjualan_detail')->with('error', 'Gagal menghapus data karena masih terhubung dengan tabel lain');
        }
    }

    public function create_ajax()
    {
        $penjualan = PenjualanModel::all();
        $barang = BarangModel::all();
        return view('penjualan_detail.create_ajax', [
            'barang' => $barang,
            'penjualan' => $penjualan,
        ]);
    }

    public function show_ajax(string $id)
    {
        $detail = PenjualanDetailModel::with(['penjualan', 'barang'])->find($id);
        
        return view('penjualan_detail.show_ajax', ['detail' => $detail]);
    }

    public function store_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'    => ['required', 'integer', 'exists:m_barang,barang_id'],
                'penjualan_id'  => ['required', 'integer', 'exists:t_penjualan,penjualan_id'],
                'harga' => ['required', 'integer', 'min:1'],
                'jumlah'  => ['required', 'integer', 'min:1'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $detail = PenjualanDetailModel::find($id);
            if ($detail) {
                $detail->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data Penjualan Detail berhasil diupdate.',
                ]);
            }

            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan.',
            ]);
        }
    }

    public function edit_ajax(string $id)
    {
        $detail = PenjualanDetailModel::find($id);
        $penjualan = PenjualanModel::all();
        $barang = BarangModel::all();
        return view('penjualan_detail.edit_ajax', [
            'detail' => $detail,
            'penjualan' => $penjualan,
            'barang' => $barang,
        ]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id'    => ['required', 'integer', 'exists:m_barang,barang_id'],
                'penjualan_id'  => ['required', 'integer', 'exists:t_penjualan,penjualan_id'], // validasi supplier
                'harga' => ['required', 'integer', 'min:1'],
                'jumlah'  => ['required', 'integer', 'min:1'],
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi gagal.',
                    'msgField' => $validator->errors(),
                ]);
            }

            $detail = PenjualanDetailModel::find($id);
            if ($detail) {
                $detail->update($request->all());

                return response()->json([
                    'status'  => true,
                    'message' => 'Data Penjualan Detail berhasil diupdate.',
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
        $detail = PenjualanDetailModel::find($id);
 
        return view('penjualan_detail.confirm_ajax', ['detail' => $detail]);
    }

    public function delete_ajax(Request $request, $id)
    {
        // Mengecek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $detail = PenjualanDetailModel::find($id);
            if ($detail) {
                try {
                    $detail->delete();
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
        return view('penjualan_detail.import'); 
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_detail_penjualan' => ['required', 'mimes:xlsx', 'max:4096'],
            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            $file = $request->file('file_detail_penjualan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'penjualan_id' => $value['A'],
                            'barang_id' => $value['B'],
                            'harga' => $value['C'],
                            'jumlah' => $value['D'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    PenjualanDetailModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport',
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport',
                ]);
            }
        }

        return redirect('/');
    }
    
    public function export_excel(){
        // ambil data barang yang akan di export
        $barang = PenjualanDetailModel::with('barang')
                    ->select( 'penjualan_id', 'barang_id','harga','jumlah')
                    ->orderBy('detail_id')
                    ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'NO');
        $sheet->setCellValue('B1', 'ID Transaksi');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'Total ');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header

        $no = 1; // nomor data dimulai dari 1
        $baris = 2; // baris data dimulai dari baris ke 2
        foreach ($barang as $key => $value) {
            $totalHarga = $value->harga * $value->jumlah;
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->penjualan_id);
            $sheet->setCellValue('C' . $baris, $value->barang->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->harga);
            $sheet->setCellValue('E' . $baris, $value->jumlah);
            $sheet->setCellValue('F' . $baris, $totalHarga);
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Detail Penjualan'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx'); //Membuat “penulis” file Excel dalam format .xlsx
        $filename = 'Data Detail Penjualan_' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); // memberi tahu bahwa ini adalah file excel
        header('Content-Disposition: attachment;filename="' . $filename . '"'); //Memberi tau browser supaya file langsung di-download, bukan dibuka di browser.  
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1'); //Supaya browser tidak menyimpan versi lama dari file ini.
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); //Tanggal kadaluarsa file ini ditetapkan ke masa lalu → artinya file ini harus dianggap baru setiap saat.  
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // memberi tahu bahwa sekarang adaah terakhir modifikasi.
        header('Cache-Control: cache, must-revalidate'); // File ini bisa di-cache, tapi harus diperiksa dulu ke server apakah ada versi terbaru.
        header('Pragma: public'); //Boleh disimpan (public cache) di beberapa kasus, untuk dukung browser lama.

        $writer->save('php://output');
        exit;
        
    }

    public function export_pdf(){
        $barang = PenjualanDetailModel::with('barang')
                    ->select( 'penjualan_id', 'barang_id','harga','jumlah')
                    ->orderBy('detail_id')
                    ->get();

        $pdf = Pdf::loadView('penjualan_detail.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOptions(['isRemoteEnabled' => true]);
        $pdf->render();

        return $pdf->stream('Data Penjualan-Detail_' . date('Y-m-d H:i:s') . '.pdf');
    }
}
