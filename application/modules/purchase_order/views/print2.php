<?php
date_default_timezone_set("Asia/Bangkok");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        @font-face {
            font-family: kitfont;
            src: url('1979 Dot Matrix Regular.TTF');
        }

    .inline-container {
      display: flex;
      align-items: center; /* Agar elemen sejajar secara vertikal */
      /* justify-content: space-between;  */
      /* Mengatur spasi antar elemen */
    }
    .inline-container img {
      width: 380px; /* Sesuaikan ukuran gambar */
      /* height: auto; */
      height: 180px;
    }
    .inline-container h1 {
      margin: 10px; /* Hapus margin default pada H1 */
      font-size: 16px; /* Sesuaikan ukuran teks */
    }

    @media print {
        /* a[href]:after {
            display: none !important; */
            /* content: none !important; */
        /* } */
        /* Default: Hilangkan border untuk semua tabel */
        table {
            border: none !important;
        }

        /* Tampilkan border hanya untuk tabel dengan ID tertentu */
        #table-with-border {
            border-collapse: collapse;
            width: 100%;
        }

        #table-with-border, #table-with-border th, #table-with-border td {
            border: 1px solid black;
        }

        /* #table-with-border td:empty {
            border: 1px solid black;
        } */
    }

    .header-pt h2, .header-pt p {
        margin: 0;
        padding: 2px 0;
        line-height: 1.2;
    }

    .header-pt td {
        padding: 4px;
    }

    .contact-table td {
        padding: 2px 6px;
    }

    </style>
</head>

<body>
<?php
$date = new DateTime();
$date_TTD = $date->format('d F Y');
$curency = 'Rp. ';
// print_r($results['data_penawaran']->no_penawaran);
// $NoPenawaran = $results['data_penawaran']->no_penawaran;
// $TglPenawaran = $results['data_penawaran']->tgl_penawaran;
// $TglPenawaranFix = date('d F Y', strtotime($TglPenawaran));
// // echo $TglPenawaranFix; // Output: 30 April 2025
// // die();
// $NameCustomer = $results['data_penawaran']->name_customer;
// $KetTOP = $results['data_penawaran']->nama_top;
// $NameCreateQuotation = $results['data_penawaran']->name_create_quotation;
// $GrandTotal = !empty($results['data_penawaran']->grand_total) ? $results['data_penawaran']->grand_total : 0;
// $Data_DeliveryCost  = $this->db->query("SELECT * FROM delivery_cost_header WHERE no_penawaran ='$NoPenawaran'")->row();
// $GrandTotalAfterDC = isset($Data_DeliveryCost) ? $Data_DeliveryCost->grand_total : 0;
//START VARIABLE PO
foreach ($header as $header) {
$PONoSurat = !empty($header->no_surat) ? $header->no_surat : '';
$POTanggal_first = !empty($header->tanggal) ? $header->tanggal : '';
$POTanggal = date('d F Y', strtotime($POTanggal_first));
$POid_suplier = !empty($header->id_suplier) ? $header->id_suplier : '';
$KetTOP = !empty($header->term) ? $header->term : '';
}
$getDataSupplier = $this->db->query("SELECT * FROM new_supplier WHERE kode_supplier ='$POid_suplier'")->row();
$SuplierNPWP = !empty($getDataSupplier->tax_number) ? $getDataSupplier->tax_number : '';
$SuplierTO = !empty($getDataSupplier->nama) ? $getDataSupplier->nama : '';
$SuplierTlp = !empty($getDataSupplier->telp) ? $getDataSupplier->telp : '';
$SuplierEmail = !empty($getDataSupplier->email) ? $getDataSupplier->email : '';
$SuplierContactPerson = !empty($getDataSupplier->contact_person) ? $getDataSupplier->contact_person : '';
$InvoiceSentTo = "Sinpasa Commercial Blok C-15 Summarecon Bekasi, Bekasi Utara";
//END VARIABLE PO
// $getDataDueDateBillingPlan = $this->db->query("SELECT * FROM tr_billing_plan WHERE no_so ='$NO_SO'")->row();
// print_r($getDataDueDateBillingPlan->billing_plan_due_date);
// die();
// if(!empty($getDataDueDateBillingPlan->billing_plan_due_date) && $getDataDueDateBillingPlan->billing_plan_due_date != '0000-00-00'){
//  // $Tgl_DueDateBillingPlan = $getDataDueDateBillingPlan->billing_plan_due_date;
//  // $Tgl_DueDateBillingPlan_New = $getDataDueDateBillingPlan->billing_plan_due_date;
//  $Tgl_DueDateBillingPlan_New = date('d F Y', strtotime($getDataDueDateBillingPlan->billing_plan_due_date));
// }
// else{
//  $Tgl_DueDateBillingPlan_New = "";
// }
// print_r($Tgl_DueDateBillingPlan_New);
// die();
// $Tgl_DueDateBillingPlan_New = DateTime::createFromFormat('Y-m-d', $Tgl_DueDateBillingPlan)->format('d F Y');
// $getDataDetailSO = $this->db->query("SELECT * FROM tr_sales_order_detail WHERE no_so ='$NO_SO'")->result();
$headerPT   = $this->db->query("SELECT * FROM companies LIMIT 1")->row();
$NamePT = isset($headerPT) ? $headerPT->name : '';
$AddressPT = isset($headerPT) ? $headerPT->address : '';
$Address2PT = isset($headerPT) ? $headerPT->address2 : '';
$NpwpPT = isset($headerPT) ? $headerPT->taxid : '';
$BankNamePT = isset($headerPT) ? $headerPT->bank_name : '';
$AccountBeneficiaryPT = isset($headerPT) ? $headerPT->account_beneficiary : '';
$BankAccountPT = isset($headerPT) ? $headerPT->bank_account : '';
$PhonePT = isset($headerPT) ? $headerPT->phone : '';
$FaxPT = isset($headerPT) ? $headerPT->fax : '';
$WebsitePT = isset($headerPT) ? $headerPT->homepage : '';
$EmailPT = isset($headerPT) ? $headerPT->email : '';
// function number_to_words_new($number) {
//     $formatter = new \NumberFormatter('en', \NumberFormatter::SPELLOUT); // 'en' untuk English
//     return $formatter->format($number);
// }
?>

    <table border="0" width=800 style="margin-left: 5px;" id="table-no-border">
        <!-- START BAGIAN AKHIR HEADER KOP SURAT -->
        <table border="0">
            <tr>
                <td>
                    <div class="inline-container">
                        <img src="<?= base_url("assets/images/logo.jpg") ?>" width="180">
                    </div>
                </td>
                <td>
                    <table border="0" class="header-pt">
                        <tr>
                            <td><h2 style="margin: 0;">&nbsp;<?= @$NamePT ?></h2></td>
                        </tr>
                        <tr style="font-size: 15px;">
                            <td>
                                <p>&nbsp;&nbsp;<?=  @$AddressPT ?><br>
                                &nbsp;&nbsp;<?=  @$Address2PT ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" class="contact-table" style="font-size: 15px;">
                                    <tr>
                                        <td>No. Telepon Kantor</td>
                                        <td>:</td>
                                        <td><?= @$PhonePT ?></td>
                                    </tr>
                                    <tr>
                                        <td>No. Fax</td>
                                        <td>:</td>
                                        <td><?= @$FaxePT ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>:</td>
                                        <td>
                                            <a href="mailto:<?= @$EmailPT ?>" class="link-print"><?= @$EmailPT ?></a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Webiste</td>
                                        <td>:</td>
                                        <td>
                                            <a href="https://<?= preg_replace('#^https?://#', '', @$WebsitePT) ?>" class="link-print" target="_blank">
                                                <?= @$WebsitePT ?>
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <!-- END BAGIAN AKHIR HEADER KOP SURAT -->
        
        <!-- GARIS PEMISAH TEBAL -->
        <hr style="border: 3px solid black; margin: 10px 0;" width=800>

        <!-- START BODY / ISI BAGAIN 1-->
        <table border="0" width="800" style="font-weight: bold; font-size: 15px;">
            <tr>
                <td colspan="7"><h2 style="margin: 0;"><center>PURCHASE ORDER</center></h2></td>
            </tr>
            <tr>
                <td>No. PO</td>
                <td>:</td>
                <td><?= @$PONoSurat ?></td>
                <td><div style="width: 100px;"></div></td>
                <td>To</td>
                <td>:</td>
                <td><?= @$SuplierTO ?></td>
            </tr>
            <tr>
                <td>Date</td>
                <td>:</td>
                <td><?= @$POTanggal ?></td>
                <td><div style="width: 100px;"></div></td>
                <td>Tlp</td>
                <td>:</td>
                <td><?= @$SuplierTlp ?></</td>
            </tr>
            <tr>
                <td>No. NPWP</td>
                <td>:</td>
                <td><?= @$SuplierNPWP ?></td>
                <td><div style="width: 100px;"></div></td>
                <td>E-mail</td>
                <td>:</td>
                <td><?= @$SuplierEmail ?></td>
            </tr>
            <tr>
                <td>Payment</td>
                <td>:</td>
                <td><?= @$KetTOP ?></td>
                <td><div style="width: 100px;"></div></td>
                <td>Attn</td>
                <td>:</td>
                <td><?= @$SuplierContactPerson ?></td>
            </tr>
            <tr>
                <td>Invoice sent to</td>
                <td>:</td>
                <td>Sipansa comercial Blok C-15</td>
                <td><div style="width: 100px;"></div></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>Summarecon Bekasi, Bekasi Utara</td>
                <td><div style="width: 100px;"></div></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <!-- END BODY / ISI BAGAIN 1-->
        <br>
        <!-- START BODY / ISI BAGAIN 2-->
        <!-- <tabla border="0" width="800" class="header-pt">
            <tr>
                <td>
                    <p style="font-weight: bold; font-size: 15px;">Kepada Yth,</p>
                </td>
                <td>
                    <p style="font-weight: bold; font-size: 15px;"><?= @$NameCustomer ?></p>
                </td>
            </tr>
            <tr >
                <td><div style="height: 20px;">&nbsp;</div></td>
            </tr>
            <tr>
                <td>
                    <p style="font-size: 15px;">
                    Berikut ini kami sampaikan penawaran dengan spesifikasi produk sebagai berikut :
                    </p>
                </td>
            </tr>
        </table> -->
        <!-- END BODY / ISI BAGAIN 2-->
        <br>
        <!-- START BODY / ISI BAGAIN 3-->
        <!-- style="border-collapse: collapse; border: 1px solid black;" -->
        <table border="1" width="800" style="font-weight: bold; font-size: 15px; border-collapse: collapse; border: 1px solid black; text-align: center;">
            <tr>
                <td rowspan="2">No</td>
                <td rowspan="2">Spesifikasi</td>
                <td rowspan="2">Qty</td>
                <td rowspan="2">Sat</td>
                <td colspan="2">Harga</td>
            </tr>
            <tr>
                <td colspan="4">
                    <table border="0" style="width: 100%; border-collapse: collapse; border: 1px solid black;">
                        <tr>
                            <td>Harga Satuan</td>
                            <td style="width: 50%;">Total</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <!-- <tr>
                <td>1</td> 
                <td style="text-align: left;">Saluran Beton U-Ditch</td> 
                <td>8</td> 
                <td>Ton</td> 
                <td>Rp. 1.150.000</td>  
                <td>Rp. 12.150.000</td>            
            </tr>
            <tr>
                <td>2</td> 
                <td style="text-align: left;">Saluran Beton U-Ditch</td> 
                <td>8</td> 
                <td>Ton</td> 
                <td>Rp. 1.150.000</td>  
                <td>Rp. 12.150.000</td>            
            </tr> -->
            <!-- START LOOPING DATA DETAIL -->
            <?php 
            $numb = 0;
            $CIF = "<br>" . $header->cif . "<br><br><br><br>";
            $TOT_PPH = 0;
            foreach ($detail as $detail) {
                $numb++;
                $kategory = $detail->idmaterial;
                $barang  = $this->db->query("SELECT * FROM ms_inventory_category3 WHERE id_category3 ='$kategory' ")->row();

                $TOT_PPH += $detail->jumlahharga * $detail->pajak / 100;
                $HS = number_format($detail->hargasatuan, 2);
                $JH = number_format($detail->jumlahharga, 2);
                if (strtolower($header->loi) == 'lokal') {
                    $HS = number_format($detail->hargasatuan, 2);
                    $JH = number_format($detail->jumlahharga, 2);
                }

                $satuan = $detail->satuan;
                $satuan_packing = $detail->satuan_packing;
                if($detail->tipe !== '' && $detail->tipe !== null) {
                    $check_code4 = $this->db->get_where('new_inventory_4', ['code_lv4' => $detail->idmaterial])->num_rows();

                    if($check_code4 < 1) {
                        $this->db->select('IF(b.code, "Kg", b.code) as satuan, IF(c.code IS NULL, "M3", c.code) as satuan_packing');
                        $this->db->from('accessories a');
                        $this->db->join('ms_satuan b', 'b.id = a.id_unit', 'left');
                        $this->db->join('ms_satuan c', 'c.id = a.id_unit_gudang', 'left');
                        $this->db->where('a.id', $detail->idmaterial);
                        $data_material = $this->db->get()->row();

                        $satuan = $data_material->satuan;
                        $satuan_packing = $data_material->satuan_packing;
                    }
                }

                $detail_code = str_split($detail->code, 35);
                $final_detail_code = implode("<br>", $detail_code);

                $detail_nama = str_split($detail->nama, 35);
                $final_detail_nama = implode("<br>", $detail_nama);
                $konversi = ($detail->konversi > 0) ? $detail->konversi : 1;
            ?>
            <tr>
                <td><?= $numb ?></td>
                <td style="text-align: left;"><?= $final_detail_nama ?></td>
                <td><?= $detail->qty   ?></td>
                <td><?= ucfirst($satuan)  ?></td>
                <td><?= $curency.' '.$HS ?></td>
                <td><?= $curency.' '.$JH ?></td>
            </tr>
            <?php 
            }
            ?>
            <!-- END LOOPING DATA DETAIL -->
            <tr>
                <td colspan="4">&nbsp;</td>
                <td style="text-align: right;">Total</td>
                <td><?= $curency.' '.number_format($header->total_barang, 2) ?></td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td style="text-align: right;">PPN 11 %</td>
                <td><?= $curency.' '.number_format($header->total_ppn, 2) ?></td>
            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
                <td style="text-align: right;">Grand Total</td>
                <td><?= $curency.' '.number_format($header->subtotal, 2) ?></td>
            </tr>
        </table>
        <!-- END BODY / ISI BAGAIN 3-->
        <br>
        <!-- START BODY / ISI BAGAIN 4-->
        <table border="0" widht="800" class="header-pt" style="font-size: 15px;">
            <tr>
                <td>Terbilang : <?php echo ucfirst(number_to_words(intval($header->subtotal))) . " Rupiah"; ?></td>
            </tr>
            <tr>
                <td style="">
                    <?php
                    $EmailPO = 'megaconbp.purchasing@gmail.com';
                    ?>
                    <!-- <p style="font-weight: bold; font-size: 15px;">Catatan :</p> -->
                    <ul style="list-style-type: none; padding-left: 0; width: 800px;">
                        <li>1. Harga Franco Plant Cikarang PT. Megacon Bangun Perkasa</li>
                        <li>2. Sistem Pembayaran : <bold><i><u><?= @$KetTOP ?></bold></i></u></li>
                        <li>3. Mohon PO yang sudah diterima ditandatangani, sitempel dan di e-mail ke <a href="mailto:<?= @$EmailPO ?>" class="link-print"><?= @$EmailPO ?></a></li>
                        <li>4. Dokumen Asli dikirim ke alamat Sinpasa Comercial BLok C-15, Summarecon Bekasi, Bekasi Utara</li>
                        <li>5. Tagihan uang muka harus melampirkan kwitansi bermeterai, invoice dan faktur pajak asli</li>
                        <li>6. Tagihan pelunasan harus melampirkan kwitansi bermeterai, invoice, faktur pajak dan surat jalan yang sudah di verifikasi oleh<br>&nbsp;&nbsp;&nbsp;&nbsp;PT. Megacon Bangun Perkasa termasuk rekapitulasi penerimaan materia beserta BAST</li>
                        <li>7. Pengiriman barang harus melampirkan surat jalan yang mencantumkan Nomor Purchase Order (PO)</li>
                        <li>8. Invoice harus berdasarkan Nomor Purhcase Order (PO) yang sudah diterbitkan</li>
                    </ul>
                </td>
            </tr>
        </table>
        <!-- END BODY / ISI BAGAIN 4-->
        <!-- <br> -->
        <!-- START BODY / ISI BAGAIN 5-->
        <!-- <table border="0" widht="800" class="header-pt" style="font-size: 15px;">
            <tr>
                <td style="">
                    <p style="font-weight: bold; font-size: 15px;">Spefikasi Teknis Produk Saluran Beton U-Ditch :</p>
                    <ul>
                        <li>
                            <table border="0"  class="contact-table">
                                <tr>
                                    <td style="width: 100px;">Mutu Beton</td>
                                    <td>:</td>
                                    <td>K-350</td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table border="0"  class="contact-table">
                                <tr>
                                    <td style="width: 100px;">Mutu Besi</td>
                                    <td>:</td>
                                    <td>U 50 Hard Drawn Wiremesh</td>
                                </tr>
                            </table>
                        </li>
                        <li>
                            <table border="0"  class="contact-table">
                                <tr>
                                    <td style="width: 100px;">Semen</td>
                                    <td>:</td>
                                    <td>Type I</td>
                                </tr>
                            </table>
                        </li>
                    </ul>
                </td>
            </tr>
        </table> -->
        <!-- END BODY / ISI BAGAIN 5-->

        <!-- START BODY / ISI BAGAIN 6-->
        <!-- <table border="0" widht="800" class="header-pt" style="font-size: 15px;">
            <tr>
                <td style="">
                    <p style="">
                    Demikianlah penawaran Harga ini kami sampaikan. Bila ada yang kurang jelas harap segera
                    menguhubungi kami,<br>Terima kasih atas perhatiannya pada produk kami. Kami tunggu kabar baik
                    selanjutnya.
                    </p>
                </td>
            </tr>
        </table> -->
        <!-- END BODY / ISI BAGAIN 6-->

        <!-- START BODY / ISI BAGAIN 7-->
        <!-- <div style="text-align: right; width: 800;"> -->
        <table border="0" width="800" class="" style="font-size: 15px; display: inline-block;">
            <tr>
                <td>&nbsp;Accepted by,</td>
                <td style="width: 500px;"></td>
                <td style="">
                    <p style="">Bekasi, <?= @$date_TTD ?></p>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td style="width: 500px;"></td>
                <td style="">
                    <p style="">&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td><u>&nbsp;.......................</u></td>
                <td style="width: 500px;"></td>
                <td style="">
                    <p style="text-align: center; font-weight: bold;">
                        <u>WILLY AD</u><br>
                        <bold>Direktur</bold>
                    </p>
                </td>
            </tr>
        </table>
        <!-- </div> -->
        <!-- END BODY / ISI BAGAIN 7-->

    </table>

    <script>
        window.print();
    </script>
</body>

</html>