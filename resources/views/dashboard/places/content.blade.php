<meta name="csrf-token" content="{{ csrf_token() }}" />

@include('dashboard.places.modal.add')
<div class="pagetitle">
    <div class="row">
        <div class="col-md-6 col-6">
            <h1>Places</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Places</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-6 col-6 text-right pt-2">

            <button class="btn btn-sm btn-primary" onclick="showAddmodal()" style="float: right;"><i
                    class="bi-plus-circle"></i> Add Place</button>
        </div>
    </div>
</div><!-- End Page Title -->

<section class="section dashboard">
    <div class="row">

        <!-- Left side columns -->
        <div class="col-lg-12">
            <div class="row">

                <!-- Top Selling -->
                <div class="col-12">
                    <div class="card top-selling overflow-auto">
                        <div class="card-body pb-0" style="padding-top: 10px;">

                            <table class="table border table-hover datatable" id="places_table">
                                <thead>
                                    <tr class="">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>

                                <tfoot style="display: table-row-group;">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                </tbody>
                            </table>





                        </div>

                    </div>
                </div><!-- End Top Selling -->



            </div>
        </div><!-- End Left side columns -->



    </div>
</section>
<script type="text/javascript">
    $(function() {
        var table = $('#places_table').DataTable({
            pageLength: 20,
            processing: true,
            serverSide: true,
            "searching": true,
            'columnDefs': [
                {
                    targets: [1],
                    render: function(data, type, row) {
                        return `<span class="text-primary fw-bold">` + data + `</span>`;
                    }
                },
              
                {
                    targets: [2],
                    render: function(data, type, row) {
                        
                        return `<div class="dropdown">
                            <span class="" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="cursor: pointer">
                              <i class="bi bi-three-dots-vertical"></i>
                            </span>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <a class="dropdown-item" href="#" onclick="deleteUser(` + data + `)"><i class="bi bi-trash3 pl-1"> </i> Delete</a>
                          
                            </div>
                          </div>`;

                    }
                }
            ],
            dom: 'rtpi',
            ajax: "{{ route('places.getData') }}",
            "order": [
                [0, "desc"]
            ],
            "paging": true,
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'id',
                    name: 'id',
                    orderable: false,
                    searchable: false
                },
            ],

            initComplete: function() {
                // Apply the search
                var i = 0
                var input_box = [0,1];
                this.api().columns().every(function() {
                    var column = this;
                    if (input_box.includes(i)) {
                        var input =
                            `<input type="text" placeholder="Search" style="height:25px; font-family: Arial,FontAwesome" class="per-page form-control form-control-sm m-input">`
                        $(input).appendTo($(column.footer()).empty())
                            .on('change', function() {
                                column.search($(this).val(), false, false, true).draw();
                            });
                    }
                
                   
                    i++;
                });
            },
        });
        // $('.dataTables_length').addClass('d-none');

    });
    // var tfoot = $('#places_table tfoot');
    $('tfoot').each(function() {
        $(this).insertAfter($(this).siblings('thead'));
    });
</script>
<script>
  


    function add() {
        $.ajax({
            url: "{{ route('places.add') }}",
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $('#addUserForm').serialize(),
            beforeSend: function() {
                $('#add-place-btn').prop('disabled', true);
                $('#add-place-btn').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...'
                    );
            },
            complete: function() {
                $('#add-place-btn').prop('disabled', false);
                $('#add-place-btn').html('Add');
            },
            success: function(data) {
                if (data.success) {
                    $('#add_place_modal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Added Successfully',
                        showConfirmButton: false,
                        timer: 2000
                    });
                    $('#places_table').DataTable().draw();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 2000
                    })
                }

            }

        });
    }

    function showAddmodal(){
        $('#add_place_modal').modal('show');
    }

    function deleteUser(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })

        swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('places.delete') }}",
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Users Deleted Successfully',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Error',
                                text: 'Something went wrong',
                                showConfirmButton: false,
                                timer: 2000
                            })
                        }

                    },
                    error: function(xhr) { // if error occured
                        Swal.fire({
                            icon: 'warning',
                            title: 'Error',
                            text: 'Something went wrong',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    }

                });

            }
        })

    }
  
</script>
