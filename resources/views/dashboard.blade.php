@extends('layout')

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endsection
@section('content')
    <div class="modal fade" id="visitorModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="visitorModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="alert alert-danger print-error-msg" style="display:none">
                <ul></ul>
            </div>
            <form method="POST" id="visitorForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="visitorModalLabel">Visitor Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <label>Name</label>
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                        <div class="form-group mb-2">
                            <label>Mobile Number</label>
                            <div class="input-group mb-3">
                            <span class="input-group-text" id="addons">+65</span>
                                <input type="number" name="mobile_no" id="mobile_no" class="form-control" aria-describedby="addons" onKeyPress="if(this.value.length==8) return false;">
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <label>Purpose</label>
                            <input type="text" name="purpose" id="purpose" class="form-control" />
                        </div>
                        <div class="form-group mb-2 for-vehicle">
                            <label>Vehicle</label>
                            <input type="text" name="vehicle" id="vehicle" class="form-control" />
                        </div>
                        <div class="form-group mb-2">
                            <label for="is_walk_in">
                                <input type="checkbox" id="is_walk_in" value="1" />
                                <span>Is walk in?</span> 
                            </label>
                        </div>
                        <input type="hidden" name="is_walk_in" id="walk_in_val" value="0" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <h3>Dashboard</h3>
    <div class="float-end">
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#visitorModal">Add</button>
    </div>
    <div class="clearfix"></div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Date Filter</label>
                <input type="date" name="date" id="dateFilter" class="form-control" value="{{ date('Y-m-d') }}" />
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="recordTable">
                    <thead>
                        <th>Name</th>
                        <th>Mobile No.</th>
                        <th>Vehicle Number / Walk In</th>
                        <th>Purpose</th>
                        <th>Check-in</th>
                        <th>Check-out</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function(){
        var table = $('#recordTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            aaSorting: [[0,'decs']],
            ajax: {
                url: "{{ route('loadVisitLogs') }}",
                data: function (d) {
                    d.dateFilter = $('#dateFilter').val()
                },
                method: 'POST'
            },
            pageLength: 10,
            columns: [
                {data: 'name', name: 'name', orderable:false},
                {data: 'mobile_no', name: 'mobile_no', orderable:false},
                // {data: 'WasIs', name: 'WasIs',orderable:false},
                {data: 'is_walk_in', name: 'is_walk_in', orderable:false},
                {data: 'purpose', name: 'purpose', orderable:false},
                {data: 'check_in', name: 'check_in', orderable:false},
                {data: 'check_out', name: 'check_out', orderable:false},
            ]
        });

        $('#dateFilter').on("change", () => {
            table.ajax.reload(null, false);
        });

        $('#is_walk_in').on('change', () => {
            if ($('#is_walk_in').is(':checked')){
                $('.for-vehicle').fadeOut();
                $("#walk_in_val").val(1);
            }else{
                $('.for-vehicle').fadeIn();
                $("#walk_in_val").val(0);
            }
        });

        $("#visitorForm").on('submit', (e) => {
            e.preventDefault();
            var errFlag = 0;

            var name = $('#name').val();
            var mobile_no = $('#mobile_no').val();
            var purpose = $('#purpose').val();
            var vehicle = $('#vehicle').val();
            var walk_in_val = $('#walk_in_val').val();

            if(name == "")  { errFlag = 1; }
            if(mobile_no == "")  { errFlag = 1; }
            if(purpose == "")  { errFlag = 1; }
            if ($("#walk_in_val").val() == 0){
                if(vehicle == "")  { errFlag = 1; }
            }

            if(errFlag == 1){
            alert("All fields are required!");
            }else{
            $.ajax({
                url: "{{ url('saveLog') }}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    "name":name,
                    "mobile_no":mobile_no,
                    "purpose":purpose,
                    "vehicle_no":vehicle,
                    "is_walk_in":walk_in_val,
                },
                beforeSend: function(){
                    $('.btn-submit').attr('disabled', true).html('Please wait...');
                },
                success: function(data) {
                $(".print-error-msg").find("ul").html('');
                $(".print-error-msg").css('display','none');
                    if($.isEmptyObject(data.error)){
                        if(!data.success){
                            console.log(data);
                            alert(data.msg);
                        }else{
                            table.ajax.reload(null, false);
                            $("#visitorModal").modal("toggle");
                            $("#visitorForm")[0].reset();
                            $('.for-vehicle').fadeIn();
                            $("#walk_in_val").val(0);
                        }
                    }else{
                        printError(data.error);
                    }
                    $('.btn-submit').attr('disabled', false).html('Submit');
                }
            });
            }
        });

        $(document).on("click",'.btn-checkout',function(){
            var id = $(this).data('id');
            $.ajax({
                url: "{{ url('saveCheckout') }}",
                type:"POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    "id":id
                },
                success: function(data) {
                    table.ajax.reload(null, false);
                }
            });
        });
    });
</script>
@endsection



