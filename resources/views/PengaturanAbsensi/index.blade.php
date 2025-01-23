<x-layout bodyClass="g-sidenav-show  bg-gray-200">
    <x-navbars.sidebar activePage='absensi'></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <x-navbars.navs.auth titlePage="Pengaturan waktu absensi"></x-navbars.navs.auth>
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="col-12">
                <form action="{{ route('pengaturanabsensi.update', $pengaturan_absensi[0]->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row my-3">
                        <div class="col-xl-6 col-sm-12">
                            <div class="input-group input-group-static">
                                <label for="pengaturanWaktuBukaAbsensi">Waktu Buka</label>
                                <input type="time" class="form-control" id="pengaturanWaktuBukaAbsensi"
                                    name="waktu_buka" value="{{ $pengaturan_absensi[0]->waktu_buka }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-12">
                            <div class="input-group input-group-static">
                                <label for="pengaturanWaktuTutupAbsensi">Waktu Tutup</label>
                                <input type="time" class="form-control" id="pengaturanWaktuTutupAbsensi"
                                    name="waktu_tutup" value="{{ $pengaturan_absensi[0]->waktu_tutup }}">
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ $pengaturan_absensi[0]->id }}">
                    </div>
                    <div class="row my-3">
                        <div class="col-xl-6 col-sm-12">
                            <div class="input-group input-group-outline is-filled">
                                <label class="form-label"for="rentangAwalIP">Batas bawah alamat IP</label>
                                <input type="text" class="form-control" id="rentangAwalIP" name="rentang_awal_ip"
                                    value="{{ $pengaturan_absensi[0]->rentang_awal_IP }}">
                            </div>
                        </div>
                        <div class="col-xl-6 col-sm-12">
                            <div class="input-group input-group-outline is-filled">
                                <label class="form-label"for="rentangAkhirIP">Batas bawah alamat IP</label>
                                <input type="text" class="form-control" id="rentangAkhirIP" name="rentang_akhir_ip"
                                    value="{{ $pengaturan_absensi[0]->rentang_akhir_IP }}">
                            </div>
                        </div>

                        <div class="row my-3 mx-0">
                            <div class="col-xl-6 col-sm-12">
                                @error('rentang_awal_ip')
                                    <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                                        <span class="alert-icon align-middle">
                                            <span class="material-icons text-md text-white">
                                                thumb_up_off_alt
                                            </span>
                                        </span>
                                        <span class="text-white"><strong>Danger!</strong> {{ $message }}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @enderror
                            </div>
                            <div class="col-xl-6 col-sm-12">
                                @error('rentang_akhir_ip')
                                    <div class="alert alert-danger alert-dismissible fade show w-100" role="alert">
                                        <span class="alert-icon align-middle">
                                            <span class="material-icons text-md text-white">
                                                thumb_up_off_alt
                                            </span>
                                        </span>
                                        <span class="text-white"><strong>Danger!</strong> {{ $message }}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="row p-0">
                            <div class="col-xl-12">
                                <button class="btn btn-primary w-100" type="submit">Simpan</button>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </main>
</x-layout>
