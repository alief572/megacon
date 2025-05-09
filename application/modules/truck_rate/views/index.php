<?php
$ENABLE_ADD     = has_permission('Rate_Borongan.Add');
$ENABLE_MANAGE  = has_permission('Rate_Borongan.Manage');
$ENABLE_VIEW    = has_permission('Rate_Borongan.View');
$ENABLE_DELETE  = has_permission('Rate_Borongan.Delete');
?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.css" integrity="sha512-0nkKORjFgcyxv3HbE4rzFUlENUMNqic/EzDIeYCgsKa/nwqr2B91Vu/tNAu4Q0cBuG4Xe/D1f/freEci/7GDRA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<div class="box box-primary">
    <div class="box-header">
        <div style="display:inline-block;width:100%;">
            <!-- <a class="btn btn-sm btn-success add" href="javascript:void(0)" title="Add" style="float:left;margin-right:8px"><i class="fa fa-plus">&nbsp;</i>New</a> -->
            <a class="btn btn-success btn-sm" style='float:right;' href="<?= base_url('truck_rate/add_truck_rate') ?>" title="Add">Add</a>
        </div>

    </div>
    <div class="box-body">

        <table id="tableset" class="table table-striped">
            <thead>
                <tr>
                    <!-- <th class="text-center">No</th>
                    <th class="text-center">Product</th>
                    <th class="text-center">Rate Borongan / Produk</th>
                    <th class="text-center">Last Update By</th>
                    <th class="text-center">Last Update</th>
                    <th class="text-center">Action</th> -->
                    <th class="text-center">No</th>
                    <th class="text-center">Kendaraan</th>
                    <th class="text-center">Maksimal Muatan</th>
                    <th class="text-center">Rate Trucking</th>
                    <th class="text-center">Last Update By</th>
                    <th class="text-center">Last Update</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<form id="form-modal" action="" method="post">
    <div class="modal fade" id="ModalView">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="head_title"></h4>
                </div>
                <div class="modal-body" id="view">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-sm btn-success save">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- Modal Bidus-->
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="<?= base_url('assets/js/autoNumeric.js') ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- End Modal Bidus-->
<style>
    .box-primary {

        border: 1px solid #ddd;
    }
</style>
<script type="text/javascript">
    var no_list = 1;

    $(document).ready(function() {
        DataTables();
        $('.auto_num').autoNumeric('init');
    });

    $(document).on('click', '.add', function() {
        $.ajax({
            type: 'post',
            url: siteurl + active_controller + '/add',
            cache: false,
            success: function(result) {
                $('.save').show();

                $('#head_title').html('Add Rate Borongan');
                $('#view').html(result);
                $('#ModalView').modal('show');
            },
            error: function(result) {
                swal({
                    type: 'error',
                    title: 'Error !',
                    text: 'Please try again later !'
                });
            }
        });
    });

    $(document).on('click', '.edit', function() {
        var id = $(this).data('id');

        $.ajax({
            type: 'post',
            url: siteurl + active_controller + '/add',
            data: {
                'id': id
            },
            cache: false,
            success: function(result) {
                $('.save').show();

                $('#head_title').html('Add Rate Borongan');
                $('#view').html(result);
                $('#ModalView').modal('show');
            },
            error: function(result) {
                swal({
                    type: 'error',
                    title: 'Error !',
                    text: 'Please try again later !'
                });
            }
        });
    });

    $(document).on('click', '.view', function() {
        var id = $(this).data('id');

        $.ajax({
            type: 'post',
            url: siteurl + active_controller + '/view',
            data: {
                'id': id
            },
            cache: false,
            success: function(result) {
                $('.save').hide();

                $('#head_title').html('View Rate Borongan');
                $('#view').html(result);
                $('#ModalView').modal('show');
            },
            error: function(result) {
                swal({
                    type: 'error',
                    title: 'Error !',
                    text: 'Please try again later !'
                });
            }
        });
    });

    $(document).on('submit', '#form-modal', function(e) {
        e.preventDefault();

        swal({
            type: 'warning',
            title: 'Warning !',
            text: 'This data will be saved !',
            showCancelButton: true
        }, function(next) {
            if (next) {
                var formData = new FormData($('#form-modal')[0]);

                $.ajax({
                    type: 'post',
                    url: siteurl + active_controller + '/save_rate_borongan',
                    data: formData,
                    dataType: 'json',
                    cache: false,
                    processData: false,
                    contentType: false,
                    success: function(result) {
                        if (result.status == 1) {
                            swal({
                                type: 'success',
                                title: 'Success !',
                                text: result.pesan
                            }, function(lanjut) {
                                $('#ModalView').modal('hide');
                                DataTables();
                            });
                        } else {
                            swal({
                                type: 'warning',
                                title: 'Warning !',
                                text: result.pesan
                            });
                        }
                    },
                    error: function(result) {
                        swal({
                            type: 'error',
                            title: 'Error !',
                            text: 'Please try again later !'
                        });
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete', function() {
        var id = $(this).data('id');

        swal({
            type: 'warning',
            title: 'Warning !',
            text: 'This data will be deleted !',
            showCancelButton: true
        }, function(next) {
            if (next) {
               
                $.ajax({
                    type: 'post',
                    url: siteurl + active_controller + '/delete_truck_rate',
                    data: {
                        'id': id
                    },
                    dataType: 'json',
                    cache: false,
                    success: function(result) {
                        if (result.status == 1) {
                            swal({
                                type: 'success',
                                title: 'Success !',
                                text: result.pesan
                            }, function(lanjut) {
                                DataTables();
                            });
                        } else {
                            swal({
                                type: 'warning',
                                title: 'Warning !',
                                text: result.pesan
                            });
                        }
                    },
                    error: function(result) {
                        swal({
                            type: 'error',
                            title: 'Error !',
                            text: 'Please try again later !'
                        });
                    }
                });
            }
        });
    });

    function number_format(number, decimals, dec_point, thousands_sep) {
        // Strip all characters but numerical ones.
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    function DataTables() {
        var DataTables = $('#tableset').dataTable({
            ajax: {
                // url: siteurl + active_controller + 'get_data_rate_borongan',
                url: siteurl + active_controller + 'get_data_truck_rate',
                type: "POST",
                dataType: "JSON",
                data: function(d) {

                }
            },
            columns: [{
                    data: 'no'
                },
                {
                    data: 'kd_asset'
                },
                {
                    data: 'maksimal_muatan'
                },
                {
                    data: 'truck_rate'
                },
                {
                    data: 'last_update_by'
                },
                {
                    data: 'last_update'
                },
                {
                    data: 'action'
                }
            ],
            responsive: true,
            processing: true,
            serverSide: true,
            stateSave: true,
            destroy: true,
            paging: true
        });
    }
</script>