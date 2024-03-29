<?php
            $calendar = new Calendar();
            $events = $calendar->getEvents(1, 'today');
            foreach($events as $event){
                $date =  date('U',strtotime($event['dateTime']));
                $bookings = $calendar->getBookingsByDate($date);
                $productsSum = $productCollection = [];
                foreach ($bookings as $booking){
                    $productsSum[$booking->ID] = [];
                    $meta = $booking->meta;
                    $products = $meta['products'];
                    $name = $meta['userData']['name'];
                    $bookingLink = get_edit_post_link($booking->ID);
                    foreach ($products as $key => $product){
                        $productsSum[$booking->ID][$key]['weight'] = $product['weight']['qt'];
                        $productsSum[$booking->ID][$key]['items'] = $product['items']['qt'];
                        $productCollection[$key]['name'] = $product['name'];
                        $productCollection[$key]['weight']['mu'] = $product['weight']['mu'];
                        $productCollection[$key]['items']['mu'] = $product['items']['mu'];
                        $productCollection[$key]['weight']['qt'] = $productCollection[$key]['weight']['qt'] + $product['weight']['qt'];
                        $productCollection[$key]['items']['qt'] = $productCollection[$key]['items']['qt'] + $product['items']['qt'];
                    }


                }

                // Obtain a list of columns
                foreach ($productCollection as $key => $row) {
                    $prodName[$key]  = $row['name'];
                }
                // Sort the data with volume descending, edition ascending
                // Add $data as the last parameter, to sort by the common key
                array_multisort($prodName, SORT_ASC, $productCollection);

                foreach ($productCollection as $product){

                    echo "<tr>
                            <td>".$product['name']."</td>
                            <td>".$product['weight']['qt'].' '.$product['weight']['mu']."</td>
                            <td>".$product['items']['qt'].' '.$product['items']['mu']."</td>
                            </tr>";
                }

            }
