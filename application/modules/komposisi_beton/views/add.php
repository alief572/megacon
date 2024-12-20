<?php
$jenis_beton = (isset($header)) ? $header->nm_jenis_beton : '';
$keterangan = (isset($header)) ? $header->keterangan : '';
?>
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<div class="box">
    <div class="box-header">

    </div>

    <form action="" method="post" id="frm-data" enctype="multipart/form-data">

        <input type="hidden" name="id_komposisi_beton" value="<?= $id_komposisi_beton ?>">

        <div class="box-body">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Jenis Beton</label>
                    <input type="text" name="jenis_beton" id="" class="form-control form-control-sm" value="<?= $jenis_beton ?>" required>
                </div>
            </div>
            <div class="col-md-12">

            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="">Keterangan</label>
                    <textarea name="keterangan" id="" class="form-control form-control-sm"><?= $keterangan ?></textarea>
                </div>
            </div>

            <br><br>

            <div class="col-md-12">
                <h4>Detail Material</h4>
                <button type="button" class="btn btn-sm btn-success add_material">
                    <i class="fa fa-plus"></i> Add Material
                </button>

                <table class="table table-striped" style="margin-top: 1rem;">
                    <thead class="bg-primary">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">Material Name</th>
                            <th class="text-center">Volume (m3)</th>
                            <th class="text-center">Keterangan</th>
                            <th class="text-center">#</th>
                        </tr>
                    </thead>
                    <tbody class="list_detail_material">
                        <?php
                        $no = 1;
                        if (!empty($detail)) {
                            foreach ($detail as $item) {
                                echo '<tr class="row_detail_material_' . $no . '">';

                                echo '<td class="text-center">';
                                echo $no;
                                echo '</td>';

                                echo '<td class="text-left">';
                                echo '<select class="form-control form-control-sm chosen_select id_material" name="detail_material[' . $no . '][id_material]" data-no="' . $no . '">';
                                echo '<option value="">- Select Material -</option>';

                                foreach ($list_material as $item_material) {
                                    $selected = '';
                                    if ($item_material->code_lv4 == $item->id_material) {
                                        $selected = 'selected';
                                    }
                                    echo '<option value="' . $item_material->code_lv4 . '" ' . $selected . '>' . $item_material->nama . '</option>';
                                }

                                echo '</select>';
                                echo '<input type="hidden" class="form-control form-control-sm" name="detail_material[' . $no . '][material_name]" value="' . $item->nm_material . '">';
                                echo '</td>';

                                echo '<td class="text-left">';
                                echo '<input type="number" class="form-control form-control-sm" name="detail_material[' . $no . '][volume]" value="' . $item->volume . '" step="0.01">';
                                echo '</td>';

                                echo '<td class="text-left">';
                                echo '<textarea name="detail_material[' . $no . '][keterangan]" class="form-control form-control-sm">' . $item->keterangan . '</textarea>';
                                echo '</td>';

                                echo '<td class="text-center">';
                                echo '<button type="button" class="btn btn-sm btn-danger del_material" data-no="' . $no . '"><i class="fa fa-trash"></i></button>';
                                echo '</td>';

                                echo '</tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <a href="<?= base_url('komposisi_beton') ?>" class="btn btn-sm btn-danger">
                    <i class="fa fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fa fa-save"></i> Save
                </button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js" integrity="sha512-rMGGF4wg1R73ehtnxXBt5mbUfN9JUJwbk21KMlnLZDJh7BkPmeovBuddZCENJddHYYMkCh9hPFnPmS9sspki8g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    var no_detail_material = 1;

    $(document).ready(function() {
        chosen_select();
    });

    $(document).on('click', '.add_material', function() {
        var hasil = '<tr class="row_detail_material_' + no_detail_material + '">';

        hasil += '<td class="text-center">';
        hasil += no_detail_material;
        hasil += '</td>';

        hasil += '<td class="text-left">';
        hasil += '<select class="form-control form-control-sm chosen_select id_material" name="detail_material[' + no_detail_material + '][id_material]" data-no="' + no_detail_material + '">';
        hasil += '<option value="">- Select Material -</option>';
        <?php
        foreach ($list_material as $item_material) {
        ?>

            hasil += '<option value="<?= $item_material->code_lv4 ?>">';
            hasil += '<?= $item_material->nama ?>'
            hasil += '</option>';

        <?php
        }
        ?>
        hasil += '</select>';
        hasil += '<input type="hidden" class="form-control form-control-sm" name="detail_material[' + no_detail_material + '][material_name]">';
        hasil += '</td>';

        hasil += '<td class="text-left">';
        hasil += '<input type="number" class="form-control form-control-sm" name="detail_material[' + no_detail_material + '][volume]" step="0.01">';
        hasil += '</td>';

        hasil += '<td class="text-left">';
        hasil += '<textarea name="detail_material[' + no_detail_material + '][keterangan]" class="form-control form-control-sm"></textarea>';
        hasil += '</td>';

        hasil += '<td class="text-center">';
        hasil += '<button type="button" class="btn btn-sm btn-danger del_material" data-no="' + no_detail_material + '"><i class="fa fa-trash"></i></button>';
        hasil += '</td>';

        hasil += '</tr>';

        no_detail_material++;

        $('.list_detail_material').append(hasil);

        chosen_select();
    });

    $(document).on('click', '.del_material', function() {
        var no = $(this).data('no');

        $('.row_detail_material_' + no).remove();
    });

    $(document).on('change', '.id_material', function() {
        var id_material = $(this).val();
        var no = $(this).data('no');

        $.ajax({
            type: 'post',
            url: siteurl + active_controller + '/get_nm_material',
            data: {
                'id_material': id_material
            },
            dataType: 'json',
            cache: false,
            success: function(result) {
                $('input[name="detail_material[' + no + '][material_name]"]').val(result.nm_material);
            },
            error: function(result) {
                swal({
                    type: 'error',
                    title: 'Error !',
                    text: 'Please try again later !'
                });
            }
        });
    })

    $(document).on('submit', '#frm-data', function(e) {
        e.preventDefault();

        swal({
            type: 'warning',
            title: 'Are you sure ?',
            text: 'Data will be saved !',
            showCancelButton: true
        }, function(next) {
            if (next) {
                var formData = new FormData($('#frm-data')[0]);

                $.ajax({
                    type: 'post',
                    url: siteurl + active_controller + 'save_komposisi_beton',
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
                                window.location.href = siteurl + active_controller;
                            });
                        } else {
                            swal({
                                type: 'warning',
                                title: 'Failed !',
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

    function chosen_select() {
        $('.chosen_select').chosen({
            width: '100%'
        });
    }
</script>