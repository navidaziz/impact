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

        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/WUA_Members'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data WUA Members</a>
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Beneficiaries'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Beneficiaries Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard' ], ['Summary', 'Crop & Component Wise' , 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>WUA AVG. Members <small>Per Scheme</small></strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="2">WUA AVG. Members <small>Per Scheme</small></th>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "
                                SELECT 
                                    ROUND(AVG(wua_members), 2) AS wua_members,
                                    ROUND(AVG(male_members), 2) AS male_members,
                                    ROUND(AVG(female_members), 2) AS female_members,
                                    ROUND(AVG(male_members) / (AVG(male_members) + AVG(female_members)) * 100, 2) AS male_percentage,
                                    ROUND(AVG(female_members) / (AVG(male_members) + AVG(female_members)) * 100, 2) AS female_percentage
                                FROM impact_surveys AS s;
                            ";
                            $wua = $this->db->query($query)->row();

                            // Store values for Highcharts
                            $wua_members[] = $wua->wua_members;
                            $male_members[] = $wua->male_members;
                            $female_members[] = $wua->female_members;
                            ?>
                            <tr>
                                <td>Average Members</td>
                                <td><?php echo $wua->wua_members; ?></td>
                            </tr>
                            <tr>
                                <td>Male Members (Avg.)</td>
                                <td><?php echo $wua->male_members; ?></td>
                            </tr>
                            <tr>
                                <td>Female Members (Avg.)</td>
                                <td><?php echo $wua->female_members; ?></td>
                            </tr>
                            <tr>
                                <td>Male Members (%)</td>
                                <td><?php echo round($wua->male_percentage, 2) . "%"; ?></td>
                            </tr>
                            <tr>
                                <td>Female Members (%)</td>
                                <td><?php echo round($wua->female_percentage, 2) . "%"; ?></td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="wua_members_chart" style="width: 100%;  height: 330px; margin: 0 auto;"></div>
            </div>
        </div>
        <script>
            Highcharts.chart('wua_members_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'WUA Members'
                },
                xAxis: {
                    categories: 'WUA Average Members',
                    title: {
                        text: 'Average Members'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '(Avg.)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ''
                },
                plotOptions: {
                    column: {
                        shadow: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{y:,.000f}',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            //rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size

                            }

                        }
                    }
                },
                series: [{
                        name: 'AVG. Members',
                        data: <?php echo json_encode($wua_members, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        name: 'Males',
                        color: '#00A2D6',
                        data: <?php echo json_encode($male_members, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        name: 'Females',
                        color: '#EF3DBB',
                        data: <?php echo json_encode($female_members, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
        </script>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="members-pie" style="width: 100%;  height: 330px; margin: 0 auto;"></div>
            </div>
        </div>
        <script type="text/javascript">
            Highcharts.chart('members-pie', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'WUA Member Gender Distribution'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.2f} %'
                        }
                    }
                },
                series: [{
                    name: 'Members',
                    colorByPoint: true,
                    data: [{
                        name: 'Male',
                        color: '#00A2D6',
                        y: <?php echo $wua->male_percentage; ?>
                    }, {
                        name: 'Female',
                        color: '#EF3DBB',
                        y: <?php echo $wua->female_percentage; ?>
                    }]
                }]
            });
        </script>

    </div>
</div>
<div class="row">
    <div class="col-md-4">

        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

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
                    <th colspan="6">Beneficiaries Components Wise</th>
                </tr>
                <tr>
                    <th rowspan="2">Components</th>
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
                <?php foreach ($components as $component) {
                    $query =
                        "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.component = '" . $component->component . "'";
                    $beneficiary = $this->db->query($query)->row();


                    // Store values for Highcharts
                    $categories[] = $component->component;
                    $house_holds[] = $beneficiary->house_holds;
                    $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                    $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                    $female_beneficiaries[] = $beneficiary->female_beneficiaries;
                ?>
                    <tr>
                        <th><?php echo $component->component; ?></th>
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
$query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
$regions = $this->db->query($query)->result();

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