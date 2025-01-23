<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='absensi'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Absensi"></x-navbars.navs.auth>
        <!-- End Navbar -->

        <div class="container-fluid">
            @if (session('sukses'))
                <div class="row">
                    <div class="alert alert-success text-white" role="alert" id="pesan_sukses">
                        <strong>Berhasil!</strong> {{ session('sukses') }}
                    </div>
                </div>
            @endif
            @if (Auth()->user()->role == 'admin')
                <div class="row">
                    <div class="col-12 p-0">
                        <a href="{{ route('pengaturanabsensi.index') }}" class="btn btn-primary w-100">Pengaturan
                            absensi</a>
                    </div>
                </div>
            @endif

            @if (Auth()->user()->role == 'pegawai')
                <div class="row">
                    @if ($hasil_cek_waktu && $hasil_cek_ip)
                        @if ($hasil_cek_double_absensi === false)
                            <a href="{{ route('absensi.create') }}" class="btn btn-primary mb-4 w-100"
                               data-bs-toggle="modal" data-bs-target="#modal_absensi">Absensi</a>
                        @else
                            <a class="btn btn-secondary mt-2 mb-0 w-100"" disabled>Absensi</a>
                            <p class="m-0"><small>Absensi tidak tersedia! anda sudah melakukan absensi hari
                                    ini!</small>
                            </p>
                        @endif
                    @else
                        <a class="btn btn-secondary mt-2 mb-0 w-100"" disabled>Absensi</a>
                        @if ($hasil_cek_waktu === false)
                            <p class="m-0"><small>Absensi tidak tersedia! waktu habis!</small></p>
                        @elseif($hasil_cek_ip === false)
                            <p class="m-0"><small>Absensi tidak tersedia! anda berada di luar jangkauan
                                    jaringan!</small>
                            </p>
                        @endif
                    @endif
                </div>
            @endif
            <div class="row">
                <div class="col-6 d-flex" style="gap: 10px">
                    <button class="filter-btn btn btn-info" data-filter="hari"
                            data-value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        Filter Hari Ini
                    </button>
                    <button class="filter-btn btn btn-info" data-filter="bulan"
                            data-value="{{ \Carbon\Carbon::now()->format('Y-m') }}">
                        Filter Bulan Ini
                    </button>
                    <button class="filter-btn btn btn-info" data-filter="tahun"
                            data-value="{{ \Carbon\Carbon::now()->year }}">
                        Filter Tahun Ini
                    </button>
                </div>
                <div class="col-6 d-flex justify-content-end">
                    <a href="#" class="btn btn-success download-pdf">Unduh laporan</a>

                </div>
            </div>
            <div class="row">
                <div class="col-12 p-0 mt-2">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0" id="absensi-table">
                                <thead class="text-center">
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                        No
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                        Nama
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                        Waktu absensi
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                        Keterangan
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="text-center" style="">
                                @php
                                    $no = 1;
                                @endphp
                                @forelse ($absensis as $absensi)
                                    <tr>
                                        <td>
                                            <p class="font-weight-normal mb-0">{{ $no }}</p>
                                            @php
                                                $no++;
                                            @endphp
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">{{ $absensi->user->name }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">{{ $absensi->created_at }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">
                                                {{ $absensi->status_absensi }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="font-weight-normal">
                                            Tidak ada data absensi !
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            {{-- {{ $absensis->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal_absensi" tabindex="-1" role="dialog" aria-labelledby="modal_absensiLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('absensi.store') }}" method="post">
                        <div class="modal-body">
                            @csrf
                            <!-- Tab Menu -->
                            <ul class="nav nav-tabs" id="absensiTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="masuk-tab" data-bs-toggle="tab" data-bs-target="#masuk" type="button" role="tab" aria-controls="masuk" aria-selected="true">
                                        Absensi Masuk
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="pulang-tab" data-bs-toggle="tab" data-bs-target="#pulang" type="button" role="tab" aria-controls="pulang" aria-selected="false">
                                        Absensi Pulang
                                    </button>
                                </li>
                            </ul>

                            <!-- Tab Content -->
                            <div class="tab-content" id="absensiTabContent">
                                <!-- Form Absensi Masuk -->
                                <div class="tab-pane fade show active" id="masuk" role="tabpanel" aria-labelledby="masuk-tab">
                                    <h6 class="mt-3">Pilih Status Masuk</h6>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_absensi_masuk" id="hadir" value="hadir">
                                        <label class="form-check-label" for="hadir">Hadir</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_absensi_masuk" id="izin" value="izin">
                                        <label class="form-check-label" for="izin">Izin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_absensi_masuk" id="alpha" value="alpha">
                                        <label class="form-check-label" for="alpha">Alpha</label>
                                    </div>
                                </div>

                                <!-- Form Absensi Pulang -->
                                <div class="tab-pane fade" id="pulang" role="tabpanel" aria-labelledby="pulang-tab">
                                    <h6 class="mt-3">Absensi Pulang</h6>
                                    <p class="text-muted">Anda hanya dapat melakukan absensi pulang jika telah melakukan absensi masuk dengan status "Hadir".</p>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_absensi_pulang" id="pulang" value="pulang">
                                        <label class="form-check-label" for="pulang">Pulang</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal -->
        {{--<div class="modal fade" id="modal_absensi" tabindex="-1" role="dialog" aria-labelledby="modal_absensiLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-normal" id="exampleModalLabel">Absensi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('absensi.store') }}" method="post">
                        <div class="modal-body">
                            @csrf
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status_absensi_masuk" id="status_absensi_masuk"
                                       value="hadir">
                                <label class="custom-control-label" for="status_absensi_masuk">Hadir</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="status_absensi_keluar" id="status_absensi_keluar" value="pulang">
                                <label class="custom-control-label" for="status_absensi_keluar">
                                    Pulang
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn bg-gradient-secondary"
                                data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>--}}
        <script>
            $(document).ready(function() {
                $("#pesan_sukses").delay(3000).fadeOut("slow");
                let currentFilter = null;

                $('.filter-btn').on('click', function() {
                    const filter = $(this).data('filter');
                    const value = $(this).data('value');

                    // Simpan filter untuk digunakan saat unduh
                    currentFilter = {
                        filter,
                        value
                    };

                    $.ajax({
                        url: '/absensi',
                        type: 'GET',
                        data: {
                            filter,
                            value
                        },
                        success: function(response) {
                            const tbody = $('#absensi-table tbody');
                            tbody.empty();

                            if (response.data.length > 0) {
                                let no = 1;
                                response.data.forEach(item => {
                                    const formattedDate = moment(item.created_at).format(
                                        'DD-MM-YYYY HH:mm:ss');
                                    const userName = item.user ? item.user.name :
                                        'Nama tidak tersedia';
                                    const row = `
                            <tr>
                                <td>${no}</td>
                                <td>${userName}</td>
                                <td>${formattedDate}</td>
                                <td>${item.status_absensi}</td>
                            </tr>`;
                                    tbody.append(row);
                                    no++;
                                });
                            } else {
                                tbody.append(
                                    '<tr><td colspan="4">Tidak ada data absensi!</td></tr>');
                            }
                        },
                        error: function(err) {
                            console.error('Gagal memuat data:', err);
                        }
                    });
                });

                $('.download-pdf').on('click', function(e) {
                    e.preventDefault();
                    let url = "{{ route('absensi.download-pdf') }}";
                    if (currentFilter) {
                        url += `?filter=${currentFilter.filter}&value=${currentFilter.value}`;
                    }
                    window.location.href = url;
                });


            })
        </script>
    </main>
</x-layout>
