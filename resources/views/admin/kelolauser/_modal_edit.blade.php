<!-- MODAL EDIT USER -->
<div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header bg-info text-white border-0 py-3">
                <h5 class="modal-title font-weight-bold"><i class="fas fa-user-edit mr-2"></i> Edit Pengguna</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4 bg-light text-left">
                    <div class="card border-0 shadow-sm mb-0" style="border-radius: 12px;">
                        <div class="card-body p-4">
                            <!-- NAMA -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" style="border-radius: 8px;" value="{{ $user->name }}" required>
                            </div>
                            
                            <!-- NIP -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">NIP (Opsional)</label>
                                <input type="text" name="nip" class="form-control" style="border-radius: 8px;" value="{{ $user->nip }}">
                            </div>

                            <!-- PASSWORD -->
                            <div class="form-group mb-4">
                                <label class="font-weight-bold text-dark">Password Baru (Opsional)</label>
                                <input type="password" name="password" class="form-control" style="border-radius: 8px;" placeholder="Kosongkan jika tidak ingin mengubah password">
                            </div>

                            <!-- ROLE -->
                            <div class="form-group mb-0">
                                <label class="d-block font-weight-bold text-dark mb-2">Peran <span class="text-danger">*</span></label>
                                <div class="custom-radio-btn d-flex flex-wrap">
                                    <input type="radio" id="roleEditAdmin{{ $user->id }}" name="role" value="admin" {{ $user->role == 'admin' ? 'checked' : '' }} required>
                                    <label for="roleEditAdmin{{ $user->id }}" class="flex-fill text-center m-0">Admin</label>
                                    
                                    <input type="radio" id="roleEditKepsek{{ $user->id }}" name="role" value="kepala_sekolah" {{ $user->role == 'kepala_sekolah' ? 'checked' : '' }} required>
                                    <label for="roleEditKepsek{{ $user->id }}" class="flex-fill text-center m-0" style="border-left: 0;">Kepala Sekolah</label>
                                    
                                    <input type="radio" id="roleEditGuru{{ $user->id }}" name="role" value="guru" {{ $user->role == 'guru' ? 'checked' : '' }} required>
                                    <label for="roleEditGuru{{ $user->id }}" class="flex-fill text-center m-0" style="border-left: 0;">Guru</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 pr-4 bg-light">
                    <button type="button" class="btn btn-secondary px-4 shadow-sm" style="border-radius: 8px;" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info px-4 shadow-sm" style="border-radius: 8px;">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
