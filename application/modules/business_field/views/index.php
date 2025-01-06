<?php
$ENABLE_ADD     = has_permission('Master_Business_Field.Add');
$ENABLE_MANAGE  = has_permission('Master_Business_Field.Manage');
$ENABLE_VIEW    = has_permission('Master_Business_Field.View');
$ENABLE_DELETE  = has_permission('Master_Business_Field.Delete');
?>
<style type="text/css">
    thead input {
        width: 100%;
    }
</style>
<div id='alert_edit' class="alert alert-success alert-dismissable" style="padding: 15px; display: none;"></div>
<link rel="stylesheet" href="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.css') ?>">

<div class="box">
    <div class="box-header">
        <?php if ($ENABLE_ADD) : ?>
            <a class="btn btn-success btn-sm add" href="javascript:void(0)" title="Add"><i class="fa fa-plus">&nbsp;</i>Add</a>
        <?php endif; ?>

        <span class="pull-right">
        </span>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Business Type</th>
                    <th>Keterangan</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

<!-- awal untuk modal dialog -->

<div class="modal modal-default fade" id="dialog-popup" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="head_title">Default</h4>
            </div>
            <div class="modal-body" id="ModalView">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">
                    <i class="fa fa-close"></i>
                    Close
                </button>
                <button type="button" class="btn btn-sm btn-primary" id="save">
                    <i class="fa fa-save"></i>
                    Save
                </button>
            </div>
        </div>
    </div>

    <!-- DataTables -->
    <script src="<?= base_url('assets/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
    <script src="<?= base_url('assets/plugins/datatables/dataTables.bootstrap.min.js') ?>"></script>

    <!-- page script -->
    <script type="text/javascript">
        $(document).ready(function() {
            datatables();
        });

        $(document).on('click', '.add', function() {
            $.ajax({
                type: 'post',
                url: siteurl + active_controller + 'add_business_field',
                cache: false,
                success: function(result) {
                    $('#head_title').html('Add Business Field');
                    $('#ModalView').html(result);
                    $('#dialog-popup').modal('show');
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
            var id_bidang_usaha = $(this).data('id_bidang_usaha');

            $.ajax({
                type: 'post',
                url: siteurl + active_controller + 'edit_business_field',
                data: {
                    'id_bidang_usaha': id_bidang_usaha
                },
                cache: false,
                success: function(result) {
                    $('#head_title').html('Edit Business Field');
                    $('#ModalView').html(result);
                    $('#dialog-popup').modal('show');
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

        $(document).on('click', '.delete', function() {
            var id_bidang_usaha = $(this).data('id_bidang_usaha');

            swal({
                type: 'warning',
                title: 'Are you sure ?',
                text: 'This data will be deleted !',
                cancelShowButton: true
            }, function(next) {
                if (next) {
                    $.ajax({
                        type: 'post',
                        url: siteurl + active_controller + 'delete_business_field',
                        data: {
                            'id_bidang_usaha': id_bidang_usaha
                        },
                        cache: false,
                        dataType: 'json',
                        success: function(result) {
                            if (result.status == '1') {
                                swal({
                                    type: 'success',
                                    title: 'Success !',
                                    text: result.msg
                                }, function(lanjut) {
                                    $('#dialog-popup').modal('hide');
                                    datatables();
                                });
                            } else {
                                swal({
                                    type: 'warning',
                                    title: 'Failed !',
                                    text: result.msg
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

        $(document).on('click', '#save', function() {
            var id_bidang_usaha = $('input[name="id_bidang_usaha"]').val();
            var business_field = $('input[name="business_field"]').val();
            var keterangan = $('textarea[name="keterangan"]').val();

            swal({
                type: 'warning',
                title: 'Are you sure ?',
                text: 'This data will be saved !',
                cancelShowButton: true
            }, function(next) {
                if (next) {
                    $.ajax({
                        type: 'post',
                        url: siteurl + active_controller + 'save_business_field',
                        data: {
                            'id_bidang_usaha': id_bidang_usaha,
                            'business_field': business_field,
                            'keterangan': keterangan
                        },
                        cache: false,
                        dataType: 'json',
                        success: function(result) {
                            if (result.status == '1') {
                                swal({
                                    type: 'success',
                                    title: 'Success !',
                                    text: result.msg
                                }, function(lanjut) {
                                    $('#dialog-popup').modal('hide');
                                    datatables();
                                });
                            } else {
                                swal({
                                    type: 'warning',
                                    title: 'Failed !',
                                    text: result.msg
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

        function datatables() {
            var table = $('#example1').DataTable({
                ajax: {
                    url: siteurl + active_controller + 'get_data_bf',
                    type: "POST",
                    dataType: "JSON",
                    data: function(d) {

                    }
                },
                columns: [{
                        data: 'no',
                    },
                    {
                        data: 'bidang_usaha'
                    },
                    {
                        data: 'keterangan'
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

        $(function() {
            $("#form-area").hide();
        });
    </script>