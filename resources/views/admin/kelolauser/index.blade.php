@extends('adminlte::page')

@section('title', 'Manajemen User')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="font-weight-bold text-dark">Manajemen User</h1>
            <p class="text-muted mb-0">Kelola data pengguna, hak akses, dan status akun.</p>
        </div>
    </div>
@stop

@section('content')

<style>
/* Custom Radio Toggle for Role */
.custom-radio-btn input[type="radio"] {
    display: none;
}
.custom-radio-btn label {
    display: inline-block;
    padding: 10px 15px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    border: 1px solid #ced4da;
    background-color: #fff;
    color: #495057;
    transition: all 0.2s ease-in-out;
}
.custom-radio-btn input[type="radio"]:checked + label {
    background-color: #007bff;
    border-color: #007bff;
    color: #fff;
    box-shadow: 0 4px 8px rgba(0,123,255,0.2);
}
.custom-radio-btn label:first-of-type {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}
.custom-radio-btn label:last-of-type {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}
</style>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" id="success-alert" role="alert" style="border-radius: 12px; border: none; border-left: 5px solid #28a745;">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
        
        <div class="card-header bg-white border-bottom-0 py-4 d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="card-title font-weight-bold mb-0 text-dark w-100 mb-3">
                Daftar Pengguna
            </h3>
            
            <div class="w-100 d-flex flex-wrap align-items-center gap-3" style="gap: 15px;">
                <div class="input-group" style="max-width: 400px;">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-light border-right-0" style="border-radius: 8px 0 0 8px;">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                    </div>
                    <input type="text" id="ajaxSearch" class="form-control bg-light border-left-0" style="border-radius: 0 8px 8px 0;" placeholder="Cari nama, NIP atau role..." value="{{ request('search') }}">
                </div>
                
                <div class="spinner-border spinner-border-sm text-primary ml-2" id="loadingSpinner" style="display: none;" role="status">
                    <span class="sr-only">Loading...</span>
                </div>

                <button class="btn btn-primary px-4 shadow-sm ml-auto" data-toggle="modal" data-target="#modalTambahUser" style="border-radius: 8px;">
                    <i class="fas fa-user-plus mr-2"></i> Tambah User
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-0 px-4 py-3" width="5%">No</th>
                            <th class="border-0 py-3 cursor-pointer sort-header" data-sort="name" width="20%">Nama <i class="fas fa-sort text-muted ml-1"></i></th>
                            <th class="border-0 py-3 cursor-pointer sort-header" data-sort="nip" width="10%">NIP <i class="fas fa-sort text-muted ml-1"></i></th>
                            <th class="border-0 py-3 cursor-pointer sort-header" data-sort="role" width="15%">Role <i class="fas fa-sort text-muted ml-1"></i></th>
                            <th class="border-0 py-3 text-center cursor-pointer sort-header" data-sort="is_active" width="10%">Status <i class="fas fa-sort text-muted ml-1"></i></th>
                            <th class="border-0 py-3 text-center" width="15%">Password Default</th>
                            <th class="border-0 py-3 cursor-pointer sort-header" data-sort="created_at" width="15%">Dibuat <i class="fas fa-sort-down text-primary ml-1"></i></th>
                            <th class="border-0 py-3 text-center" width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @include('admin.kelolauser._table')
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer bg-white border-top py-3" id="paginationContainer">
            @if($users->hasPages())
                {{ $users->links('pagination::bootstrap-4') }}
            @endif
        </div>
    </div>

    <!-- MODAL TAMBAH USER -->
    <div class="modal fade" id="modalTambahUser" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                
                <div class="modal-header bg-primary text-white border-0 py-3">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-user-plus mr-2"></i> Tambah User Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4 bg-light">
                        <div class="card border-0 shadow-sm mb-0" style="border-radius: 12px;">
                            <div class="card-body p-4">
                                <!-- NAMA -->
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" style="border-radius: 8px;" placeholder="Masukkan nama pengguna" required>
                                </div>

                                <!-- NIP -->
                                <div class="form-group mb-4">
                                    <label class="font-weight-bold text-dark">NIP <span class="text-danger">*</span></label>
                                    <input type="text" name="nip" class="form-control" style="border-radius: 8px;" placeholder="Masukkan NIP" required>
                                </div>

                                <!-- ROLE -->
                                <div class="form-group mb-0">
                                    <label class="d-block font-weight-bold text-dark mb-2">Role <span class="text-danger">*</span></label>
                                    <div class="custom-radio-btn d-flex flex-wrap">
                                        <input type="radio" id="roleAdmin" name="role" value="admin" required>
                                        <label for="roleAdmin" class="flex-fill text-center m-0">Admin</label>
                                        
                                        <input type="radio" id="roleKepsek" name="role" value="kepala_sekolah" required>
                                        <label for="roleKepsek" class="flex-fill text-center m-0" style="border-left: 0;">Kepala Sekolah</label>
                                        
                                        <input type="radio" id="roleGuru" name="role" value="guru" checked required>
                                        <label for="roleGuru" class="flex-fill text-center m-0" style="border-left: 0;">Guru</label>
                                    </div>
                                </div>
                                
                                <div class="alert alert-info border-0 shadow-sm mt-4 mb-0" style="border-radius: 8px; border-left: 4px solid #17a2b8 !important;">
                                    <small><i class="fas fa-info-circle mr-1"></i> Password bawaan (default) akan di-generate secara acak oleh sistem.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 pt-0 pb-4 pr-4 bg-light">
                        <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius: 8px;" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px;">
                            <i class="fas fa-save mr-1"></i> Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@stop

@section('js')
<script>
    // Hilangkan alert sukses otomatis
    setTimeout(function () {
        let alertBox = document.getElementById('success-alert');
        if (alertBox) {
            $(alertBox).alert('close');
        }
    }, 5000);

    $(document).ready(function() {
        // --- AJAX Table Search & Sort ---
        let searchTimeout;
        let currentSortColumn = 'created_at';
        let currentSortDirection = 'desc';

        function fetchUsers(page = 1) {
            const search = $('#ajaxSearch').val();
            
            $('#loadingSpinner').show();
            
            $.ajax({
                url: "{{ route('admin.users') }}",
                data: {
                    search: search,
                    sort: currentSortColumn,
                    direction: currentSortDirection,
                    page: page
                },
                success: function(response) {
                    $('#tableBody').html(response);
                    
                    // Extract pagination from the hidden row in the partial view
                    const paginationHtml = $('#tableBody .pagination-row td').html();
                    if(paginationHtml && paginationHtml.trim() !== '') {
                        $('#paginationContainer').html(paginationHtml);
                    } else {
                        $('#paginationContainer').empty();
                    }
                    
                    $('#loadingSpinner').hide();
                },
                error: function() {
                    $('#loadingSpinner').hide();
                }
            });
        }

        $('#ajaxSearch').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => fetchUsers(1), 500);
        });
        
        $('.sort-header').on('click', function() {
            const column = $(this).data('sort');
            if (currentSortColumn === column) {
                currentSortDirection = currentSortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                currentSortColumn = column;
                currentSortDirection = 'asc';
            }
            
            // Update icons
            $('.sort-header i').removeClass('fa-sort-up fa-sort-down text-primary').addClass('fa-sort text-muted');
            const iconClass = currentSortDirection === 'asc' ? 'fa-sort-up text-primary' : 'fa-sort-down text-primary';
            $(this).find('i').removeClass('fa-sort text-muted').addClass(iconClass);
            
            fetchUsers(1);
        });

        // Handle pagination links
        $(document).on('click', '#paginationContainer a', function(e) {
            e.preventDefault();
            const page = $(this).attr('href').split('page=')[1];
            fetchUsers(page);
        });
        
        // Initialize sort icon cursor
        $('.sort-header').css('cursor', 'pointer');
    });
</script>
@stop
