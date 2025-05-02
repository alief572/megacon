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
// print_r($results['data_penawaran']->no_penawaran);
$NoPenawaran = $results['data_penawaran']->no_penawaran;
$TglPenawaran = $results['data_penawaran']->tgl_penawaran;
$TglPenawaranFix = date('d F Y', strtotime($TglPenawaran));
// echo $TglPenawaranFix; // Output: 30 April 2025
// die();
$NameCustomer = $results['data_penawaran']->name_customer;
$KetTOP = $results['data_penawaran']->nama_top;
$NameCreateQuotation = $results['data_penawaran']->name_create_quotation;
$GrandTotal = !empty($results['data_penawaran']->grand_total) ? $results['data_penawaran']->grand_total : 0;
$Data_DeliveryCost	= $this->db->query("SELECT * FROM delivery_cost_header WHERE no_penawaran ='$NoPenawaran'")->row();
$GrandTotalAfterDC = isset($Data_DeliveryCost) ? $Data_DeliveryCost->grand_total : 0;
// $header	= $this->db->query("SELECT * FROM tr_invoice_sales WHERE id_invoice ='$id_invoice'")->row();
// // $coabank = $header->kd_bank;
// $Invoice = $header->id_invoice;
// $NO_SO = $header->id_so;
// $getDataSO	= $this->db->query("SELECT * FROM tr_sales_order WHERE no_so ='$NO_SO'")->row();
// $To_Name = $getDataSO->pic_customer;
// $getIDCustomer = $getDataSO->id_customer;


//version old
// $To_Name = $header->nm_customer;
// $getIDCustomer = $header->id_customer;
// $getDataCustomer = $this->db->query("SELECT * FROM master_customers WHERE id_customer ='$getIDCustomer'")->row();
// $AlamatCust = $getDataCustomer->alamat;
// $coa =  $this->db->query("SELECT * FROM " . DBACC . ".coa_master WHERE no_perkiraan = '$coabank' ")->row();
// $getNoSO =  $this->db->query("SELECT * FROM tr_invoice_payment_detail WHERE kd_pembayaran ='$kodebayar'")->row();
// $NO_SO = $getNoSO->no_ipp;
// $getDataSPK_delivery = $this->db->query("SELECT * FROM spk_delivery WHERE no_so ='$NO_SO'")->row();
// if(!empty($getDataSPK_delivery->no_surat_jalan)){
// 	$SuratJalan = $getDataSPK_delivery->no_surat_jalan;
// }else{
// 	$SuratJalan = '';
// }
// $getDataHeaderSO = $this->db->query("SELECT * FROM tr_sales_order WHERE no_so ='$NO_SO'")->row();
// $Total_Amt = $getDataHeaderSO->nilai_so;
// $PPN = $getDataHeaderSO->nilai_ppn;
// $Total = $getDataHeaderSO->grand_total;
// $NoPenawaran = $getDataHeaderSO->no_penawaran;
// $getDataPenawaran = $this->db->query("SELECT * FROM tr_penawaran WHERE no_penawaran ='$NoPenawaran'")->row();
// $getIDTop = $getDataPenawaran->top;
// $PpnORNonppn = $getDataPenawaran->ppn;
// $Tgl_Penawaran = $getDataPenawaran->tgl_penawaran;
// // Ubah format menggunakan DateTime
// $date_TTD_Penawaran = DateTime::createFromFormat('Y-m-d', $Tgl_Penawaran)->format('d F Y');
// // $date_TTD_Penawaran = $Tgl_Penawaran->format('d F Y');
// // print_r($date_TTD_Penawaran);
// // die();
// if($PpnORNonppn != '' || $PpnORNonppn != NULL || $PpnORNonppn > 0){
// 	$StatusPpnORNonppn = 'PPN';
// }else{
// 	$StatusPpnORNonppn = 'NON_PPN';
// }
// $getDataTOP = $this->db->query("SELECT * FROM list_help WHERE id ='$getIDTop'")->row();
// $NameTOP = $getDataTOP->name;
// if($getDataTOP->data1 != '' || $getDataTOP->data1 != NULL){
// 	$HariTOP = $getDataTOP->data1;
// }else{
// 	$HariTOP = "";
// }
//hitung tanggal due date sesuai tanggal penawaran
// $format = 'd F Y';
// $HitungDueDate = new DateTime($date_TTD_Penawaran);
// $HitungDueDate->modify("+{$HariTOP} days");
// print_r($date_TTD_Penawaran);
// echo $HitungDueDate->format($format);
// $FixDueDate = $HitungDueDate->format($format);//tidak jadi pakai ini
// die();
// $getDataDueDateBillingPlan = $this->db->query("SELECT * FROM tr_billing_plan WHERE no_so ='$NO_SO'")->row();
// print_r($getDataDueDateBillingPlan->billing_plan_due_date);
// die();
// if(!empty($getDataDueDateBillingPlan->billing_plan_due_date) && $getDataDueDateBillingPlan->billing_plan_due_date != '0000-00-00'){
// 	// $Tgl_DueDateBillingPlan = $getDataDueDateBillingPlan->billing_plan_due_date;
// 	// $Tgl_DueDateBillingPlan_New = $getDataDueDateBillingPlan->billing_plan_due_date;
// 	$Tgl_DueDateBillingPlan_New = date('d F Y', strtotime($getDataDueDateBillingPlan->billing_plan_due_date));
// }
// else{
// 	$Tgl_DueDateBillingPlan_New = "";
// }
// print_r($Tgl_DueDateBillingPlan_New);
// die();
// $Tgl_DueDateBillingPlan_New = DateTime::createFromFormat('Y-m-d', $Tgl_DueDateBillingPlan)->format('d F Y');
// $getDataDetailSO = $this->db->query("SELECT * FROM tr_sales_order_detail WHERE no_so ='$NO_SO'")->result();
$headerPT	= $this->db->query("SELECT * FROM companies LIMIT 1")->row();
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
                <td>No</td>
                <td>:</td>
                <td><?= @$NoPenawaran ?></td>
                <td><div style="width: 200px;"></div></td>
                <td><?= @$TglPenawaranFix ?></td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td>Penawaran Harga</td>
                <td><div style="width: 200px;"></div></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <!-- END BODY / ISI BAGAIN 1-->
        <br>
        <!-- START BODY / ISI BAGAIN 2-->
        <tabla border="0" width="800" class="header-pt">
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
        </table>
        <!-- END BODY / ISI BAGAIN 2-->
        <br>
        <!-- START BODY / ISI BAGAIN 3-->
        <!-- style="border-collapse: collapse; border: 1px solid black;" -->
        <table border="1" width="800" style="font-weight: bold; font-size: 15px; border-collapse: collapse; border: 1px solid black; text-align: center;">
            <tr>
                <td>No</td>
                <td>Description</td>
                <td>Unit Harga Satuan</td>
                <td>Quantity</td>
                <td>Jumlah</td>
            </tr>
            <!-- <tr>
                <td>1</td> 
                <td style="text-align: left;">Saluran Beton U-Ditch</td> 
                <td>340.000</td> 
                <td>42 pcs</td> 
                <td>14.280.000</td>            
            </tr> -->
            <!-- START LOOPING DATA DETAIL -->
            <?php 
            $numb = 0;
            $harga_seb_diskon = 0;
            $harga_ses_diskon = 0;
            $ttl_diskon = 0;
            $ttl_persen_diskon = 0;
            $total_value_before_dc = 0;
            foreach($results['data_penawaran_detail'] AS $detail_penawaran){
            $numb++;
            $harga_ses_diskon += (($detail_penawaran->harga_satuan + $detail_penawaran->cutting_fee + $detail_penawaran->delivery_fee) * $detail_penawaran->qty);
            ?>
            <tr>
                <td><?= $numb ?></td>
                <td style="text-align: left;"><?= $detail_penawaran->nama_produk ?></td>
                <td><?= number_format($detail_penawaran->harga_satuan)  ?></td>
                <td><?= round($detail_penawaran->qty).' pcs'  ?></td>
                <td><?= number_format($detail_penawaran->total_harga) ?></td>
            </tr>
            <?php 
            }
            ?>
            <!-- END LOOPING DATA DETAIL -->
            <tr>
                <td colspan="4">Total Value Before Delivery Cost</td>
                <td><?= number_format(@$GrandTotal) ?></td>
            </tr>
            <tr>
                <td colspan="4">Grand Total After Delivery Cost and PPN</td>
                <td><?= number_format(@$GrandTotalAfterDC) ?></td>
            </tr>
        </table>
        <!-- END BODY / ISI BAGAIN 3-->
        <br>
        <!-- START BODY / ISI BAGAIN 4-->
        <table border="0" widht="800" class="header-pt" style="font-size: 15px;">
            <tr>
                <td style="">
                    <p style="font-weight: bold; font-size: 15px;">Catatan :</p>
                    <ul style="list-style-type: none; padding-left: 0;">
                        <li>- Harga di atas belum termasuk ongkos pengiriman sampai lokasi</li>
                        <li>- Harga diatas tidak termasuk biaya kuli / preman di lapangan</li>
                        <li>- Berlakunya Penawaran 14 (Empat Belas) Hari Setelah tanggal Penawaran</li>
                        <li>- Harga di atas tidak termasuk PPN</li>
                        <li>- Sistem Pembayaran : <bold><i><u><?= @$KetTOP ?></bold></i></u></li>
                    </ul>
                </td>
            </tr>
        </table>
        <!-- END BODY / ISI BAGAIN 4-->
        <!-- <br> -->
        <!-- START BODY / ISI BAGAIN 5-->
        <table border="0" widht="800" class="header-pt" style="font-size: 15px;">
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
        </table>
        <!-- END BODY / ISI BAGAIN 5-->

        <!-- START BODY / ISI BAGAIN 6-->
        <table border="0" widht="800" class="header-pt" style="font-size: 15px;">
            <tr>
                <td style="">
                    <p style="">
                    Demikianlah penawaran Harga ini kami sampaikan. Bila ada yang kurang jelas harap segera
                    menguhubungi kami,<br>Terima kasih atas perhatiannya pada produk kami. Kami tunggu kabar baik
                    selanjutnya.
                    </p>
                </td>
            </tr>
        </table>
        <!-- END BODY / ISI BAGAIN 6-->

        <!-- START BODY / ISI BAGAIN 7-->
        <!-- <div style="text-align: right; width: 800;"> -->
        <table border="0" width="800" class="" style="font-size: 15px; display: inline-block;">
            <tr>
                <td style="width: 670px;"></td>
                <td style="">
                    <p style="">Hormat Kami.</p>
                </td>
            </tr>
            <tr>
                <td style="width: 670px;"></td>
                <td style="">
                    <p style="">&nbsp;</p>
                </td>
            </tr>
            <tr>
                <td style="width: 670px;"></td>
                <td style="">
                    <p style="text-align: center; font-weight: bold;"><u><?= @$NameCreateQuotation ?></u></p>
                </td>
            </tr>
        </table>
        <!-- </div> -->
        <!-- END BODY / ISI BAGAIN 7-->

	</table>

	<script>
		// window.print();
	</script>
</body>

</html>