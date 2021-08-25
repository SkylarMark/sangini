<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <?php $this->load->view('import') ?>
</head>

<style>
    html body{
        font-family: 'montserrat';
    }

    @page 
    {
        margin-top: 0mm;  /* this affects the margin in the printer settings */
        margin-bottom: 1mm;
    }

    @media print
    {    
        .hidden-print
        {
            display: none !important;
        }

        html, body {
            height:100vh; 
            margin: 0 !important; 
            padding: 0 !important;
            overflow: hidden;
        }
    }
</style>

<body>
<link rel="stylesheet" type="text/css"  href="<?php echo base_url() ?>assets/css/bill.css">

<div id="invoice">
    <div class="toolbar hidden-print">
        <div class="text-right">
            <button id="printInvoice" class="btn btn-info" onclick="window.print();"><i class="fa fa-print"></i> Print</button>
        </div>
        <hr>
    </div>
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                            <img src="<?php echo base_url() ?>assets/logo.png" width="250px"/>
                    </div>
                    <div class="col company-details">
                        <h2 class="name"><?php echo $owner[0]['owner_name'] ?></h2>
                        <div><?php echo $owner[0]['work_address'] ?></div>
                        <div><?php echo $owner[0]['contact_phone'] ?></div>
                        <div><?php echo $owner[0]['contact_email'] ?></div>
                    </div>
                </div>
            </header>
            <main>
                <div class="row contacts">
                    <div class="col invoice-to">
                        <div class="text-gray-light">INVOICE TO:</div>
                        <h2 class="to"><?php echo $user[0]['fullname'] ?></h2>
                        <div class="address"><?php echo $user[0]['address_line1'].' '.$user[0]['address_line2'].' '.$user[0]['city'] ?></div>
                        <div class="email"><a href="mailto:<?php echo $user[0]['email'] ?>"><?php echo $user[0]['email'] ?></a></div>
                    </div>
                    <div class="col invoice-details">
                        <h4 class="invoice-id">Invoice : <?php echo $order_id ?></h4>
                        <div class="date">Order Date: <?php echo $order_main[0]['Date'] ?></div>
                        <div class="date">Order Time: <?php echo $order_main[0]['Time'] ?></div>
                    </div>
                </div>
                <table>
                    <thead  class="text-right">
                        <tr>
                            <th class="text-left">#</th>
                            <th class="text-left">Product Name</th>
                            <th class="text-left">Cloth</th>
                            <th class="text-left">Color</th>
                            <th class="text-left">Size</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Order Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-right">
                        <?php
                        $sgst = $config[0]['sgst'];
                        $cgst = $config[0]['cgst'];
                        $igst = $config[0]['igst'];

                        $discount = $order_main[0]['discount'] == null ? $user[0]['discount'] : $order_main[0]['discount'];
                        
                        $totalamount = 0;

                        $x = 0; 
                        foreach($order_details as $row)
                        {
                            $x = $x + 1;
                            
                            $totalamount = $totalamount + $row['order_amount'];

                            echo "
                            <tr>
                            <td class='text-left'>".$x."</td>
                            <td class='text-left'>".$row['product_name']."</td>
                            <td class='text-left'>".$row['cloth_name']."</td>
                            <td class='text-left'>".$row['color_code']."</td>
                            <td class='text-left'>".$row['size_code']."</td>
                            <td>".$row['price']."</td>
                            <td>".$row['quantity']."</td>
                            <td ondblclick='changeOrderAmount(this.id, this)' id='".$row['product_list_id']."'>
                                    ".$row['order_amount']." &#8377;
                            </td>
                            </tr>
                            ";
                        }
                        
                        // Amount Calculations
                        $discountamount = ($totalamount*$discount)/100;

                        $totalamount = $totalamount - $discountamount;

                        $taxamountsgst = ($totalamount/100) * $sgst;
                        $taxamountcgst = ($totalamount/100) * $cgst;
                        $taxamountigst = ($totalamount/100) * $igst;

                        if ($user[0]['state'] == $owner[0]['state'])
                            $taxamount = $taxamountsgst + $taxamountcgst;
                        else
                            $taxamount = $taxamountigst;

                        $grandtotal = $totalamount +  + $taxamount;

                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"></td>
                            <td colspan="1">Discount <?php echo $discount ?> %</td>
                            <td id ="subtotal"><?php echo $discountamount ?> &#8377;</td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                            <td colspan="1">SUBTOTAL</td>
                            <td id ="subtotal"><?php echo $totalamount ?> &#8377;</td>
                        </tr>
                        <?php
                        if($user[0]['state'] == $owner[0]['state']){
                        ?>
                        <tr>
                            <td colspan="6"></td>
                            <td id ="tax" colspan="1">CGST <?php echo $sgst ?>%</td>
                            <td><?php echo $taxamountsgst ?> &#8377;</td>
                        </tr>
                        <tr>
                            <td colspan="6"></td>
                            <td id ="tax" colspan="1">SGST <?php echo $cgst ?>%</td>
                            <td><?php echo $taxamountcgst ?> &#8377;</td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td colspan="6"></td>
                            <td id ="tax" colspan="1">IGST <?php echo $igst ?>%</td>
                            <td><?php echo $taxamountigst ?> &#8377;</td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="6"></td>
                            <td colspan="1">Grand Total</td>
                            <td id="grandtotal"><?php echo $grandtotal ?> &#8377;</td>
                        </tr>
                    </tfoot>
                </table>
                <!-- <div class="thanks">Thank you!</div>
                <div class="notices">
                    <div>NOTICE:</div>
                    <div class="notice">A finance charge of 1.5% will be made on unpaid balances after 30 days.</div>
                </div> -->
            </main>
            <footer>
                Invoice was created Auotmatically and is valid without the signature and seal.
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
</body>
</html>