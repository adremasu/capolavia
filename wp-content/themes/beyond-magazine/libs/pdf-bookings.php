<?php
/**
 * Created by PhpStorm.
 * User: andrea
 * Date: 11/07/16
 * Time: 12.08
 */



// Export of PDF file

$date = $_GET['date'];
$format = $_GET['format'];

function cmp($a, $b)
{
    return strcmp($a["name"], $b["name"]);
}

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
            usort($products, "cmp");
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

    // Export of CSV file
    if ($format == 'TT'){
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=bookings.csv");
        header("Pragma: no-cache");
        header("Expires: 0");

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
        asort($productCollection);
        asort($completeProductList);
        asort($productsList);

        foreach ($productCollection as $key => $row) {
            $prodNames[$key]  = $row['name'];
        }
        // Sort the data with volume descending, edition ascending
        // Add $data as the last parameter, to sort by the common key
        $CSVproductsQts = 'Totale, ,';
        foreach ($productCollection as $key => $Cproduct){
            if ($Cproduct['weight']['qt']){
                $CSVproductsQts .= $Cproduct['weight']['qt'].' '.$Cproduct['weight']['mu'].' ';
            }
            if ($Cproduct['items']['qt']){
                $CSVproductsQts .= $Cproduct['items']['qt'].' pz.';
            }
            $CSVproductsQts .= ',';

        }
        $CSVproductsQts .= "\n";

        $CSVProductsList =  "Prodotti,Cons.,";
        foreach($prodNames as $key => $_productName){
            $nameParts = explode(' ',$_productName);
            $productName = '';
            foreach ($nameParts as $namePart){
                $part = ((strlen($namePart)>4) ? substr($namePart,0,3).'.' : $namePart);
                $productName .= $part.' ';
            }
            $CSVProductsList .= $productName.',';
        }
        $CSVProductsList .= 'Indirizzo, Note';

        $CSVOrders = '';

        asort($completeProductList);
        $i=0;
        foreach ($completeProductList as $key => $value) {
          $orderedProductList[] = $key;
        }

        foreach ($_CSVBookings as &$booking){
            $CSVOrders .= $booking['name'].',';
            $CSVOrders .= $booking['delivery'].',';
            $orders = $booking['orders'];
                foreach ($orderedProductList as $key => &$prod){

                  if (!array_key_exists($prod, $booking['orders'])){
                      $CSVOrders .= '--,';
                  } else {
                    if ($booking['orders'][$prod]['weight']['qt']){
                        $CSVOrders .= $booking['orders'][$prod]['weight']['qt'];
                        $CSVOrders .= $booking['orders'][$prod]['weight']['mu'].',';
                    } elseif ($booking['orders'][$prod]['items']['qt']){
                        $CSVOrders .= $booking['orders'][$prod]['items']['qt'];
                        $CSVOrders .= 'pz.,';
                    }
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
