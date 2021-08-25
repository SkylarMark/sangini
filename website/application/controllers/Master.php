<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {
    
    // Load Pages
        public function product(){
            if (!$this->session->userdata('username'))
            {
                $this->load->view('login');
            }
            else
            {
                $this->load->view('admin/master/productMaster');
            }
        }

        public function cloth(){
            if (!$this->session->userdata('username'))
            {
                $this->load->view('login');
            }
            else
            {
                $this->load->view('admin/master/clothMaster');
            }
        }

        public function size(){
            if (!$this->session->userdata('username'))
            {
                $this->load->view('login');
            }
            else
            {
                $this->load->view('admin/master/sizeMaster');
            }
        }

        public function color(){
            if (!$this->session->userdata('username'))
            {
                $this->load->view('login');
            }
            else
            {
                $this->load->view('admin/master/colorMaster');
            }
        }

        public function discount(){
            if (!$this->session->userdata('username'))
            {
                $this->load->view('login');
            }
            else
            {
                $this->load->view('admin/master/discount');
            }
        }

    // Get Product Table
        public function getproductData(){

                $url = api_url.'tables';

                $fields = array(
                    'username' => $this->session->userdata('username'),
                    'api_key' => $this->session->userdata('api_key'),
                    'table' => 'product_master',
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
                    foreach($result['data'] as $row){ 
                        echo '
                            <tr>
                                <td scope="row">'.$row['product_id'].'</td>
                                <td>'.$row['product_name'].'</td>
                                <td>
                                <div class="row">
                                    <div class="mr-3">
                                        <button data-toggle="tooltip" title="Update" id="'.$row['product_id'].'" class="btn btn-outline-primary btn-sm" type="button" onclick="updateProduct(this.id)">
                                            <i class="fas fa-user"></i>
                                        </button>
                                    </div>
        
                                    <div class="mr-3">
                                        <button data-toggle="tooltip" title="Delete" id="'.$row['product_id'].'" onclick="deleteProduct(this.id)"  class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
        
                                </td>
                            </tr>';
                        }
                }
                else
                {
                echo '<td colspan="3" class="bg-info text-center" style="color: white; font-size: 20px;">Add Some Data First</td>';
                }

                //close connection
                curl_close($ch);

            
        }

        public function getclothData(){

            $url = api_url.'tables';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'cloth_master',
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
                foreach($result['data'] as $row){ 
                    echo '
                        <tr>
                            <td scope="row">'.$row['cloth_id'].'</td>
                            <td>'.$row['cloth_name'].'</td>
                            <td>
                            <div class="row">
                                <div class="mr-3">
                                    <button data-toggle="tooltip" title="Update" id="'.$row['cloth_id'].'" class="btn btn-outline-primary btn-sm" type="button" onclick="updateProduct(this.id)">
                                        <i class="fas fa-user"></i>
                                    </button>
                                </div>

                                <div class="mr-3">
                                    <button data-toggle="tooltip" title="Delete" id="'.$row['cloth_id'].'" onclick="deleteProduct(this.id)"  class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            </td>
                        </tr>';
                    }
            }
            else
            {
            echo '<td colspan="3" class="bg-info text-center" style="color: white; font-size: 20px;">Add Some Data First</td>';
            }

            //close connection
            curl_close($ch);

        
        }

        public function getsizeData(){

            $url = api_url.'tables';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'size_master',
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
                foreach($result['data'] as $row){ 
                    echo '
                        <tr>
                            <td scope="row">'.$row['size_id'].'</td>
                            <td>'.$row['size_code'].'</td>
                            <td>
                            <div class="row">
                                <div class="mr-3">
                                    <button data-toggle="tooltip" title="Update" id="'.$row['size_id'].'" class="btn btn-outline-primary btn-sm" type="button" onclick="updateProduct(this.id)">
                                        <i class="fas fa-user"></i>
                                    </button>
                                </div>

                                <div class="mr-3">
                                    <button data-toggle="tooltip" title="Delete" id="'.$row['size_id'].'" onclick="deleteProduct(this.id)"  class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            </td>
                        </tr>';
                    }
            }
            else
            {
            echo '<td colspan="3" class="bg-info text-center" style="color: white; font-size: 20px;">Add Some Data First</td>';
            }

            //close connection
            curl_close($ch);

        
        }
        
        public function getcolorData(){

            $url = api_url.'tables';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'color_master',
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
                foreach($result['data'] as $row){ 
                    echo '
                        <tr>
                            <td scope="row">'.$row['color_id'].'</td>
                            <td>'.$row['color_name'].'</td>
                            <td>'.$row['color_code'].'</td>
                            <td>
                                <div class="row">
                                    <div class="mr-3">
                                        <button data-toggle="tooltip" title="Update" id="'.$row['color_id'].'" class="btn btn-outline-primary btn-sm" type="button" onclick="updateProduct(this.id)">
                                            <i class="fas fa-user"></i>
                                        </button>
                                    </div>

                                    <div class="mr-3">
                                        <button data-toggle="tooltip" title="Delete" id="'.$row['color_id'].'" onclick="deleteProduct(this.id)"  class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>';
                    }
            }
            else
            {
            echo '<td colspan="4" class="bg-info text-center" style="color: white; font-size: 20px;">Add Some Data First</td>';
            }

            //close connection
            curl_close($ch);

        
        }

        public function getdiscountData(){
            
            $page = $this->uri->segment(3);

            $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

            $show = trim((int)$_POST['show']);

            $entries = ($page - 1) * $show;

            $url = api_url.'viewtable';
            $getRows = api_url.'getRows';

            $array = ["*"];
            $table = ["`discount_master`"];

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'type' => 'view',
                'table' => $table,
                'feilds' => $array,
                'orderby' => 'username',
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
                            <td>'.$row['username'].'</td>
                            <td>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0" class="form-control" placeholder="Price" id="'.$row['username'].'_discount" value="'.$row['discount'].'" autofill="off">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                    <button data-toggle="tooltip" title="Update Discount" class="btn btn-info input-group-text" id="'.$row['username'].'" onclick="this.disabled=true; updateDiscount(this.id)"><i class="fas fa-check"></i></button>
                                </div>
                            </div>
                            </td>
                        ';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="1" class="text-left">
                        <input type="hidden" id="currentpagetomax" value="'.$max.'">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        <span id="currentpage">'. $page.'</span>
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 1, '.$max.', 10)"><i class="fas fa-caret-right" style="color: white"></i></button>
                    </td>
                    </td>
                    <td colspan="1">
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

    // Add, Update, Delete
        // Product Table
        public function addData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'product',
                'type' => 'add',
                'product_name' => ucfirst($_POST['product_name']),
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
        }

        public function updateData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'product',
                'type' => 'update',
                'product_id' => $_POST['product_id'],
                'product_name' => ucfirst($_POST['product_name']),
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
        }

        public function deleteData(){
            
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'product',
                'type' => 'delete',
                'product_id' => $_POST['product_id'],
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
        }

        // Cloth Table
        public function addclothData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'cloth',
                'type' => 'add',
                'cloth_name' => ucfirst($_POST['cloth_name']),
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
        }

        public function updateclothData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'cloth',
                'type' => 'update',
                'cloth_id' => $_POST['cloth_id'],
                'cloth_name' => ucfirst($_POST['cloth_name']),
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
        }

        public function deleteclothData(){
            
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'cloth',
                'type' => 'delete',
                'cloth_id' => $_POST['cloth_id'],
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
        }

        // Color Table
        public function addcolorData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'color',
                'type' => 'add',
                'color_name' => ucfirst($_POST['color_name']),
                'color_code' => strtoupper($_POST['color_code']),
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
        }

        public function updatecolorData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'color',
                'type' => 'update',
                'color_id' => $_POST['color_id'],
                'color_name' => ucfirst($_POST['color_name']),
                'color_code' => strtoupper($_POST['color_code']),
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
        }

        public function deletecolorData(){
            
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'color',
                'type' => 'delete',
                'color_id' => $_POST['color_id'],
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
        }

        // size Table
        public function addsizeData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'size',
                'type' => 'add',
                'size_code' => strtoupper($_POST['size_code']),
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
        }

        public function updatesizeData(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'size',
                'type' => 'update',
                'size_id' => $_POST['size_id'],
                'size_code' => strtoupper($_POST['size_code']),
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
        }

        public function deletesizeData(){
            
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'size',
                'type' => 'delete',
                'size_id' => $_POST['size_id'],
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
        }

        // Discount Table
        public function updateDiscount(){
            $url = api_url.'curd';

            $fields = array(
                'username' => $this->session->userdata('username'),
                'api_key' => $this->session->userdata('api_key'),
                'table' => 'discount',
                'type' => 'update',
                'changeusername' => $_POST['change_username'],
                'discount' => strtoupper($_POST['discount']),
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
        }
}
