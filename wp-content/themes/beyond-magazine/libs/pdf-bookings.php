<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 11/07/16
 * Time: 12.08
 */


$date = $_GET['date'];
$format = $_GET['format'];


if ($date) {

    $pdf = new PDF();
    $calendar = new Calendar();
    $bookings = $calendar->getBookingsByDate($date);

    if ($format != 'TT'){
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 20);
        $title = 'Prenotazioni per ' . date_i18n('l j F ', $date);
        $title = iconv('UTF-8', 'windows-1252', $title);
        $pdf->Cell(40, 10, $title, 0, 1);

        $header = array('Prodotto', 'peso', 'pezzi');
        $productsSum = $productCollection = [];
        foreach ($bookings as $booking) {
            $pdf->SetFont('Arial', 'B', 10);
            $pdf->SetAutoPageBreak(true);
            $productsSum[$booking->ID] = [];
            $meta = $booking->meta;
            $products = $meta['products'];
            $name = $meta['userData']['name'];
            $delivery = $meta['userData']['delivery'];
            $phone = $meta['userData']['phone'];

            if ($delivery) {
                $pdf->SetFont('Arial', 'U', 10);
                $deliveryText = ' [CONSEGNA A DOMICILIO: ' . $meta['userData']['address'] . ']';
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->MultiCell(0,4, $name . $deliveryText);

            } else {
                $pdf->MultiCell(0,4, $name);
            }

            if ($phone) {
                $pdf->SetFont('Arial', 'U', 10);
                $phoneText = '[TELEFONO: ' . $phone . ']';
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->MultiCell(0,4, $phoneText);

            } 
            $data = '';
            $pdf->SetFont('Arial', '', 10);

            foreach ($products as $key => $product) {
                $productsSum[$booking->ID][$key]['weight'] = $product['weight']['qt'];
                $productsSum[$booking->ID][$key]['items'] = $product['items']['qt'];
                $productCollection[$key]['name'] = $product['name'];
                $productCollection[$key]['weight']['mu'] = $product['weight']['mu'];
                $productCollection[$key]['items']['mu'] = $product['items']['mu'];
                $productCollection[$key]['weight']['qt'] = $productCollection[$key]['weight']['qt'] + $product['weight']['qt'];
                $productCollection[$key]['items']['qt'] = $productCollection[$key]['items']['qt'] + $product['items']['qt'];

                $data .= "\n\r" . $product['name'] . ";" . $product['weight']['qt'] . ' ' . $product['weight']['mu'] . ";" . $product['items']['qt'] . ' ' . $product['items']['mu'] . ";";

                $pdf->Cell(70, 8, $product['name'], 1, 0);

                if ($product['weight']['qt']) {
                    $pdf->Cell(20, 8, $product['weight']['qt'] . ' ' . $product['weight']['mu'], 1, 1);
                }
                if ($product['items']['qt']) {
                    $pdf->Cell(20, 8, $product['items']['qt'] . ' ' . $product['items']['mu'], 1, 1);
                }

            }

            if ($meta['userData']['notes']) {
                $notesText = 'Note: ' . $meta['userData']['notes'];
                $pdf->MultiCell(0, 4, $notesText);
            }

            $pdf->Ln(1);

        }
        $pdf->Output("report.pdf", "I");
    }
    if ($format == 'TT'){

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=bookings.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

//header
        $completeProductList = [];
        $productsList = [];
        foreach ($bookings as $booking) {
            $id = $booking->ID;
            $meta = $booking->meta;
            $products = $meta['products'];
            foreach ($products as $key => $product) {
                $productCollection[$key]['name'] = $product['name'];
                $productCollection[$key]['weight']['mu'] = $product['weight']['mu'];
                $productCollection[$key]['items']['mu'] = $product['items']['mu'];
                $productCollection[$key]['weight']['qt'] = $productCollection[$key]['weight']['qt'] + $product['weight']['qt'];
                $productCollection[$key]['items']['qt'] = $productCollection[$key]['items']['qt'] + $product['items']['qt'];
                $_CSVBookings[$id]['orders'][$key]['weight']['mu'] = $product['weight']['mu'];
                $_CSVBookings[$id]['orders'][$key]['weight']['qt'] = $product['weight']['qt'];
                $_CSVBookings[$id]['orders'][$key]['items']['mu'] = $product['items']['mu'];
                $_CSVBookings[$id]['orders'][$key]['items']['qt'] = $product['items']['qt'];
                $completeProductList[$key] = $product['name'];
            }
            $customerName = $meta['userData']['name'];
            $_CSVBookings[$id]['name'] = $customerName;

            $delivery = $meta['userData']['delivery'];
            $phone = $meta['userData']['phone'];
            $address = $meta['userData']['address'];
            $notes = $meta['userData']['notes'];
            $deliveryText = ($delivery ? 'V': 'X');
            $_CSVBookings[$id]['delivery'] = $deliveryText;
            $_CSVBookings[$id]['address'] = $address;
            $_CSVBookings[$id]['notes'] = $notes;
            foreach ($products as $product){
                $productsList[] = $product['name'];
            }
        }

        foreach ($productCollection as $key => $row) {
            $prodNames[$key]  = $row['name'];
        }
        ksort($prodNames);
        ksort($productCollection);
        // Sort the data with volume descending, edition ascending
        // Add $data as the last parameter, to sort by the common key
        $CSVproductsQts = 'Totale, ,';
        foreach ($productCollection as $product){
            if ($product['weight']['qt']){
                $CSVproductsQts .= $product['weight']['qt'].' '.$product['weight']['mu'].' ';
            }
            if ($product['items']['qt']){
                $CSVproductsQts .= $product['items']['qt'].' pz.';
            }
            $CSVproductsQts .= ',';

        }
        $CSVproductsQts .= "\n";
        $completeProductList = array_unique($completeProductList);
        $productsList = array_unique($productsList);

        $CSVProductsList =  "Prodotti,Cons.,";
        foreach($prodNames as $key => $product){
            $nameParts = explode(' ',$product);
            $productName = '';
            foreach ($nameParts as $namePart){
                $part = ((strlen($namePart)>4) ? substr($namePart,0,3).'.' : $namePart);
                $productName .= $part.' ';
            }
            $CSVProductsList .= $productName.',';
        }
        $CSVProductsList .= 'Indirizzo, Note';

        $CSVOrders = '';

        foreach ($_CSVBookings as &$booking){
            $CSVOrders .= $booking['name'].',';
            $CSVOrders .= $booking['delivery'].',';
            $orders = $booking['orders'];
            foreach ($orders as $order){
                foreach ($completeProductList as $key => &$prod){
                    if (!array_key_exists($key, $booking['orders'])){
                        $booking['orders'][$key]['weight']['qt'] = '-';
                        $booking['orders'][$key]['weight']['mu'] = '-';
                        $booking['orders'][$key]['items']['qt'] = '-';
                        $booking['orders'][$key]['items']['mu'] = '-';
                    }
                }
                ksort($booking['orders']);
            }
            foreach ($booking['orders'] as $key => $order) {
                if ($order['weight']['qt']){
                    $CSVOrders .= $order['weight']['qt'];
                    $CSVOrders .= $order['weight']['mu'].',';
                } elseif ($order['items']['qt']){
                    $CSVOrders .= $order['items']['qt'];
                    $CSVOrders .= 'pz.,';
                }
            }
            $CSVOrders .= '"'.$booking['address'].'",';
            $CSVOrders .= '"'.$booking['notes'].'",';

            $CSVOrders .= "\n";

        }
        echo $CSVProductsList." \n";
        echo $CSVOrders."\n";
        echo $CSVproductsQts;
    }

    die();

}