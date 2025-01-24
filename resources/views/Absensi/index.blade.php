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
            <!-- Tombol untuk absensi -->
            <button class="btn btn-primary mb-4 w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#modal_absensi"
                    @if($disableAll) disabled @endif>
                Absensi
            </button>

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
                                        <button class="nav-link @if($disableMasuk) disabled @endif"
                                                id="masuk-tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#masuk"
                                                type="button"
                                                role="tab"
                                                aria-controls="masuk"
                                                aria-selected="true">
                                            Absensi Masuk
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if($disablePulang) disabled @endif"
                                                id="pulang-tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#pulang"
                                                type="button"
                                                role="tab"
                                                aria-controls="pulang"
                                                aria-selected="false">
                                            Absensi Pulang
                                        </button>
                                    </li>
                                </ul>


                                <!-- Tab Content -->
                                <div class="tab-content" id="absensiTabContent">
                                    <!-- Form Absensi Masuk -->
                                    <div class="tab-pane fade show active" id="masuk" role="tabpanel"
                                         aria-labelledby="masuk-tab">
                                        <h6 class="mt-3">Pilih Status Masuk</h6>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_absensi_masuk"
                                                   id="hadir" value="hadir">
                                            <label class="form-check-label" for="hadir">Hadir</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_absensi_masuk"
                                                   id="izin" value="izin">
                                            <label class="form-check-label" for="izin">Izin</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_absensi_masuk"
                                                   id="alpha" value="alpha">
                                            <label class="form-check-label" for="alpha">Alpha</label>
                                        </div>
                                    </div>

                                    <!-- Form Absensi Pulang -->
                                    <div class="tab-pane fade" id="pulang" role="tabpanel" aria-labelledby="pulang-tab">
                                        <h6 class="mt-3">Absensi Pulang</h6>
                                        <p class="text-muted">Anda hanya dapat melakukan absensi pulang jika telah
                                            melakukan
                                            absensi masuk dengan status "Hadir".</p>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="status_absensi_pulang"
                                                   id="pulang" value="pulang">
                                            <label class="form-check-label" for="pulang">Pulang</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Tutup
                                </button>
                                <button type="submit" class="btn bg-gradient-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
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
                                        Absensi Masuk
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                        Waktu Masuk
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                        Absensi Pulang
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">
                                        Waktu Pulang
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
                                            <p class="font-weight-normal mb-0">{{ $absensi->status_absensi_masuk }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">
                                                {{ $absensi->waktu_masuk }}</p>
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">{{ $absensi->status_absensi_pulang }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="font-weight-normal mb-0">
                                                {{ $absensi->waktu_pulang }}</p>
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
        <script>
            $(document).ready(function () {
                // Pengecekan status dari backend untuk menentukan tab aktif
                const disableMasuk = @json($disableMasuk);
                const disablePulang = @json($disablePulang);

                // Jika Absensi Masuk dinonaktifkan, langsung alihkan ke tab Pulang
                if (disableMasuk) {
                    $('#pulang-tab').tab('show'); // Aktifkan tab Pulang
                }

                // Event listener untuk klik pada tombol tab Absensi Masuk
                $('#masuk-tab').on('click', function (e) {
                    if ($(this).hasClass('disabled')) {
                        e.preventDefault(); // Batalkan default behavior
                        $('#pulang-tab').click(); // Alihkan ke tab Pulang
                    }
                });

                // Event listener untuk klik pada tombol tab Absensi Pulang
                $('#pulang-tab').on('click', function (e) {
                    if ($(this).hasClass('disabled')) {
                        e.preventDefault(); // Batalkan default behavior
                    }
                });

                $("#pesan_sukses").delay(3000).fadeOut("slow");
                let currentFilter = null;

                $('.filter-btn').on('click', function () {
                    const filter = $(this).data('filter');
                    const value = $(this).data('value');

                    // Simpan filter untuk digunakan saat unduh
                    currentFilter = {filter, value};

                    $.ajax({
                        url: '/absensi',
                        type: 'GET',
                        data: {filter, value},
                        success: function (response) {
                            const tbody = $('#absensi-table tbody');
                            tbody.empty();

                            if (response.data.length > 0) {
                                let no = 1;
                                response.data.forEach(item => {
                                    const row = `
                        <tr>
                            <td>${no}</td>
                            <td>${item.user_name}</td>
                            <td>${item.status_absensi_masuk}</td>
                            <td>${item.waktu_masuk}</td>
                            <td>${item.status_absensi_pulang}</td>
                            <td>${item.waktu_pulang}</td>
                        </tr>`;
                                    tbody.append(row);
                                    no++;
                                });
                            } else {
                                tbody.append(
                                    '<tr><td colspan="6">Tidak ada data absensi!</td></tr>'
                                );
                            }
                        },
                        error: function (err) {
                            console.error('Gagal memuat data:', err);
                        }
                    });
                });


                $('.download-pdf').on('click', function (e) {
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
