<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cake extends CI_Controller
{

    /**
     * constructor method
     */
    public function __construct()
    {

        parent::__construct();
        $this->lang->load("system", 'english');
        //$this->output->enable_profiler(TRUE);
    }
    //---------------------------------------------------------------


    /**
     * Default action to be called
     */
    public function index()
    {

        $this->data["title"] = 'users';
        $this->data["description"] = 'users';
        // $this->data["view"] = ADMIN_DIR . "cake/index";


        $controller_name = 'scheme_lists';


        $table_name = "scheme_lists";
        $table_name_singular = "scheme_lists";
        $query = "SHOW COLUMNS FROM $table_name";


        $tablecolumns = $this->db->query($query)->result();

        $colums = array();
        $primary_key = '';
        $ignore = array('status', 'order', 'created_by', 'created_date', 'last_updated');
        $count = 1;
        foreach ($tablecolumns as $tablecolumn) {
            if (!in_array($tablecolumn->Field, $ignore)) {
                if ($count == 1) {
                    $primary_key = $tablecolumn->Field;
                } else {
                    $colums[] = $tablecolumn->Field;
                }
                $count++;
            }
        }

        $this->data['add_form'] = $this->add_form($controller_name, $table_name, $table_name_singular, $primary_key, $colums);
        $this->data['list_view'] = $this->list_view_code($controller_name, $table_name, $table_name_singular, $primary_key, $colums);
        $this->data['controller'] = $this->controller_code($controller_name, $table_name, $table_name_singular, $primary_key, $colums);
        //$this->load->view(ADMIN_DIR . "layout", $this->data);
        $this->load->view(ADMIN_DIR . "cake/index", $this->data);
    }

    private function add_form($controller_name, $table_name, $table_name_singular, $primary_key, $colums)
    {
        $add_form = '';
        $add_form .= '
        <form id="' . $table_name . '" class="form-horizontal" enctype="multipart/form-data" method="post">
        <input type="hidden" name="' . $primary_key . '" value="<?php echo $input->' . $primary_key . '; ?>" />
        ';
        foreach ($colums as $colum_name) {

            $type = 'text';
            if (strstr($colum_name, "email")) {
                $type = 'email';
            }
            if (strstr($colum_name, "no") or strstr($colum_name, "tax") or strstr($colum_name, "pay") or strstr($colum_name, "deduction")) {
                $type = 'number';
            }
            if (strstr($colum_name, "date")) {
                $type = 'date';
            }
            if (strstr($colum_name, "gender")) {
                $type = 'radio';
            }
            if ($type == 'radio') {
                $add_form .= '<div class="form-group row">
                            <label for="' . $colum_name . '" class="col-sm-4 col-form-label">' . ucwords(strtolower(str_replace('_', ' ', $colum_name))) . '</label>
                            <div class="col-sm-8">
                            <?php $options = array("Option One", "Option Tow");
                            foreach($options as $option){
                                $checked="";
                                if($option==$input->' . $colum_name . '){
                                    $checked = "checked";
                                }
                            ?>
                            <span style="margin-left:5px"></span>
                            <input <?php echo $checked ?> type="' . $type . '" required  id="<?php echo $option; ?>" name="' . $colum_name . '" value="<?php echo $option; ?>" class="">
                            <span style="margin-left:3px"></span> <?php echo $option;  ?>
                            <?php } ?>
                            </div>
                            </div>
                            ';
            } else {
                $add_form .= '<div class="form-group row">
                            <label for="' . $colum_name . '" class="col-sm-4 col-form-label">' . ucwords(strtolower(str_replace('_', ' ', $colum_name))) . '</label>
                            <div class="col-sm-8">
                            <input type="' . $type . '" required  id="' . $colum_name . '" name="' . $colum_name . '" value="<?php echo $input->' . $colum_name . '; ?>" class="form-control">
                            </div>
                            </div>
                            ';
            }
        }

        $add_form .= '
        <div class="form-group row" style="text-align:center">
        <div id="result_response"></div>
        <?php if ($input->' . $primary_key . ' == 0) { ?>
        <button type="submit" class="btn btn-primary">Add Data</button>
        <?php }else{ ?>
            <button type="submit" class="btn btn-primary">Update Data</button>
            <?php } ?>
        </div>
        </form>
        </div>
        ';
        $add_form .= '
       <script>
        $(\'#' . $table_name . '\').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: \'POST\',
                url: \'<?php echo site_url(ADMIN_DIR . "' . $controller_name . '/add_' . $table_name_singular . '"); ?>\', // URL to submit form data
                data: formData,
                success: function(response) {
                    // Display response
                    if (response == \'success\') {
                        location.reload();
                    } else {
                        $(\'#result_response\').html(response);
                    }
    
                }
            });
        });
     </script>';
        return $add_form;
    }

    private function list_view_code($controller_name, $table_name, $table_name_singular, $primary_key, $colums)
    {
        $list_view = '';
        $list_view .= '
        <div class="table-responsive">
        <table class="table table-bordered" id="' . $controller_name . '">
            <thead>
                <tr>
                <th></th>
                <th>#</th>
                ';
        foreach ($colums as $colum_name) {
            $list_view .= '<th>' . ucwords(strtolower(str_replace('_', ' ', $colum_name))) . '</th>
                    ';
        }
        $list_view .= '<th>Action</th>
        </tr>
            </thead>
            <tbody>
                <?php
                $count=1;
                $query = "SELECT * FROM ' . $table_name . '";
                $rows = $this->db->query($query)->result();
                foreach ($rows as $row) { ?>
                    <tr>
                    <td><a href="<?php echo site_url(ADMIN_DIR . \'' . $controller_name . '/delete_' . $table_name . '/\' . $row->' . $primary_key . '); ?>" onclick="return confirm(\'Are you sure? you want to delete the record.\')">Delete</a> </td>
                    <td><?php echo $count++ ?></td>
                    ';
        foreach ($colums as $colum_name) {
            $list_view .= '<td><?php echo $row->' . $colum_name . '; ?></td>
                        ';
        }
        $list_view .= '<td><button onclick="get_' . $table_name_singular . '_form(\'<?php echo $row->' . $primary_key . '; ?>\')" >Edit<botton></td>';
        $list_view .= '
        </tr>
        <?php } ?>
        </tbody>
                    </table>
                    <div style="text-align: center;">
                        <button onclick="get_' . $table_name_singular . '_form(\'0\')" class="btn btn-primary">Add Record</button>
                    </div>
                </div>
        <script>
            function get_' . $table_name_singular . '_form(' . $primary_key . ') {
                $.ajax({
                        method: "POST",
                        url: "<?php echo site_url(ADMIN_DIR . \'' . $controller_name . '/get_' . $table_name_singular . '_form\'); ?>",
                        data: {
                            ' . $primary_key . ': ' . $primary_key . '
                        },
                    })
                    .done(function(respose) {
                        $(\'#modal\').modal(\'show\');
                        $(\'#modal_title\').html(\'' . ucwords(strtolower(str_replace('_', ' ', $table_name))) . '\');
                        $(\'#modal_body\').html(respose);
                    });
            }
        </script>';
        $list_view .= '
        <table id="datatable" class="table  table_small table-bordered">
        <thead>
            <tr>
            <th>#</th>
            ';
        foreach ($colums as $colum_name) {
            $list_view .= '<th>' . ucwords(strtolower(str_replace('_', ' ', $colum_name))) . '</th>
                    ';
        }
        $list_view .= '
        <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
        </table>

        <script type="text/javascript">
            $(document).ready(function() {
                document.title = "' . $table_name . ' (Date:<?php echo date(\'d-m-Y h:m:s\') ?>)";
                $("#datatable").DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo base_url(ADMIN_DIR."' . $controller_name . '/' . $table_name . '"); ?>",
                        "type": "POST"
                    },
                    "columns": [
                        {
                            "data": null,
                            "render": function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1; // Start index from 1
                            }
                        },
                        ';
        foreach ($colums as $colum_name) {
            $list_view .= '
                { "data": "' . $colum_name . '" },
                ';
        }
        $list_view .= '

        {
            "data": null,
            "render": function(data, type, row) {
                return \'<a class="llink llink-view" href="<?php echo site_url(ADMIN_DIR . "' . $controller_name . '/trash/"); ?>\' + row.' . $primary_key . ' + \'/\' + \'" onclick="return confirm(\'Are you sure? you want to delete the record.\')"><i class="fa fa-trash-o"></i></a><span style="margin-left: 10px;"></span>\' +
                    \'<a class="llink llink-view" href="<?php echo site_url(ADMIN_DIR . "' . $controller_name . '/view_' . $table_name . '/"); ?>\' + row.' . $primary_key . ' + \'/\' + \'"><i class="fa fa-eye"></i></a><span style="margin-left: 10px;"></span>\' +
                    \'<a class="llink llink-edit" href="<?php echo site_url(ADMIN_DIR . "' . $controller_name . '/edit/"); ?>\' + row.' . $primary_key . ' + \'/\' + \'"><i class="fa fa-pencil-square-o"></i></a>\';
            }
        }

                    ],
                    "lengthMenu": [
                        [15, 25, 50, -1],
                        [15, 25, 50, "All"]
                    ],
                    "order": [
                        [0, "asc"]
                    ],
                    "searching": true,
                    "paging": true,
                    "info": true,
                    dom: "Bfrtip",

                    buttons: ["excel", "pageLength"]
                });
            });
        </script>
        ';
        return $list_view;
    }


    private function controller_code($controller_name, $table_name, $table_name_singular, $primary_key, $colums)
    {
        $controller = '';

        $controller .= '
        private fucntion get_inputs(){
            $input["' . $primary_key . '"] = $this->input->post("' . $primary_key . '");
            ';

        foreach ($colums as $colum_name) {
            $controller .= '$input["' . $colum_name . '"] = $this->input->post("' . $colum_name . '");
            ';
        }

        $controller .= '$inputs =  (object) $input;
        return $inputs;
        }
        ';
        $controller .= ' 
        public function get_' . $table_name_singular . '_form(){
        $' . $primary_key . ' = (int) $this->input->post("' . $primary_key . '");
        if ($' . $primary_key . ' == 0) {
            ';

        $controller .= '
        $input = $this->get_inputs();
           } else {
            $query = "SELECT * FROM 
            ' . $table_name . ' 
            WHERE ' . $primary_key . ' = $' . $primary_key . '";
            $input = $this->db->query($query)->row();
            }
            $this->data["input"] = $input;
            $this->load->view(ADMIN_DIR . "' . $controller_name . '/get_' . $table_name_singular . '_form", $this->data);
            }';

        $controller .= '
        public function add_' . $table_name_singular . '()
        {
            ';

        foreach ($colums as $colum_name) {

            $controller .= '$this->form_validation->set_rules("' . $colum_name . '", "' . ucwords(strtolower(str_replace('_', ' ', $colum_name))) . '", "required");
                ';
        }
        $controller .= '
            if ($this->form_validation->run() == FALSE) {
                echo \'<div class="alert alert-danger">\' . validation_errors() . "</div>";
                exit();
            } else {
                $inputs = $this->get_inputs();
        ';
        $controller .= ' $inputs["created_by"] = $this->session->userdata("userId");
        $' . $primary_key . ' = (int) $this->input->post("' . $primary_key . '");
                if ($' . $primary_key . ' == 0) {
                    $this->db->insert("' . $table_name . '", $inputs);

                } else {
                    $this->db->where("' . $primary_key . '", $' . $primary_key . '); 
                    $inputs["last_updated"] = date(\'Y-m-d H:i:s\');
                    $this->db->update("' . $table_name . '", $inputs);
                }
                echo "success";
            }
        }
        public function delete_' . $table_name_singular . '($' . $primary_key . ')
        {
            $' . $primary_key . ' = (int) $' . $primary_key . ';
            $this->db->where("' . $primary_key . '", $' . $primary_key . ');
            $this->db->delete("' . $table_name . '");
            $requested_url = isset($_SERVER[\'HTTP_REFERER\']) ? $_SERVER[\'HTTP_REFERER\'] : base_url();
            redirect($requested_url);
        }
        ';
        $controller .= '
    }

    public function fetch_data() {
        ';

        foreach ($colums as $colum_name) {
            $controller .= '$columns[] = "' . $colum_name . '";
            ';
        }

        $controller .= '
        
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];

        $this->db->select("*");
        $this->db->from("' . $table_name . '");

        $search = $this->db->escape("%" . $this->input->post("search")["value"] . "%");
        if (!empty($this->input->post("search")["value"])) {
            $this->db->group_start();
            foreach($columns as $column) {
                $this->db->or_like($column, $search);
            }
            $this->db->group_end();
        }

        // Ordering
        $this->db->order_by($order, $dir);

        // Pagination
        if ($limit != -1) {
            $sql .= " LIMIT $limit OFFSET $start";
        }
        $query = $this->db->get();
        $data = $query->result();

        // Total records count
        $total_records = $this->db->count_all_results("' . $table_name . '");

        $output = array(
            "draw" => intval($this->input->post("draw")),
            "recordsTotal" => $total_records,
            "recordsFiltered" => $total_records,
            "data" => $data
        );

        echo json_encode($output);
    }
    ';

        $controller .= '
        public function ' . $table_name . '() {
            ';

        foreach ($colums as $colum_name) {
            $controller .= '$columns[] = "' . $colum_name . '";
            ';
        }

        $controller .= '
        
        $limit = $this->input->post("length");
        $start = $this->input->post("start");
        $order = $columns[$this->input->post("order")[0]["column"]];
        $dir = $this->input->post("order")[0]["dir"];

        $search = $this->db->escape("%".$this->input->post("search")["value"]."%");
            // Manual SQL query building
            $sql = "SELECT * FROM ' . $table_name . '";
            
            // Searching
            if(!empty($this->input->post("search")["value"])) {
                $sql .= " WHERE ";
                foreach($columns as $column) {
                    $sql .= "$column LIKE $search OR ";
                }
                $sql = rtrim($sql, "OR "); // Remove the last "OR"
            }
    
            // Ordering
            $sql .= " ORDER BY $order $dir";
    
            // Pagination
            if ($limit != -1) {
            $sql .= " LIMIT $limit OFFSET $start";
            }
    
            $query = $this->db->query($sql);
            $data = $query->result();
    
            // Total records count
            $total_records = $this->db->query("SELECT COUNT(*) as count FROM ' . $table_name . '")->row()->count;
    
            $output = array(
                "draw" => intval($this->input->post("draw")),
                "recordsTotal" => $total_records,
                "recordsFiltered" => $total_records,
                "data" => $data
            );
    
            echo json_encode($output);
        }
    ';
        return $controller;
    }
}
