@forelse($users as $user)
    <tr>
        <td class="px-4 py-3 align-middle">{{ $users->firstItem() + $loop->index }}</td>
        <td class="py-3 align-middle font-weight-bold text-dark">{{ $user->name }}</td>
        <td class="py-3 align-middle">{{ $user->nip }}</td>
        <td class="py-3 align-middle">
            @if($user->role == 'admin')
                <span class="badge badge-primary border px-3 py-2" style="border-radius: 20px;">
                    <i class="fas fa-user-shield mr-1"></i> Admin
                </span>
            @elseif($user->role == 'kepala_sekolah')
                <span class="badge badge-warning border px-3 py-2" style="border-radius: 20px; color: #856404;">
                    <i class="fas fa-user-tie mr-1"></i> Kepala Sekolah
                </span>
            @else
                <span class="badge badge-info border px-3 py-2" style="border-radius: 20px;">
                    <i class="fas fa-chalkboard-teacher mr-1"></i> Guru
                </span>
            @endif
        </td>
        <td class="py-3 align-middle text-center">
            @if($user->is_active)
                <span class="badge badge-success px-3 py-2" style="border-radius: 20px; background-color: #e8f5e9; color: #2e7d32;">
                    <i class="fas fa-check-circle mr-1"></i> Aktif
                </span>
            @else
                <span class="badge badge-danger px-3 py-2" style="border-radius: 20px; background-color: #ffebee; color: #c62828;">
                    <i class="fas fa-times-circle mr-1"></i> Nonaktif
                </span>
            @endif
        </td>
        <td class="py-3 align-middle text-center">
            <span class="badge badge-light border text-muted px-2 py-1" style="font-family: monospace;">
                {{ $user->default_password }}
            </span>
        </td>
        <td class="py-3 align-middle text-muted small">
            {{ $user->created_at->format('d M Y, H:i') }}
        </td>
        <td class="py-3 align-middle text-center">
            <div class="d-flex justify-content-center gap-2" style="gap: 8px;">
                <button type="button" class="btn btn-outline-info btn-sm px-3" style="border-radius: 6px;" data-toggle="modal" data-target="#modalEdit{{ $user->id }}">
                    <i class="fas fa-pencil-alt"></i> Edit
                </button>
                
                <form action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    @if($user->is_active)
                        <button type="button" class="btn btn-outline-danger btn-sm px-3" style="border-radius: 6px;"
                            onclick="event.preventDefault(); Swal.fire({title: 'Nonaktifkan Pengguna ini?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#d33', cancelButtonColor: '#3085d6', confirmButtonText: 'Nonaktifkan'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                            <i class="fas fa-user-slash"></i> Nonaktif
                        </button>
                    @else
                        <button type="button" class="btn btn-outline-success btn-sm px-3" style="border-radius: 6px;"
                            onclick="event.preventDefault(); Swal.fire({title: 'Aktifkan Pengguna ini?', icon: 'question', showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#3085d6', confirmButtonText: 'Aktifkan'}).then((result) => { if (result.isConfirmed) { this.closest('form').submit(); } })">
                            <i class="fas fa-user-check"></i> Aktifkan
                        </button>
                    @endif
                </form>
            </div>
            
            <!-- MODAL EDIT USER DIPINDAHKAN KE DALAM LOOP AGAR SESUAI DATA -->
            @include('admin.kelolauser._modal_edit', ['user' => $user])
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-5 text-muted">
            <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i>
            <p class="mb-0">Belum ada data user ditemukan.</p>
        </td>
    </tr>
@endforelse

<tr class="pagination-row d-none">
    <td colspan="8">
        @if($users->hasPages())
            <div class="d-flex justify-content-center mt-3" id="pagination-links">
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </td>
</tr>
