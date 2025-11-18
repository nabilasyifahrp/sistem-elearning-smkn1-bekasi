@extends('partials.layouts-guru')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0" style="color: #256343;">Manajemen Absensi</h1>
            <p class="text-muted">Kelola absensi siswa dan lihat rekap kehadiran</p>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-12">
            <ul class="nav nav-tabs" id="attendanceTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="absensi-tab" data-bs-toggle="tab" data-bs-target="#absensi" type="button" style="color: #256343;">
                        <i class="bi bi-clipboard-check"></i> Absensi Hari Ini
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="rekap-tab" data-bs-toggle="tab" data-bs-target="#rekap" type="button" style="color: #256343;">
                        <i class="bi bi-bar-chart"></i> Rekap Absensi
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="tab-content" id="attendanceTabContent">
        <div class="tab-pane fade show active" id="absensi" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Absensi - Tanggal 17 November 2024</h5>
                        </div>
                        <div class="col-md-6 text-end">
                            <select class="form-select form-select-sm" style="width: auto; display: inline-block;">
                                <option>Matematika - X IPA 1</option>
                                <option>Fisika - X IPA 1</option>
                                <option>Kimia - X IPA 2</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>001</td>
                                    <td>Ahmad Hidayat</td>
                                    <td><span class="badge" style="background-color: #28a745;">Hadir</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" title="Ubah Status">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>002</td>
                                    <td>Siti Nurhaliza</td>
                                    <td><span class="badge" style="background-color: #ffc107;">Izin (Disetujui Wali Kelas)</span></td>
                                    <td>Izin keluarga</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" title="Ubah Status">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>003</td>
                                    <td>Budi Santoso</td>
                                    <td><span class="badge" style="background-color: #ff9800;">Sakit</span></td>
                                    <td>Demam tinggi</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" title="Ubah Status">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>004</td>
                                    <td>Rini Wijaya</td>
                                    <td><span class="badge" style="background-color: #dc3545;">Alfa</span></td>
                                    <td>Tanpa keterangan</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" title="Ubah Status">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>005</td>
                                    <td>Doni Pratama</td>
                                    <td><span class="badge" style="background-color: #28a745;">Hadir</span></td>
                                    <td>-</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary" title="Ubah Status">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button class="btn" style="background-color: #256343; color: white;">
                                <i class="bi bi-save"></i> Simpan Absensi
                            </button>
                            <button class="btn btn-outline-secondary">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="rekap" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-header" style="background-color: #256343; color: white;">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h5 class="mb-0">Rekap Absensi</h5>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-1" style="color: white;">Kelas</label>
                            <select class="form-select form-select-sm">
                                <option>Matematika - X IPA 1</option>
                                <option>Fisika - X IPA 1</option>
                                <option>Kimia - X IPA 2</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-1" style="color: white;">Periode</label>
                            <select class="form-select form-select-sm">
                                <option>November 2024</option>
                                <option>Oktober 2024</option>
                                <option>September 2024</option>
                            </select>
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-light btn-sm">
                                <i class="bi bi-download"></i> Export PDF
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead style="background-color: #f0f0f0;">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th style="text-align: center;"><span class="badge" style="background-color: #28a745;">Hadir</span></th>
                                    <th style="text-align: center;"><span class="badge" style="background-color: #ffc107;">Izin</span></th>
                                    <th style="text-align: center;"><span class="badge" style="background-color: #ff9800;">Sakit</span></th>
                                    <th style="text-align: center;"><span class="badge" style="background-color: #dc3545;">Alfa</span></th>
                                    <th style="text-align: center;">Total</th>
                                    <th style="text-align: center;">Persentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>001</td>
                                    <td>Ahmad Hidayat</td>
                                    <td style="text-align: center;">18</td>
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;">0</td>
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;">20</td>
                                    <td style="text-align: center;"><span style="color: #28a745; font-weight: bold;">90%</span></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>002</td>
                                    <td>Siti Nurhaliza</td>
                                    <td style="text-align: center;">17</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;">0</td>
                                    <td style="text-align: center;">20</td>
                                    <td style="text-align: center;"><span style="color: #ffc107; font-weight: bold;">85%</span></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>003</td>
                                    <td>Budi Santoso</td>
                                    <td style="text-align: center;">16</td>
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;">20</td>
                                    <td style="text-align: center;"><span style="color: #ff9800; font-weight: bold;">80%</span></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>004</td>
                                    <td>Rini Wijaya</td>
                                    <td style="text-align: center;">15</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;">2</td>
                                    <td style="text-align: center;">20</td>
                                    <td style="text-align: center;"><span style="color: #dc3545; font-weight: bold;">75%</span></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>005</td>
                                    <td>Doni Pratama</td>
                                    <td style="text-align: center;">19</td>
                                    <td style="text-align: center;">0</td>
                                    <td style="text-align: center;">0</td>
                                    <td style="text-align: center;">1</td>
                                    <td style="text-align: center;">20</td>
                                    <td style="text-align: center;"><span style="color: #28a745; font-weight: bold;">95%</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection