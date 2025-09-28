@extends('backend.app', ['title' => 'Show Message'])

@section('content')

<!-- App Content -->
<div class="app-content main-content mt-0">
    <div class="side-app">
        <div class="main-container container-fluid">

            <!-- Page Header -->
            <div class="page-header d-flex justify-content-between align-items-center">
                <h1 class="page-title">Message Details</h1>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Contact</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Show</li>
                </ol>
            </div>

            <!-- Message Card -->
            <div class="row justify-content-center mt-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
                            <h4 class="mb-0">{{ Str::limit($contactlist->message, 50) }}</h4>
                            <a href="javascript:window.history.back()" class="btn btn-light btn-sm">Back</a>
                        </div>
                        <div class="card-body">

                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th class="w-25">Name:</th>
                                    <td>{{ $contactlist->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td>{{ $contactlist->phone ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Post Code:</th>
                                    <td>{{ $contactlist->postcode ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ $contactlist->email ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Message:</th>
                                    <td class="text-break">{{ $contactlist->message ?? 'N/A' }}</td>
                                </tr>
                            </table>

                            <!-- Actions -->
                            <div class="mt-4 d-flex gap-2">
                                <button class="btn btn-danger btn-sm" onclick="showDeleteConfirm(`{{ $contactlist->id }}`, event)">
                                    <i class="fe fe-trash"></i> Delete
                                </button>
                                <button class="btn btn-primary btn-sm" onclick="goToEdit(`{{ $contactlist->id }}`)">
                                    <i class="fe fe-edit"></i> Edit
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Delete confirmation
    function showDeleteConfirm(id, event) {
        event.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "This record will be permanently deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteItem(id);
            }
        });
    }

    // Delete action
    function deleteItem(id) {
        NProgress.start();
        let url = "{{ route('admin.list.destroy', ':id') }}";
        let csrfToken = '{{ csrf_token() }}';
        $.ajax({
            type: "DELETE",
            url: url.replace(':id', id),
            headers: {'X-CSRF-TOKEN': csrfToken},
            success: function(resp) {
                NProgress.done();
                toastr.success(resp.message);
                window.location.href = "{{ route('admin.contact.index') }}";
            },
            error: function(error) {
                NProgress.done();
                toastr.error(error.responseJSON.message || 'Something went wrong!');
            }
        });
    }

    // Edit action
    function goToEdit(id) {
        let url = "{{ route('admin.list.edit', ':id') }}";
        window.location.href = url.replace(':id', id);
    }
</script>
@endpush
