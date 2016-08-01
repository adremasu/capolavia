<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 11/07/16
 * Time: 12.08
 */


$booking = $_GET['booking'];
$date = $_GET['date'];


if ($booking || $date){
    $pdf = new PDF();

    $pdf->AddPage();

    $pdf->SetFont('Arial','B',20);
    $title = 'Prenotazioni per '.date_i18n('l j F ',$date);
    $title = iconv('UTF-8', 'windows-1252', $title);
    $pdf->Cell(40,10,$title,0,1);

    $calendar = new Calendar();
    $header = array('Prodotto', 'peso', 'pezzi');
    $bookings = $calendar->getBookingsByDate($date);
    $productsSum = $productCollection = [];
    foreach ($bookings as $booking){
        $pdf->SetFont('Arial','B',10);

        $productsSum[$booking->ID] = [];
        $meta = $booking->meta;
        $products = $meta['products'];
        $name = $meta['userData']['name'];
        $delivery = $meta['userData']['delivery'];
        if ($delivery){
            $pdf->SetFont('Arial','U',10);
            $deliveryText = ' [CONSEGNA A DOMICILIO: '.$meta['userData']['address'].']';
            $pdf->SetFont('Arial','B',10);

        } else {
            $deliveryText = '';
        }
        $pdf->Cell(40,16,$name.$deliveryText,0,1);
        $data = '';
        $pdf->SetFont('Arial','',10);

        foreach ($products as $key => $product){
            $productsSum[$booking->ID][$key]['weight'] = $product['weight']['qt'];
            $productsSum[$booking->ID][$key]['items'] = $product['items']['qt'];
            $productCollection[$key]['name'] = $product['name'];
            $productCollection[$key]['weight']['mu'] = $product['weight']['mu'];
            $productCollection[$key]['items']['mu'] = $product['items']['mu'];
            $productCollection[$key]['weight']['qt'] = $productCollection[$key]['weight']['qt'] + $product['weight']['qt'];
            $productCollection[$key]['items']['qt'] = $productCollection[$key]['items']['qt'] + $product['items']['qt'];

            $data .= "\n\r".$product['name'].";".$product['weight']['qt'].' '.$product['weight']['mu'].";".$product['items']['qt'].' '.$product['items']['mu'].";";

            $pdf->Cell(70,8,$product['name'],1,0);

            if ($product['weight']['qt']){
                $pdf->Cell(20,8,$product['weight']['qt'].' '.$product['weight']['mu'],1,1);
            }
            if ($product['items']['qt']){
                $pdf->Cell(20,8,$product['items']['qt'].' '.$product['items']['mu'],1,1);
            }

        }

        if ($meta['userData']['notes']){
            $notesText = 'Note: '.$meta['userData']['notes'];
            $pdf->Cell(120,8,$notesText,0,1);
        }

        $pdf->Ln(1);

    }


    $pdf->Output("report.pdf", "I");

    die ('ciao');

}
