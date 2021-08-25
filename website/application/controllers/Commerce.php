<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Commerce extends CI_Controller {

    // Product
	public function productlist(){
		if (!$this->session->userdata('username'))
        {
            redirect(base_url()."login");
        }
        else
        {
            $productName =  $this->getProductName('product_master', 'product_id', 'product_name');
            $Product_Cloth = $this->getProductName('cloth_master', 'cloth_id', 'cloth_name');
            $Product_Color = $this->getProductName('color_master', 'color_id', 'color_name');
            $Product_Size = $this->getProductName('size_master', 'size_id', 'size_code');

            if ($productName && $Product_Cloth && $Product_Color && $Product_Size )
            {
                $data = array(
                    'product_id' => $productName['id'],
                    'product_name' => $productName['name'],
    
                    'product_cloth_id' => $Product_Cloth['id'],
                    'product_cloth_name' => $Product_Cloth['name'],
    
                    'product_color_id' => $Product_Color['id'],
                    'product_color_name' => $Product_Color['name'],
    
                    'product_size_id' => $Product_Size['id'],
                    'product_size_name' => $Product_Size['name'],
                );
    
                $this->load->view('admin/ecommerce/products', $data);
            }
            else
            {
                $this->load->view('errors/masterMissing');
            }
        }
    }

    // Get Dropdown on Product List' Add Function
    private function getProductName($table, $tableid, $tablename){

        $url = api_url.'tables';

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'table' => $table,
        );

        $data = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        if (!$result['error'])
        {
            $id = array();
            $name = array();

            foreach($result['data'] as $row){
                array_push($id, $row[$tableid]);
                array_push($name, $row[$tablename]);
            }

            $data = array(
               'id' => $id,
               'name' => $name
            );

            return $data;
        }
        else
        {
            return false;
        }

        //close connection
        curl_close($ch);
    }

    public function loadtable(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = ["id,product_master.product_name as Product","cloth_master.cloth_name as Cloth","color_master.color_name as Color","size_master.size_code as Size","price as Price","quantity as Quantity"];
        $table = [
        "`product_list`",
        "JOIN product_master ON product_list.product_id = product_master.product_id",
        "JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id",
        "JOIN color_master ON product_list.color_id = color_master.color_id",
        "JOIN size_master ON product_list.size_id = size_master.size_id",
        ];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'id',
            'sort' => 'ASC',
            'limit' => $entries,
            'show' => $show,
        );

        $data = http_build_query($fields);
        
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $getRows);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //execute post
        $RowCount = curl_exec($ch);

        $RowCount = json_decode($RowCount, true);

        (int)$max = $RowCount['rows']/$show;

        if (($max * $show) - ((int)$max * $show) > 0)
            $max = (int)$max + 1;
        else
            $max = (int)$max;

        if (!$result['error']){
            foreach($result['data'] as $row){ 
                echo '
                    <tr>
                        <td scope="row">'.$row['id'].'</td>
                        <td>'.$row['Product'].'</td>
                        <td>'.$row['Cloth'].'</td>
                        <td>'.$row['Color'].'</td>
                        <td>'.$row['Size'].'</td>
                        <td>
                        <div class="input-group input-group-sm">
                            <input type="number" step="0" class="form-control" placeholder="Price" id="'.$row['id'].'_price" value="'.$row['Price'].'" autofill="off">
                            <div class="input-group-append">
                                <span class="input-group-text">&#x20a8;</span>
                                <button data-toggle="tooltip" title="Update Price" class="btn btn-info input-group-text" id="'.$row['id'].'" onclick="this.disabled=true; updatePrice(this.id)"><i class="fas fa-check"></i></button>
                            </div>
                        </div>
                        </td>

                        <td>

                        <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="'.$row['id'].'_cstock">'.$row['Quantity'].'</span>
                            <span class="input-group-text">  +  </span>
                        </div>
                            <input type="number" step="0" class="form-control" placeholder="" id="'.$row['id'].'_stock" value="0" autofill="off" style="width: 4vw">
                                <div class="input-group-append">
                                    <button data-toggle="tooltip" title="Update Stock" class="btn btn-info input-group-text" id="'.$row['id'].'" onclick="this.disabled=true; updateStock(this.id)"><i class="fas fa-check"></i></button>
                                </div>
                            </div>
                        </td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="6" class="text-left">
                        <input type="hidden" id="currentpagetomax" value="'.$max.'">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        <span id="currentpage">'. $page.'</span>
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 1, '.$max.', 10)"><i class="fas fa-caret-right" style="color: white"></i></button>
                    </td>
                    <td>
                        <select class="form-control text-right" id="selectShowRows" onchange="load_table_paged(1, 2, '.$max.', this.value)">
                            <option value="'.$show.'" disabled selected>Records Per Page</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="99999">All</option>
                        </select>
                    </td>

                </tr>';
        }
        else
        {
            echo '<td colspan="7" class="bg-info text-center" style="color: white; font-size: 20px;">Add Some Data First</td>';
        }

        //close connection
        curl_close($ch);
    }

    // Cart
    public function cart(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/ecommerce/cart');
        }
    }

    public function loadcart(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = [
            "cart_id as id",
            "username",
            "product_list.id as ProductID",
            "cart.quantity as Quantity",
            "amount as Amount",
            "product_list.price as Price",
            "product_master.product_name as Product",
            "cloth_master.cloth_name as Cloth",
            "color_master.color_name as Color",
            "color_master.color_code as Color_Code",
            "size_master.size_code as Size"
        ];
        
        $table = [
            "`cart`",
            "JOIN `product_list` ON product_list.id = cart.product_list_id",
            "JOIN product_master ON product_list.product_id = product_master.product_id",
            "JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id ",
            "JOIN color_master ON product_list.color_id = color_master.color_id",
            "JOIN size_master ON product_list.size_id = size_master.size_id",
        ];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'cart_id',
            'sort' => 'ASC',
            'limit' => $entries,
            'show' => $show,
        );

        $data = http_build_query($fields);
        
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $getRows);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //execute post
        $RowCount = curl_exec($ch);

        $RowCount = json_decode($RowCount, true);

        (int)$max = $RowCount['rows']/$show;

        if (($max * $show) - ((int)$max * $show) > 0)
            $max = (int)$max + 1;
        else
            $max = (int)$max;

        if (!$result['error']){
            foreach($result['data'] as $row){ 
                echo '
                    <tr>
                        <td scope="row">'.$row['id'].'</td>
                        <td>'.$row['ProductID'].'</td>
                        <td>'.$row['username'].'</td>
                        <td>'.$row['Product'].'</td>
                        <td>'.$row['Cloth'].'</td>
                        <td>'.$row['Color'].'</td>
                        <td>'.$row['Size'].'</td>
                        <td>'.$row['Quantity'].'</td>
                        <td>'.$row['Price'].'</td>
                        <td>'.$row['Amount'].'</td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="8" class="text-left">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        '. $page.'
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 1, '.$max.', 10)"><i class="fas fa-caret-right" style="color: white"></i></button>
                    </td>
                    <td colspan="2">
                        <select class="form-control text-right" id="selectShowRows" onchange="load_table_paged(1, 2, '.$max.', this.value)">
                            <option value="'.$show.'" disabled selected>Records Per Page</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="99999">All</option>
                        </select>
                    </td>

                </tr>';
        }
        else
        {
            echo '<td colspan="10" class="bg-info text-center" style="color: white; font-size: 20px;">Waiting for User to add to Cart</td>';
        }

        //close connection
        curl_close($ch);
    }
    
    // Orders
    public function orders(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/ecommerce/orders');
        }
    }

    public function loadorder(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        restartpage:
        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = [
            "order_id",
            "order_status",
            "order_status_name",
            "username",
            "total_amount",
            "DATE_FORMAT(order_add_timestamp, '%d-%m-%Y') as Date",
            "Time(order_add_timestamp) as Time"
        ];
        
        $table = ["`orders`","JOIN `order_status_master` ON order_status_master.order_status_id = orders.order_status","WHERE order_status = 0 OR order_status = 1"];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'order_add_timestamp',
            'sort' => 'ASC',
            'limit' => $entries,
            'show' => $show,
        );

        $data = http_build_query($fields);
        
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $getRows);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //execute post
        $RowCount = curl_exec($ch);

        $RowCount = json_decode($RowCount, true);

        // Pagination Settings (Dont Touch)
        (int)$max = $RowCount['rows']/$show;

        if (($max * $show) - ((int)$max * $show) > 0)
            $max = (int)$max + 1;
        else
            $max = (int)$max;

        if ($page > $max)
        {
            $page = $page - 1;
            goto restartpage;
        }
        // -------------------------------------------

        if (!$result['error']){
            foreach($result['data'] as $row){ 

                $accept = '                                
                <div class="mr-3">
                    <button data-toggle="tooltip" title="Accept Order" id="'.$row['order_id'].'" onclick="orderstatus(this.id, 1);"  class="btn btn-sm btn-outline-success">
                        <i class="fas fa-check"></i>
                    </button>
                </div>';

                $delivered = '                                
                <div class="mr-3">
                    <button data-toggle="tooltip" title="Order Delivered" id="'.$row['order_id'].'" onclick="orderstatus(this.id, 3)"  class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-archive"></i>
                    </button>
                </div>';

                echo '
                    <tr>
                        <td>'.$row['order_id'].'</td>
                        <td>'.$row['username'].'</td>
                        <td>'.$row['total_amount'].'</td>
                        <td>'.$row['Date'].'</td>
                        <td>'.$row['Time'].'</td>
                        <td>'.$row['order_status_name'].'</td>
                        <td>
                            <div class="row">
                                <div class="mr-3">
                                    <button data-toggle="tooltip" title="View Order" id="'.$row['order_id'].'" class="btn btn-outline-primary btn-sm" type="button" onclick="viewProduct(this.id)">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </div>';

                                echo $row['order_status'] != 1 ? $accept : $delivered;

                                echo'
                                <div class="mr-3">
                                    <button data-toggle="tooltip" title="Cancel Order" id="'.$row['order_id'].'" onclick="orderstatus(this.id, 5)"  class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>

                                <div class="mr-3">
                                    <a data-toggle="tooltip" title="Print Order" id="'.$row['order_id'].'" href="'.base_url().'commerce/printBill/'.$row['order_id'].'"  class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-print"></i>
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="4" class="text-left">
                        <input type="hidden" id="currentpagetomax" value="'.$max.'">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        <span id="currentpage">'. $page.'</span>
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 1, '.$max.', 10)"><i class="fas fa-caret-right" style="color: white"></i></button>
                    </td>
                    </td>
                    <td colspan="3">
                        <select class="form-control text-right" id="selectShowRows" onchange="load_table_paged(1, 2, '.$max.', this.value)">
                            <option value="'.$show.'" disabled selected>Records Per Page</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="99999">All</option>
                        </select>
                    </td>
                </tr>';
        }
        else
        {
            echo '<td colspan="7" class="bg-info text-center" style="color: white; font-size: 20px;">Waiting for User to Confirm Order</td>';
        }

        //close connection
        curl_close($ch);
    }

    public function loadsingleOrder(){

        $orderid = trim($_POST['order_id']);
        $url = api_url.'viewtable';

        $array = [
            "order_id",
            "order_descriptions.product_list_id",
            "product_name",
            "cloth_name",
            "color_code",
            "size_code",
            "price",
            "order_descriptions.quantity",
            "order_descriptions.order_amount"
        ];
        
        $table = [
            "`order_descriptions`", 
            "JOIN `product_list` ON product_list.id = order_descriptions.product_list_id",
            "JOIN product_master ON product_list.product_id = product_master.product_id",
            "JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id",
            "JOIN color_master ON product_list.color_id = color_master.color_id",
            "JOIN size_master ON product_list.size_id = size_master.size_id",
            "WHERE order_id = '$orderid'",
        ];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'product_list_id',
            'sort' => 'ASC',
            'limit' => '0',
            'show' => '999',
        );

        $data = http_build_query($fields);
        
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        if (!$result['error']){

            foreach($result['data'] as $row){ 
                echo '
                    <tr>
                        <td>'.$row['product_list_id'].'</td>
                        <td>'.$row['product_name'].'</td>
                        <td>'.$row['cloth_name'].'</td>
                        <td>'.$row['color_code'].'</td>
                        <td>'.$row['size_code'].'</td>
                        <td class="text-right">'.$row['price'].'</td>
                        <td class="text-right">'.$row['quantity'].'</td>
                        <td class="text-right">'.$row['order_amount'].'</td>
                    </tr>';
                };

                echo '
                <th class="text-center bg-dark" colspan="8" style="color: white">
                    '.$result['data'][0]['order_id'].'
                </th>
                ';
        }
        else
        {
            // echo $result['data'];
            echo '<td colspan="8" class="bg-info text-center" style="color: white; font-size: 20px;">Add Some Data First</td>';
        }

        //close connection
        curl_close($ch);
    }

    public function orderStatus(){

        $url = api_url.'orderstatus';

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'id' => trim($_POST['id']),
            'value' => trim($_POST['status']),
        );

        $data = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);
    }


    // Add Product List Item
	public function addproduct(){

        $url = api_url.'productlist';

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'add',
            'product_id' => $_POST['product_id'],
            'cloth_id' => $_POST['cloth_id'],
            'color_id' => $_POST['color_id'],
            'size_id' => $_POST['size_id'],
            'price' => $_POST['price'],
        );

        $data = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        echo json_encode($result);

        //close connection
        curl_close($ch);

    
    }

    // Update Price
    public function updatePrice(){

        $url = api_url.'updateprice';

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'id' => $_POST['id'],
            'price' => $_POST['price'],
        );

        $data = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        print_r($result);

        //close connection
        curl_close($ch);

    }

    // Update Stock
    public function updateStock(){

        $url = api_url.'updatestock';

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'id' => $_POST['id'],
            'stock' => $_POST['stock'],
        );

        $data = http_build_query($fields);

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $result = curl_exec($ch);

        $result = json_decode($result, true);

        print_r($result);

        //close connection
        curl_close($ch);

    }

    // Print Bill
    public function printBill(){
        
        $order_id = $this->uri->segment(3);

        $url = api_url.'viewtable';

        // Order Data Product Table
            $array = [
                "order_id",
                "order_descriptions.product_list_id",
                "product_name",
                "cloth_name",
                "color_code",
                "size_code",
                "price",
                "order_descriptions.quantity",
                "order_descriptions.order_amount"
            ];
            
            $table = [
                "`order_descriptions`",
                "JOIN `product_list` ON product_list.id = order_descriptions.product_list_id",
                "JOIN product_master ON product_list.product_id = product_master.product_id",
                "JOIN cloth_master ON product_list.cloth_id = cloth_master.cloth_id",
                "JOIN color_master ON product_list.color_id = color_master.color_id",
                "JOIN size_master ON product_list.size_id = size_master.size_id",
                "WHERE order_id = '$order_id'",
            ];

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'type' => 'view',
                'table' => $table,
                'feilds' => $array,
                'orderby' => 'product_list_id',
                'sort' => 'ASC',
                'limit' => '0',
                'show' => '999',
            );

            $data = http_build_query($fields);
            
            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $result = curl_exec($ch);

            $result = json_decode($result, true);

            //close connection
            curl_close($ch);

        // Order Additional Data 
            $array = [
                "orders.username",
                "discount",
                "DATE_FORMAT(order_add_timestamp, '%d-%m-%Y') as Date",
                "Time(order_add_timestamp) as Time",
            ];
            
            $table = [
                "`orders`",
                "WHERE order_id = '$order_id'",
            ];

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'type' => 'view',
                'table' => $table,
                'feilds' => $array,
                'orderby' => 'order_add_timestamp',
                'sort' => 'ASC',
                'limit' => '0',
                'show' => '999',
            );

            $data = http_build_query($fields);
            
            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $order = curl_exec($ch);

            $order = json_decode($order, true);

            //close connection
            curl_close($ch);

        // User Profile Data 
            $array = ["*"];
            $username = $order['data'][0]['username'];
            $table = [
                "`profile`",
                "JOIN `discount_master` ON discount_master.username = profile.username",
                "JOIN `user_login` ON user_login.username = profile.username",
                "WHERE profile.username = '$username'",
            ];

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'type' => 'view',
                'table' => $table,
                'feilds' => $array,
                'orderby' => 'role',
                'sort' => 'ASC',
                'limit' => '0',
                'show' => '999',
            );

            $data = http_build_query($fields);
            
            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $user = curl_exec($ch);

            $user = json_decode($user, true);

            //close connection
            curl_close($ch);

        // Owner Profile Data

            $array = ["*"];
            $table = ["`owner`"];
            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'type' => 'view',
                'table' => $table,
                'feilds' => $array,
                'orderby' => 'owner_name',
                'sort' => 'ASC',
                'limit' => '0',
                'show' => '999',
            );

            $data = http_build_query($fields);
            
            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $owner = curl_exec($ch);

            $owner = json_decode($owner, true);

        // Config Data

            $array = ["*"];
            $table = ["`config_data`"];
            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'type' => 'view',
                'table' => $table,
                'feilds' => $array,
                'orderby' => 'sgst',
                'sort' => 'ASC',
                'limit' => '0',
                'show' => '1',
            );

            $data = http_build_query($fields);
            
            //open connection
            $ch = curl_init();

            //set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            //execute post
            $config = curl_exec($ch);

            $config = json_decode($config, true);

            //close connection
            curl_close($ch);


        
        $data = array(
            'order_id' => $order_id,
            'order_details' => $result['data'],
            'order_main' => $order['data'],
            'user' => $user['data'],
            'owner' => $owner['data'],
            'config' => $config['data'],
        );

        $this->load->view('admin/modules/bill', $data);
    }
}
