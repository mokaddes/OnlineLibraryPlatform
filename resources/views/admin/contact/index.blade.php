@extends('admin.layouts.master')
@section('contact', 'active')
@section('title') {{ $data['title'] ?? '' }} @endsection
@push('style')
<style>
.status-badge {
    font-size: 0.3rem;
    font-weight: 300;
    padding: 0px 2px;
}
th {
    font-weight: normal !important;
}
</style>
@endpush
@section('content')
    <div class="content-wrapper mt-3" >
        <div class="content">
            <div class="container-fluid">
                <div class="card" >
                    <div class="card-header" >
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h3 class="card-title" >All Contacts</h3>
                            </div>
                        </div>
                    </div>
                    <div class="px-2 table-responsive">
                        <table class="table table-hover dataTables">
                            <thead>
                                <th class="text-center">Serial No.</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th class="text-center">Contact At</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                                @foreach ($data['rows'] as $key => $row)
                                    <tr>
                                        <td class="text-center" style="width: 10%">{{$key+1}}</td>
                                        <td>{{$row->name ?? 'N/A'}}</td>
                                        <td>{{$row->email ?? 'N/A'}}</td>
                                        <td class="text-center">{{ date('d-m-Y H:i:s', strtotime($row->created_at)) ?? 'N/A' }}</td>
                                        <td class="text-center">
                                            <a href="javascript:void(0)"class="btn btn-sm view" title="View"
                                            style="background: #9C9C9C" data-id="{{$row->id}}">
                                                <i class="far fa-eye" style="color: #ffffff;"></i>
                                            </a>
                                            <a href="{{ route('admin.contact.delete', $row->id ) }}" title="Delete"
                                                onclick="return confirm('{{ __('Are you sure want to delete this item') }}')"
                                                class="btn btn-sm" style="background: #EC2626">
                                                <i class="fas fa-trash-alt" style="color: #ffffff;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View modal --}}
    <div class="modal fade" id="viewContactModal" tabindex="-1" aria-labelledby="viewContactModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewContactModalLabel">View Contact Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modal_body"></div>

            </div>
        </div>
    </div>
    </div>
@endsection
@push("script")
<script type="text/javascript">
    $(document).on('click', '.view', function() {
        let cat_id = $(this).data('id');
        $.get('contact/'+cat_id+'/view', function(data) {
            console.log(data);
            $('#viewContactModal').modal('show');
            $('#modal_body').html(data);
        });
    });
</script>
@endpush