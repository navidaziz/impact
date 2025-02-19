<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mockup</title>


    <!-- Bootstrap 4 CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- jQuery (Must be loaded before Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Popper.js (Required for Bootstrap tooltips, popovers, and modals) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Include Highcharts library from CDN -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/heatmap.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>



</head>
<style>
    .table_small2>tbody>tr>td,
    .table_small2>tbody>tr>th,
    .table_small2>tfoot>tr>td,
    .table_small2>tfoot>tr>th,
    .table_small2>thead>tr>td,
    .table_small2>thead>tr>th {
        padding: 1px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
        font-size: 10px;
        text-align: center;
        border: 0.1px solid lightgray !important;
        font-weight: bold !important;
        color: black !important;
    }

    .table_small>tbody>tr>td,
    .table_small>tbody>tr>th,
    .table_small>tfoot>tr>td,
    .table_small>tfoot>tr>th,
    .table_small>thead>tr>td,
    .table_small>thead>tr>th {
        padding: 2px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
        font-size: 9px;
        text-align: center;
        border: 0.1px solid gray !important;
        font-weight: bold !important;
        color: black !important;
    }

    .table_medium2>tbody>tr>td,
    .table_medium2>tbody>tr>th,
    .table_medium2>tfoot>tr>td,
    .table_medium2>tfoot>tr>th,
    .table_medium2>thead>tr>td,
    .table_medium2>thead>tr>th {
        padding: 2px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
        font-size: 16px;
        text-align: center;
        border: 0.1px solid gray !important;
        font-weight: bold !important;
        color: black !important;

    }

    .table_medium>tbody>tr>td,
    .table_medium>tbody>tr>th,
    .table_medium>tfoot>tr>td,
    .table_medium>tfoot>tr>th,
    .table_medium>thead>tr>td,
    .table_medium>thead>tr>th {
        padding: 2px;
        line-height: 1.42857143;
        vertical-align: top;
        border-top: 1px solid #ddd;
        font-size: 13px;
        text-align: center;
        border: 0.1px solid gray !important;
        font-weight: bold !important;
        color: black !important;

    }

    .container,
    .container-lg,
    .container-md,
    .container-sm,
    .container-xl {
        max-width: 100%;
    }

    .female {
        background-color: #FFB1CB;
        width: 100%;
        display: block;
        margin-top: 2px;
        padding: 2px;
        color: #fff;

    }

    .male {
        background-color: #01A6EA;
        width: 100%;
        display: block;
        margin-top: 2px;
        padding: 2px;
        color: #fff;
    }

    .female_male {
        background-color: #FFCC10;
        width: 100%;
        display: block;
        margin-top: 1px;
        padding: 2px;
        color: #fff;
    }
</style>
<style>
    .dashboard-box {
        background-color: #f9f9f9;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        text-align: center;
        padding: 5px;
        margin: 10px 0;
        transition: transform 0.2s;
    }

    .dashboard-box:hover {
        transform: scale(1.05);
    }

    .dashboard-box h3 {
        margin: 0;
        font-size: 10px;
        font-weight: bold;
        color: #333;
    }

    .dashboard-box p {
        font-size: 14px;
        color: #777;
    }

    .dashboard-box .count {
        font-size: 15px;
        font-weight: bold;
        color: #2c3e50;
    }
</style>

<!-- Modal -->

<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document" style="width: 60%;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title" style="display: inline;"></h4>
                <button type="button" class="close pull-right" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal_body">
                ...
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<body>



    <!-- Dashboard Content -->
    <div class="container" style="padding-top: 5px;">
        <div class="row">

            <div class="col-md-2">
                <div class="dashboard-box">
                    <h3><?php echo $title; ?></h3>
                    <p class="count"><?php echo $description; ?></p>
                    <div>

                        <a href="<?php echo site_url(ADMIN_DIR . "home/index"); ?>" class="btn btn-link"><i class="fa fa-home"></i> Home</a>
                        <a href="<?php echo site_url(ADMIN_DIR . "login/logout"); ?>" class="btn btn-link"><i class="fa fa-sign-out"></i> Logout</a>

                    </div>
                </div>
                <style>
                    .list-group-item {
                        cursor: pointer;
                    }
                </style>
                <ul class="list-group">
                    <li class="list-group-item active" id="quarterly_field_visits_list" onclick="get_report('quarterly_field_visits')">Surveys</li>
                    <li class="list-group-item" id="issues_and_damages_schemes_list" onclick="get_report('issues_and_damages_schemes')">Issues and Damages</li>
                    <li class="list-group-item" id="irrigated_cca_list" onclick="get_report('irrigated_cca')">Irrigated CCA AVG</li>
                    <li class="list-group-item" id="crop_yield_list" onclick="get_report('crop_yield')">Crop Yield</li>
                    <li class="list-group-item" id="cropping_pattern_list" onclick="get_report('cropping_pattern')">Cropping Pattern</li>

                </ul>

            </div>


            <div class="col-md-10">
                <div id="home"></div>
            </div>


        </div>
        <div class="row">

        </div>

    </div>
    </div>

    <script>
        function get_report(funcation_name) {
            // Remove 'active' class from all list items
            $('.list-group-item').removeClass('active');

            // Activate the selected list item
            $('#' + funcation_name + '_list').addClass('active');

            $('#' + funcation_name).html('<h5 style="text-align: center;" class="linear-background"></h5>');
            var checkedFYIds = [];
            var checkboxes = document.querySelectorAll('.fy_id');
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const fyId = checkbox.getAttribute('name').match(/\d+/)[0];
                    checkedFYIds.push(fyId);
                }
            });
            $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('admin/impact_analysis/'); ?>" + funcation_name,
                    data: {
                        fy_ids: checkedFYIds
                    }
                })
                .done(function(respose) {
                    //$('#' + funcation_name).html(respose);
                    $('#home').html(respose);
                });
        }

        function filter_data() {




        }
        //filter_data();
        get_report("quarterly_field_visits");
    </script>

    <script>
        function makeFullScreen(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                if (element.requestFullscreen) {
                    element.requestFullscreen();
                } else if (element.mozRequestFullScreen) { // Firefox
                    element.mozRequestFullScreen();
                } else if (element.webkitRequestFullscreen) { // Chrome, Safari, Opera
                    element.webkitRequestFullscreen();
                } else if (element.msRequestFullscreen) { // IE/Edge
                    element.msRequestFullscreen();
                }
            } else {
                console.warn(`Element with id "${elementId}" not found.`);
            }
        }
    </script>

</body>

</html>