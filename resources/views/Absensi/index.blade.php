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
                <a href="{{ route('pengaturanabsensi.index') }}" class="btn btn-primary w-100">Pengaturan
                    absensi</a>
            @endif
            <!-- Tombol untuk absensi -->
            <button class="btn btn-primary mb-4 w-100"
                    data-bs-toggle="modal"
                    data-bs-target="#modal_absensi"
                    >
                Absensi
            </button>
            <div class="row">
                <div class="col d-flex" style="gap: 10px">
                    <!-- Button Filter Tanggal -->
                    <button class="filter-btn btn btn-info" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        Filter Tanggal
                    </button>
                    <ul class="dropdown-menu">
                        {{-- Date Picker filter tanggal --}}
                        <div class="mx-3 my-2">
                            <div class="container">
                                <div class="mb-2">
                                    <label for="filterDate" class="form-label m-0">Pilih Tanggal</label>
                                    <input type="date" id="filterDate" class="form-control"
                                           value="{{ request()->input('date', \Carbon\Carbon::now()->format('Y-m-d')) }}">
                                </div>
                                <button class="btn btn-outline-info m-0 w-100" id="applyDateFilter">Terapkan</button>
                            </div>
                        </div>
                    </ul>
                    <!-- Button Filter Bulan -->
                    <button class="filter-btn btn btn-info" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        Filter Bulanan
                    </button>
                    <ul class="dropdown-menu">
                        {{-- Date Picker filter bulan --}}
                        <div class="mx-3 my-2">
                            <div class="container">
                                <div class="mb-2">
                                    <label for="filterMonth" class="form-label m-0">Pilih Bulan</label>
                                    <input type="month" id="filterMonth" class="form-control"
                                           value="{{ request()->input('month', \Carbon\Carbon::now()->format('Y-m')) }}">
                                </div>
                                <button class="btn btn-outline-info m-0 w-100" id="applyMonthFilter">Terapkan</button>
                            </div>
                        </div>
                    </ul>
                    <!-- Button Filter Tahunan -->
                    <button class="filter-btn btn btn-info" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        Filter Tahun
                    </button>
                    <ul class="dropdown-menu">
                        {{-- Date Picker filter tanggal --}}
                        <div class="mx-3 my-2">
                            <div class="container">
                                <div class="mb-2">
                                    <label for="filterYear" class="form-label m-0">Pilih Tahun</label>
                                    <input type="number" id="filterYear" class="form-control"
                                           value="{{ request()->input('year', \Carbon\Carbon::now()->format('Y')) }}">
                                </div>
                                <button class="btn btn-outline-info m-0 w-100" id="applyYearFilter">Terapkan</button>
                            </div>
                        </div>
                    </ul>
                </div>
                <div class="col d-flex justify-content-end">
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
                                        <td colspan="6" class="font-weight-normal">
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

        {{-- Content Table --}}
        {{-- Model Form Absensi --}}
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
                                    <button class="nav-link"
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
                                    <button class="nav-link"
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
                                               id="hadir" value="Hadir">
                                        <label class="form-check-label" for="hadir">Hadir</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_absensi_masuk"
                                               id="izin" value="Izin">
                                        <label class="form-check-label" for="izin">Izin</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="status_absensi_masuk"
                                               id="alpha" value="Alpha">
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
                                               id="pulang" value="Pulang">
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
        <script>
            $(document).ready(function () {
                let currentFilter = {}; // Penyimpanan filter

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

                // Fungsi Filter Tanggal
                $('#applyDateFilter').on('click', function () {
                    var filterDate = $('#filterDate').val();
                    console.log('Tanggal Mulai:', filterDate);  // Periksa apakah tanggal yang dikirim sudah sesuai

                    if (filterDate) {
                        currentFilter = {filter: 'date', value: filterDate };
                    }

                    $.ajax({
                        url: '/absensi',  // Pastikan URL sesuai dengan route yang benar
                        type: 'GET',
                        data: {
                            date: filterDate,  // Kirimkan tanggal dalam format YYYY-MM-DD
                        },
                        success: function (response) {
                            updateTable(response.data)
                        },
                        error: function (xhr, status, error) {
                            // Menangani error jika response status bukan 2xx
                            var errorMessage = xhr.responseJSON ? xhr.responseJSON.error : 'Terjadi kesalahan';
                            alert('Error: ' + errorMessage);  // Tampilkan pesan error ke pengguna
                        }
                    });
                });

                // Fungsi filter bulan dan tahun
                $('#applyMonthFilter').on('click', function () {
                    var filterMonth = $('#filterMonth').val();
                    console.log(`Bulan ${filterMonth}`);

                    if (filterMonth) {
                        currentFilter = {filter: 'month', value: filterMonth };
                    }

                    $.ajax({
                        url: '/absensi',
                        type: 'GET',
                        data: {
                            month: filterMonth,
                        },
                        success: function (response) {
                            console.log(response);
                            updateTable(response.data);
                        }, error: function (xhr, status, error) {
                            // Menangani error jika response status bukan 2xx
                            var errorMessage = xhr.responseJSON ? xhr.responseJSON.error : 'Terjadi kesalahan';
                            alert('Error: ' + errorMessage);  // Tampilkan pesan error ke pengguna
                        }
                    });
                });

                // Fungsi filter tahun

                $('#applyYearFilter').on('click', function () {
                    var filterYear = $('#filterYear').val();
                    console.log(`Bulan ${filterYear}`);

                    if (filterYear) {
                        currentFilter = {filter: 'year', value: filterYear };
                    }

                    $.ajax({
                        url: '/absensi',
                        type: 'GET',
                        data: {
                            year: filterYear,
                        },
                        success: function (response) {
                            console.log(response);
                            updateTable(response.data);
                        }, error: function (xhr, status, error) {
                            // Menangani error jika response status bukan 2xx
                            var errorMessage = xhr.responseJSON ? xhr.responseJSON.error : 'Terjadi kesalahan';
                            alert('Error: ' + errorMessage);  // Tampilkan pesan error ke pengguna
                        }
                    });
                });
                // Fungsi untuk memperbarui tabel
                function updateTable(data) {
                    console.log(data)
                    const tbody = $('#absensi-table tbody');
                    tbody.empty();

                    if (data.length > 0) {
                        let no = 1;
                        data.forEach(item => {
                            const row = `
                    <tr>
                        <td>${no++}</td>
                        <td>${item.user.name}</td>
                        <td>${item.status_absensi_masuk}</td>
                        <td>${item.waktu_masuk}</td>
                        <td>${item.status_absensi_pulang}</td>
                        <td>${item.waktu_pulang}</td>
                    </tr>`;
                            tbody.append(row);
                        });
                    } else {
                        tbody.append('<tr><td colspan="6">Tidak ada data absensi!</td></tr>');
                    }
                }


                $('.download-pdf').on('click', function (e) {
                    e.preventDefault();
                    let url = "{{ route('absensi.download-pdf') }}";
                    /*  */
                    if (currentFilter.filter && currentFilter.value) {
                        url += `?filter=${currentFilter.filter}&value=${currentFilter.value}`;
                    };
                    window.location.href = url;
                });


            })
        </script>
    </main>
</x-layout>
