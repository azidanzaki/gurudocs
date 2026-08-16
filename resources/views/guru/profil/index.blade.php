@extends('adminlte::page')

@section('title', 'Profil')

@section('content_header')
<h1>Profil</h1>
@stop

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body box-profile text-center py-4">
                    <div class="mb-4">
                        <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/logomts.png') }}" class="img-circle shadow-sm"
                            style="width:160px; height:160px; object-fit:cover; border: 4px solid #fff;">
                    </div>

                    <h3 class="profile-username font-weight-bold mb-1">
                        {{ auth()->user()->name }}
                    </h3>

                    <p class="text-muted mb-3">
                        @if(auth()->user()->role === 'admin')
                            <span class="badge badge-primary px-3 py-2" style="border-radius: 20px;">Administrator</span>
                        @elseif(auth()->user()->role === 'kepala_sekolah')
                            <span class="badge badge-success px-3 py-2" style="border-radius: 20px;">Kepala Sekolah</span>
                        @else
                            <span class="badge badge-info px-3 py-2" style="border-radius: 20px;">Guru</span>
                        @endif
                        <br><small class="d-block mt-2 font-weight-bold text-dark">NIP: {{ auth()->user()->nip }}</small>
                    </p>

                    @if(auth()->user()->role === 'guru')
                    <div class="text-left mt-4 pt-3 border-top">
                        <h6 class="font-weight-bold text-dark mb-3"><i class="fas fa-book-reader mr-2 text-primary"></i>Kelas & Mapel</h6>
                        <ul class="list-group list-group-flush mb-0">
                            @php
                                $mengajar = auth()->user()->mengajar();
                                $mapelsGrouped = [];
                                foreach ($mengajar as $m) {
                                    $mapel = \App\Models\Mapel::find($m->mapel_id);
                                    $kelas = \App\Models\Kelas::find($m->kelas_id);
                                    if ($mapel && $kelas) {
                                        $mapelsGrouped[$mapel->nama_mapel][] = $kelas->nama_kelas;
                                    }
                                }
                            @endphp
                            @forelse($mapelsGrouped as $mapelName => $kelases)
                            <li class="list-group-item px-0 border-bottom-0 pb-1 pt-2">
                                <b class="text-dark">{{ $mapelName }}</b> <br>
                                <span class="text-muted small">{{ implode(', ', $kelases) }}</span>
                            </li>
                            @empty
                            <li class="list-group-item px-0 border-bottom-0 text-muted small">
                                Belum ada jadwal mengajar.
                            </li>
                            @endforelse
                        </ul>
                    </div>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="card-header bg-white border-bottom p-0">
                    <ul class="nav nav-pills p-3">
                        <li class="nav-item mr-2"><a class="nav-link font-weight-bold" href="#activity" data-toggle="tab" style="border-radius: 50px; padding: 10px 20px;"><i class="fas fa-history mr-2"></i>Aktivitas</a></li>
                        <li class="nav-item"><a class="nav-link active font-weight-bold" href="#settings" data-toggle="tab" style="border-radius: 50px; padding: 10px 20px;"><i class="fas fa-cog mr-2"></i>Pengaturan</a>
                        </li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane" id="activity">
                            @if(isset($activities) && $activities->count() > 0)
                                <div class="timeline timeline-inverse">
                                    @foreach($activities as $act)
                                        <!-- timeline item -->
                                        <div>
                                            <i class="{{ $act['icon'] }}"></i>
                                            <div class="timeline-item shadow-sm border-0" style="border-radius: 12px;">
                                                <span class="time text-muted"><i class="far fa-clock"></i> {{ $act['time']->diffForHumans() }}</span>
                                                <h3 class="timeline-header border-0 font-weight-bold text-dark">Aktivitas {{ ucfirst($act['type']) }}</h3>
                                                <div class="timeline-body text-muted">
                                                    {!! $act['description'] !!}
                                                </div>
                                            </div>
                                        </div>
                                        <!-- END timeline item -->
                                    @endforeach
                                    <div>
                                        <i class="far fa-clock bg-gray"></i>
                                    </div>
                                </div>
                            @else
                                <div class="text-center py-5 text-muted">
                                    <i class="fas fa-history fa-3x mb-3 opacity-50"></i>
                                    <p class="mb-0">Belum ada aktivitas terekam.</p>
                                </div>
                            @endif
                        </div>
                        
                        <div class="tab-pane active" id="settings">
                            <form class="form-horizontal" method="POST" action="{{ route('profil.update') }}" enctype="multipart/form-data">
                                @csrf
                                
                                @if(session('success'))
                                    <div class="alert alert-success">{{ session('success') }}</div>
                                @endif
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="form-group row">
                                    <label for="inputFoto" class="col-sm-3 col-form-label font-weight-bold">Foto Profil</label>
                                    <div class="col-sm-9">
                                        <div class="custom-file-upload">
                                            <input type="file" class="d-none" name="foto" id="inputFoto" accept="image/*" onchange="document.getElementById('fileName').innerHTML = this.files[0] ? '<i class=\'fas fa-check text-success mr-1\'></i> ' + this.files[0].name : 'Belum ada file dipilih'">
                                            <label for="inputFoto" class="btn btn-outline-primary px-4 py-2 shadow-sm font-weight-bold mb-1" style="border-radius: 8px; cursor: pointer;">
                                                <i class="fas fa-camera mr-2"></i> Pilih Foto Baru
                                            </label>
                                            <div id="fileName" class="text-muted small mb-2">Belum ada file dipilih</div>
                                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto profil. Maksimal 2MB.</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-3 col-form-label font-weight-bold">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" style="border-radius: 8px;" name="name" id="inputName" value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputNIP" class="col-sm-3 col-form-label font-weight-bold">NIP</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control bg-light" style="border-radius: 8px;" name="nip" id="inputNIP" value="{{ old('nip', auth()->user()->nip) }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail" class="col-sm-3 col-form-label font-weight-bold">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" style="border-radius: 8px;" name="email" id="inputEmail" value="{{ old('email', auth()->user()->email) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputNoHP" class="col-sm-3 col-form-label font-weight-bold">No. Handphone</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" style="border-radius: 8px;" name="no_hp" id="inputNoHP" value="{{ old('no_hp', auth()->user()->no_hp) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label font-weight-bold">Password Baru <br><small class="text-muted font-weight-normal">(opsional)</small></label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" style="border-radius: 8px;" name="password" id="inputPassword" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                                        <small class="text-muted d-block mt-2">
                                            Status password Anda: 
                                            @if(\Illuminate\Support\Facades\Hash::check('password', auth()->user()->password) || \Illuminate\Support\Facades\Hash::check(auth()->user()->nip, auth()->user()->password))
                                                <span class="text-danger font-weight-bold"><i class="fas fa-exclamation-triangle"></i> Masih menggunakan password default sistem! Harap segera diganti.</span>
                                            @else
                                                <span class="text-success font-weight-bold"><i class="fas fa-shield-alt"></i> Sudah aman</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPasswordConfirm" class="col-sm-3 col-form-label font-weight-bold">Konfirmasi Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" style="border-radius: 8px;" name="password_confirmation" id="inputPasswordConfirm" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                                <div class="form-group row mt-4">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;"><i class="fas fa-save mr-2"></i> Simpan Perubahan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>

@stop