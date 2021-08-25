<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends CI_Controller {

	public function login(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/logs/login');
        }
    }
    
    public function loadlogin(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = ["*"];
        $table = ["`login_log`"];

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
                if ($row['role'] == 0)
                    $role = 'Admin';
            
                if ($row['role'] == 1)
                    $role = 'User';
                echo '
                    <tr>
                        <td>'.$row['id'].'</td>
                        <td>'.$row['username'].'</td>
                        <td>'.$row['timein'].'</td>
                        <td>'.$row['timeout'].'</td>
                        <td>'.$row['current_status'].'</td>
                        <td>'.$row['latitude'].'</td>
                        <td>'.$row['longitude'].'</td>
                        <td>'.$row['os'].'</td>
                        <td>'.$role.'</td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="5" class="text-left">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        '. $page.'
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 1, '.$max.', 10)"><i class="fas fa-caret-right" style="color: white"></i></button>
                    </td>
                    <td colspan="4">
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

    public function register(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/logs/register');
        }
    }

    public function loadregister(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = [
            "username",            
            "DATE_FORMAT(timestamp, '%d-%m-%Y') as Date",
            "Time(timestamp) as Time"
        ];
        $table = ["`register_log`"];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'timestamp',
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
                        <td>'.$row['Date'].'</td>
                        <td>'.$row['Time'].'</td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="2" class="text-left">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        '. $page.'
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
    
    public function product(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/logs/products');
        }
    }

    public function loadproduct(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = [
            "id",
            "username",
            "quantity",
            "price",
            "product_id",       
            "DATE_FORMAT(timestamp, '%d-%m-%Y') as Date",
            "Time(timestamp) as Time"
        ];
        $table = ["`product_log`"];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'timestamp',
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
                        <td>'.$row['id'].'</td>
                        <td>'.$row['username'].'</td>
                        <td>'.$row['price'].'</td>
                        <td>'.$row['quantity'].'</td>
                        <td>'.$row['product_id'].'</td>
                        <td>'.$row['Date'].'</td>
                        <td>'.$row['Time'].'</td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="6" class="text-left">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        '. $page.'
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
    
    public function error(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/logs/error');
        }
    }
    
    public function loaderror(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = [
            "error_id",
            "error",
            "location",
            "user",       
            "DATE_FORMAT(timestamp, '%d-%m-%Y') as Date",
            "Time(timestamp) as Time"
        ];
        $table = ["`error_log`"];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'timestamp',
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
                        <td>'.$row['error_id'].'</td>
                        <td>'.$row['user'].'</td>
                        <td>'.$row['error'].'</td>
                        <td>'.$row['location'].'</td>
                        <td>'.$row['Date'].'</td>
                        <td>'.$row['Time'].'</td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="5" class="text-left">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        '. $page.'
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

    public function orders(){
		if (!$this->session->userdata('username'))
        {
			redirect(base_url()."login");
        }
        else
        {
			$this->load->view('admin/logs/orders');
        }
    }
    
    public function loadorders(){

        $page = $this->uri->segment(3);

        $page == 0 ? $page = 1 : $page = $this->uri->segment(3);

        $show = trim((int)$_POST['show']);

        $entries = ($page - 1) * $show;

        $url = api_url.'viewtable';
        $getRows = api_url.'getRows';

        $array = [
            "order_id",
            "username",
            "total_amount",
            "order_status",
            "order_status_name",       
            "DATE_FORMAT(order_add_timestamp, '%d-%m-%Y') as Date",
            "Time(order_add_timestamp) as Time"
        ];
        $table = ["`orders`","JOIN `order_status_master` ON order_status_master.order_status_id = orders.order_status", "WHERE order_status > 2"];

        $fields = array(
            'username' => $this->session->userdata('username'),
            'api_key' => $this->session->userdata('api_key'),
            'type' => 'view',
            'table' => $table,
            'feilds' => $array,
            'orderby' => 'order_add_timestamp',
            'sort' => 'DESC',
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
                        <td>'.$row['order_id'].'</td>
                        <td>'.$row['username'].'</td>
                        <td>'.$row['total_amount'].'</td>
                        <td>'.$row['Date'].'</td>
                        <td>'.$row['Time'].'</td>
                        <td>'.$row['order_status_name'].'</td>
                        <td>
                        <div class="mr-3">
                            <a data-toggle="tooltip" title="Print Order" id="'.$row['order_id'].'" href="'.base_url().'commerce/printBill/'.$row['order_id'].'"  class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-print"></i>
                            </a>
                        </div>
                        </td>
                    </tr>';
                }
                echo '
                <tr class="bg-dark" style="color:white">
                    <td colspan="6" class="text-left">
                        <button class="btn mr-3 ml-3" id = "'.$page.'" onclick="load_table_paged(this.id, 0, '.$max.', 10)"><i class="fas fa-caret-left" style="color: white"></i></button>
                        '. $page.'
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
}