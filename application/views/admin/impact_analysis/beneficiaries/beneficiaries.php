<h4></h4>


<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis: Beneficiaries</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Beneficiaries'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Beneficiaries Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard' ], ['Summary', 'Crop & Component Wise' , 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>
<hr />
<?php
$query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
$regions = $this->db->query($query)->result();
?>



<?php
$query = "SELECT `region` FROM `impact_surveys` 
GROUP BY `region` ASC;";
$regions_result = $this->db->query($query);
$regions = $regions_result->result();
$query = "SELECT `component` FROM `impact_surveys` 
GROUP BY `component` ORDER BY `component` ASC";
$components_result = $this->db->query($query);
$components = $components_result->result();
?>
<div class="row">
    <div class="col-md-6">
        <?php
        $chartData = array();
        $categories = array();
        $beforeData = array();
        $afterData = array();
        $increaseData = array();
        $perIncreaseData = array();

        $house_holds = $total_beneficiaries = $male_beneficiaries = $female_beneficiaries = $total  = 0;
        ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Beneficiaries Region Wise</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="table_1" style="font-size: 12px;">
                        <thead class="thead-light">
                            <tr>
                                <th style="display: none; text-align:center" colspan="5">Beneficiaries Region Wise</th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th class="text-center"><small>Total</small></th>
                                <th class="text-center">Households <small>(AVG)</small></th>
                                <th class="text-center">Beneficiaries <small>(AVG)</small></th>
                                <th class="text-center">Male <small>(AVG)</small></th>
                                <th class="text-center">Female <small>(AVG)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($regions as $region) {
                                $query = "SELECT COUNT(*) as total, 
                                ROUND(AVG(b.`house_holds`), 2) AS house_holds,
                                ROUND(AVG(b.`total_beneficiaries`), 2) AS total_beneficiaries,
                                ROUND(AVG(b.`male_beneficiaries`), 2) AS male_beneficiaries,
                                ROUND(AVG(b.`female_beneficiaries`), 2) AS female_beneficiaries
                                FROM 
                                `beneficiaries` AS b
                                WHERE b.region =  ? ";
                                $result = $this->db->query($query, array($region->region));
                                $row = $result->row();

                                $house_holds += $row->house_holds * $row->total;
                                $total_beneficiaries += $row->total_beneficiaries * $row->total;
                                $male_beneficiaries += $row->male_beneficiaries * $row->total;
                                $female_beneficiaries += $row->female_beneficiaries * $row->total;
                                $total += $row->total;
                                // Prepare chart data
                                $categories[] = ucfirst($region->region);
                                $tbData[] = (float) $row->total_beneficiaries;
                                $hhData[] = (float) $row->house_holds;
                                $mbData[] = (float) $row->male_beneficiaries;
                                $fbDate[] = (float) $row->female_beneficiaries;
                            ?>
                                <tr>
                                    <td><?php echo ucfirst($region->region) ?></td>
                                    <td class="text-center"><small><?php echo $row->total; ?></small></td>
                                    <td class="text-center"><?php echo number_format($row->house_holds, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($row->total_beneficiaries, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($row->male_beneficiaries, 2); ?></td>
                                    <th class="text-center"><?php echo number_format($row->female_beneficiaries, 2); ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">
                            <tr>
                                <th><small>Weighted Average</small></th>
                                <td class="text-center"><small><?php echo $total; ?></small></td>
                                <td class="text-center"><?php echo number_format(round($house_holds / $total, 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($total_beneficiaries / $total, 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($male_beneficiaries / $total, 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($female_beneficiaries / $total, 2), 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="beneficiaries_summary" style="height:280px">
                </div>
            </div>
        </div>
        <script>
            Highcharts.chart('beneficiaries_summary', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Beneficiaries Summary'
                },
                xAxis: {
                    categories: ["House Hold (Avg)", "Beneficiaries (Avg)", "Male (Avg)", "Female (Avg)"],
                },
                yAxis: {
                    title: {
                        text: 'Weighted Average'
                    }
                },
                plotOptions: {
                    bar: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f}'
                        }
                    }
                },
                series: [{
                        name: 'Weighted Average',
                        data: [<?php echo number_format(round($house_holds / $total, 2), 2); ?>,
                            <?php echo number_format(round($total_beneficiaries / $total, 2), 2); ?>,
                            <?php echo number_format(round($male_beneficiaries / $total, 2), 2); ?>,
                            <?php echo number_format(round($female_beneficiaries / $total, 2), 2); ?>
                        ]
                    }

                ]
            });
        </script>
    </div>
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="Region_wise_beneficiaries" style="height: 400px;"></div>
            </div>
        </div>
    </div>

    <script>
        Highcharts.chart('Region_wise_beneficiaries', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Beneficiaries Region Wise'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                crosshair: true
            },
            yAxis: {
                title: {
                    text: 'Average'
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: ' Avg'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{y}',
                        crop: false, // Don't hide labels outside the plot area
                        overflow: 'none', // Prevent hiding when overflowing
                        allowOverlap: true,
                        rotation: -90,
                        // style: {
                        //     fontSize: '9px' // Change to your desired font size
                        // }

                    },

                },
            },
            series: [{
                name: 'House Hold',
                data: <?php echo json_encode($hhData); ?>,
                color: '#ff7b7b'
            }, {
                name: 'Beneficiaries',
                data: <?php echo json_encode($tbData); ?>,
                color: '#7bff7b'
            }, {
                name: 'Male',
                data: <?php echo json_encode($mbData); ?>,
                color: '#00A2D6',
            }, {
                name: 'Female',
                data: <?php echo json_encode($fbDate); ?>,
                color: '#FF55D4'
            }]
        });
    </script>
</div>




<div class="row">
    <div class="col-md-6">

        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $house_holds = [];
        $total_beneficiaries = [];
        $male_beneficiaries = [];
        $female_beneficiaries = [];
        $wa = [];

        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Beneficiaries Components Wise</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th colspan="6">Beneficiaries Components Wise</th>
                            </tr>
                            <tr>
                                <th>Components</th>
                                <th><small>Total</small></th>
                                <th>Households <small>Avg.</small></th>
                                <th>Total <small>Avg.</small></th>
                                <th>Male <small>Avg.</small></th>
                                <th>Female <small>Avg.</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($components as $component) {
                                $query = "SELECT COUNT(*) as total, 
                                ROUND(AVG(b.`house_holds`), 2) AS house_holds,
                                ROUND(AVG(b.`total_beneficiaries`), 2) AS total_beneficiaries,
                                ROUND(AVG(b.`male_beneficiaries`), 2) AS male_beneficiaries,
                                ROUND(AVG(b.`female_beneficiaries`), 2) AS female_beneficiaries
                                FROM 
                                `beneficiaries` AS b
                                WHERE b.component = '" . $component->component . "'";
                                $beneficiary = $this->db->query($query)->row();


                                // Store values for Highcharts
                                $categories[] = $component->component;
                                $house_holds[] = $beneficiary->house_holds;
                                $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                                $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                                $female_beneficiaries[] = $beneficiary->female_beneficiaries;

                                $wa['total'] += $beneficiary->total;
                                $wa['house_holds'] += $beneficiary->house_holds * $beneficiary->total;
                                $wa['total_beneficiaries'] += $beneficiary->total_beneficiaries * $beneficiary->total;
                                $wa['male_beneficiaries'] += $beneficiary->male_beneficiaries * $beneficiary->total;
                                $wa['female_beneficiaries'] += $beneficiary->female_beneficiaries * $beneficiary->total;
                            ?>
                                <tr>
                                    <th><?php echo $component->component; ?></th>
                                    <td><small><?php echo $beneficiary->total; ?></small></td>
                                    <td><?php echo $beneficiary->house_holds; ?></td>
                                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <?php
                            $query =
                                "SELECT COUNT(*) as total, 
                                ROUND(AVG(b.`house_holds`), 2) AS house_holds,
                                ROUND(AVG(b.`total_beneficiaries`), 2) AS total_beneficiaries,
                                ROUND(AVG(b.`male_beneficiaries`), 2) AS male_beneficiaries,
                                ROUND(AVG(b.`female_beneficiaries`), 2) AS female_beneficiaries
                                FROM 
                                `beneficiaries` AS b";
                            $beneficiary = $this->db->query($query)->row(); ?>
                            <tr>
                                <th>Average</th>
                                <td><small><?php echo $beneficiary->total; ?></small></td>
                                <td><?php echo $beneficiary->house_holds; ?></td>
                                <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                                <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                                <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <td><small><?php echo $wa['total']; ?></small></td>
                                <td><?php echo round($wa['house_holds'] / $wa['total'], 2); ?></td>
                                <td><?php echo round($wa['total_beneficiaries'] / $wa['total'], 2); ?></td>
                                <td><?php echo round($wa['male_beneficiaries'] / $wa['total'], 2); ?></td>
                                <td><?php echo round($wa['female_beneficiaries'] / $wa['total'], 2); ?></td>
                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="components_beneficiaries_chart"></div>
                <script>
                    Highcharts.chart('components_beneficiaries_chart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Beneficiaries Components Wise'
                        },
                        xAxis: {
                            categories: <?php echo json_encode($categories); ?>,
                            title: {
                                text: 'Components'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: 'WP Kg/m<sup>3</sup> (Avg.)'
                            }
                        },
                        tooltip: {
                            shared: true,
                            valueSuffix: ''
                        },
                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0
                            }
                        },
                        series: [{
                            name: 'House Holds',
                            data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
                        }, {
                            name: 'Total Beneficiaries',
                            data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
                        }, {
                            name: 'Male Beneficiaries',
                            data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
                        }, {
                            name: 'Female Beneficiaries',
                            data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
                        }]
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">

    <?php
    $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
    $sub_components = $this->db->query($query)->result();

    // Initialize arrays for Highcharts data
    $categories = [];
    $house_holds = [];
    $total_beneficiaries = [];
    $male_beneficiaries = [];
    $female_beneficiaries = [];

    ?>
    <table class="table table-bordered table_medium">
        <thead>
            <tr>
                <th colspan="6">Beneficiaries Sub Components Wise</th>
            </tr>
            <tr>
                <th rowspan="2">Sub Componets</th>
                <th colspan="4">Beneficiaries</th>
            </tr>
            <tr>
                <th>Households <small>Avg.</small></th>
                <th>Total <small>Avg.</small></th>
                <th>Male <small>Avg.</small></th>
                <th>Female <small>Avg.</small></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sub_components as $sub_component) {
                $query =
                    "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.sub_component = '" . $sub_component->sub_component . "'";
                $beneficiary = $this->db->query($query)->row();


                // Store values for Highcharts
                $categories[] = $sub_component->sub_component;
                $house_holds[] = $beneficiary->house_holds;
                $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                $female_beneficiaries[] = $beneficiary->female_beneficiaries;
            ?>
                <tr>
                    <th><?php echo $sub_component->sub_component; ?></th>
                    <td><?php echo $beneficiary->house_holds; ?></td>
                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <?php
            $query =
                "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s";
            $beneficiary = $this->db->query($query)->row(); ?>
            <tr>
                <th>Total</th>
                <td><?php echo $beneficiary->house_holds; ?></td>
                <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                <td><?php echo $beneficiary->female_beneficiaries; ?></td>
            </tr>
        </tfoot>

    </table>

    <div id="sub_components_beneficiaries_chart"></div>
    <script>
        Highcharts.chart('sub_components_beneficiaries_chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Beneficiaries Sub Components Wise'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                title: {
                    text: 'Sub Component'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'WP Kg/m<sup>3</sup> (Avg.)'
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: ''
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'House Holds',
                data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
            }, {
                name: 'Total Beneficiaries',
                data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
            }, {
                name: 'Male Beneficiaries',
                data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
            }, {
                name: 'Female Beneficiaries',
                data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
            }]
        });
    </script>





</div>
</div>

<?php
$query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
$categories = $this->db->query($query)->result();

// Initialize arrays for Highcharts data
$categories_list = [];
$house_holds = [];
$total_beneficiaries = [];
$male_beneficiaries = [];
$female_beneficiaries = [];

?>

<div class="row">
    <div class="col-md-6">

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="6">Beneficiaries Category Wise</th>
                </tr>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="4">Beneficiaries</th>
                </tr>
                <tr>
                    <th>Households <small>Avg.</small></th>
                    <th>Total <small>Avg.</small></th>
                    <th>Male <small>Avg.</small></th>
                    <th>Female <small>Avg.</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) {
                    $query =
                        "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.category = '" . $category->category . "'";
                    $beneficiary = $this->db->query($query)->row();


                    // Store values for Highcharts
                    $categories_list[] = $category->category;
                    $house_holds[] = $beneficiary->house_holds;
                    $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                    $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                    $female_beneficiaries[] = $beneficiary->female_beneficiaries;
                ?>
                    <tr>
                        <th><?php echo $category->category; ?></th>
                        <td><?php echo $beneficiary->house_holds; ?></td>
                        <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query =
                    "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s";
                $beneficiary = $this->db->query($query)->row(); ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $beneficiary->house_holds; ?></td>
                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                </tr>
            </tfoot>

        </table>
    </div>
    <div class="col-md-6">
        <div id="categories_beneficiaries_chart"></div>
        <script>
            Highcharts.chart('categories_beneficiaries_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Beneficiaries categories wise'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories_list); ?>,
                    title: {
                        text: 'categories'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'WP Kg/m<sup>3</sup> (Avg.)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ''
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'House Holds',
                    data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Total Beneficiaries',
                    data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Male Beneficiaries',
                    data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Female Beneficiaries',
                    data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>

    </div>



</div>


<?php


// Initialize arrays for Highcharts data
$categories_list = [];
$house_holds = [];
$total_beneficiaries = [];
$male_beneficiaries = [];
$female_beneficiaries = [];

?>

<div class="row">
    <div class="col-md-6">

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="6">Beneficiaries Region Wise</th>
                </tr>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="4">Beneficiaries</th>
                </tr>
                <tr>
                    <th>Households <small>Avg.</small></th>
                    <th>Total <small>Avg.</small></th>
                    <th>Male <small>Avg.</small></th>
                    <th>Female <small>Avg.</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) {
                    $query =
                        "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.region = '" . $region->region . "'";
                    $beneficiary = $this->db->query($query)->row();


                    // Store values for Highcharts
                    $categories_list[] = ucwords($region->region);
                    $house_holds[] = $beneficiary->house_holds;
                    $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                    $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                    $female_beneficiaries[] = $beneficiary->female_beneficiaries;
                ?>
                    <tr>
                        <th><?php echo ucwords($region->region); ?></th>
                        <td><?php echo $beneficiary->house_holds; ?></td>
                        <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query =
                    "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s";
                $beneficiary = $this->db->query($query)->row(); ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $beneficiary->house_holds; ?></td>
                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                </tr>
            </tfoot>

        </table>
    </div>
    <div class="col-md-6">
        <div id="categories_region_chart"></div>
        <script>
            Highcharts.chart('categories_region_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Beneficiaries Region wise'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories_list); ?>,
                    title: {
                        text: 'Region'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'WP Kg/m<sup>3</sup> (Avg.)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ''
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'House Holds',
                    data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Total Beneficiaries',
                    data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Male Beneficiaries',
                    data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Female Beneficiaries',
                    data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>

    </div>



</div>