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
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/logomts.png') }}" class="img-circle elevation-2 mb-3"
                            style="width:160px; height:160px; object-fit:cover;">
                    </div>

                    <h3 class="profile-username text-center">
                        {{ auth()->user()->name }}
                    </h3>

                    <p class="text-muted text-center">
                        @if(auth()->user()->role === 'admin')
                            Administrator
                        @elseif(auth()->user()->role === 'kepala_sekolah')
                            Kepala Sekolah
                        @else
                            Guru
                        @endif
                        <br><small>NIP: {{ auth()->user()->nip }}</small>
                    </p>

                    @if(auth()->user()->role === 'guru')
                    <ul class="list-group list-group-unbordered mb-3">
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
                        @foreach($mapelsGrouped as $mapelName => $kelases)
                        <li class="list-group-item">
                            <b>{{ $mapelName }}</b> <a class="float-right">{{ implode(', ', $kelases) }}</a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                        <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Settings</a>
                        </li>
                    </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane" id="activity">
                            <!-- Post -->
                            <div class="post">
                                <div class="user-block">
                                    <span class="username ml-0">
                                        <a href="#">Sistem Aktivitas</a>
                                    </span>
                                    <span class="description ml-0">Hari ini</span>
                                </div>
                                <p>
                                    Berhasil masuk (login) ke dalam sistem.
                                </p>
                            </div>
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
                                    <label for="inputFoto" class="col-sm-3 col-form-label">Foto Profil</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control-file mt-2" name="foto" id="inputFoto" accept="image/*">
                                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah foto profil. Maksimal 2MB.</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputName" class="col-sm-3 col-form-label">Nama</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="name" id="inputName" value="{{ old('name', auth()->user()->name) }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputNIP" class="col-sm-3 col-form-label">NIP</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control bg-light" name="nip" id="inputNIP" value="{{ old('nip', auth()->user()->nip) }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputEmail" class="col-sm-3 col-form-label">Email</label>
                                    <div class="col-sm-9">
                                        <input type="email" class="form-control" name="email" id="inputEmail" value="{{ old('email', auth()->user()->email) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputNoHP" class="col-sm-3 col-form-label">No. Handphone</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" name="no_hp" id="inputNoHP" value="{{ old('no_hp', auth()->user()->no_hp) }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPassword" class="col-sm-3 col-form-label">Password Baru <small class="text-muted">(opsional)</small></label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Biarkan kosong jika tidak ingin mengubah password">
                                        <small class="text-muted">
                                            Status password Anda: 
                                            @if(\Illuminate\Support\Facades\Hash::check('password', auth()->user()->password) || \Illuminate\Support\Facades\Hash::check(auth()->user()->nip, auth()->user()->password))
                                                <span class="text-danger font-weight-bold">Masih menggunakan password default sistem! Harap segera diganti.</span>
                                            @else
                                                <span class="text-success font-weight-bold">Sudah aman (-)</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="inputPasswordConfirm" class="col-sm-3 col-form-label">Konfirmasi Password</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control" name="password_confirmation" id="inputPasswordConfirm" placeholder="Ulangi password baru">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-3 col-sm-9">
                                        <button type="submit" class="btn btn-danger">Simpan Perubahan</button>
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