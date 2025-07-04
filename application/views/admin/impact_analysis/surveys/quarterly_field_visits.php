<div class="row">
    <div class="col-md-8">
        <h4>Impact Analysis: Field Visits</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Feild_Visits'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Feild_Visits',['table_1', 'table_2', 'table_3', 'table_4', 'table_5'], ['Field Visits Summary', 'Field Visits By' , 'Components_Regions Wise FWt', 'Sub-Components_Regions Wise FW', 'Categories_Region Wise FW'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>

<?php
$query = "SELECT iq.*, 
(SELECT COUNT(*) FROM impact_surveys AS ims WHERE ims.impact_quarter_id = iq.impact_quarter_id) AS surveys,
    SUM(iq.targets) OVER (ORDER BY iq.impact_quarter_id) AS cumulative_targets,
    SUM(
        (SELECT COUNT(*) FROM impact_surveys AS ims WHERE ims.impact_quarter_id = iq.impact_quarter_id)
    ) OVER (ORDER BY iq.impact_quarter_id) AS cumulative_surveys
FROM impact_quarters as iq";
$impact_quarters = $this->db->query($query)->result();
$query = "SELECT `region`, COUNT(*) as total FROM `impact_surveys` GROUP BY `region` ORDER BY total ASC;";
$regions = $this->db->query($query)->result();
?>
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Quarterly Achievement of the Field Visit conducted by the M&EC Team so far</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 table_small2" id="table_1" style="font-size: 12px;">

                        <thead>
                            <tr style="text-align: right; dipplay:none">
                                <th colspan="<?php echo count($impact_quarters) + 2; ?>"><?php echo $title; ?></th>
                            </tr>
                            <tr>
                                <th rowspan="3">Regions</th>
                                <th colspan="<?php echo count($impact_quarters); ?>"><?php echo $description; ?></th>
                                <th rowspan="3">Cumulative</th>
                            </tr>
                            <tr>
                                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>>
                                        <button class="btn btn-warning btn-sm" style="font-size: 9px;" onclick="get_quarterly_field_visits_district_wise(<?php echo $impact_quarter->impact_quarter_id; ?>)">
                                            <?php echo $impact_quarter->impact_quarter; ?> <?php if ($impact_quarter->status == 1) { ?> <span style="color: green !important; font-weight:bold">*</span> <?php } ?>
                                        </button>
                                    </th>
                                    </a>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>><?php echo date('M', strtotime($impact_quarter->quarter_start_date)); ?>
                                        -
                                        <?php echo date('M y', strtotime($impact_quarter->quarter_end_date)); ?>
                                    <?php } ?>
                            </tr>

                        </thead>
                        <tbody>
                            <?php foreach ($regions as $region) { ?>
                                <tr>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>>
                                        <button class="btn btn-link btn-sm" onclick="get_quarterly_field_visits_district_wise(0, '<?php echo $region->region; ?>')">
                                            <?php echo ucfirst($region->region) ?>
                                        </button>
                                    </th>
                                    <?php foreach ($impact_quarters as $impact_quarter) { ?>
                                        <?php
                                        $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE region = ? AND impact_quarter_id = ?";
                                        $survey_count = $this->db->query($query, [$region->region, $impact_quarter->impact_quarter_id])->row();
                                        ?>
                                        <td <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>>
                                            <button class="btn btn-link btn-sm" onclick="get_quarterly_field_visits_district_wise(<?php echo $impact_quarter->impact_quarter_id; ?>, '<?php echo $region->region; ?>')">

                                                <?php echo $survey_count->total;  ?>
                                            </button>
                                        </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE region = ?";
                                    $survey_count = $this->db->query($query, [$region->region])->row();
                                    ?>
                                    <th>
                                        <button class="btn btn-link btn-sm" onclick="get_quarterly_field_visits_district_wise(0, '<?php echo $region->region; ?>')">
                                            <?php echo $survey_count->total;  ?>
                                        </button>
                                    </th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th style="text-align: left;">Total</th>
                                <?php
                                $surveys = 0;
                                foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>><?php echo $impact_quarter->surveys;
                                                                                                                                    $surveys += $impact_quarter->surveys;  ?></th>
                                <?php } ?>
                                <td><?php echo $surveys;  ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: left;">Targets</th>
                                <?php
                                $targets = 0;
                                foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>><?php echo $impact_quarter->targets;
                                                                                                                                    $targets += $impact_quarter->targets;  ?></th>
                                <?php } ?>
                                <td><?php echo $targets;  ?></td>
                            </tr>

                            <tr>
                                <th style="text-align: left;">Difference</th>
                                <?php
                                $total_difference = 0;
                                $diff = 0;
                                foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>><?php
                                                                                                                                    if ($impact_quarter->targets and $impact_quarter->surveys) {
                                                                                                                                        $diff = $impact_quarter->surveys - $impact_quarter->targets;
                                                                                                                                        if ($diff >= 0) {
                                                                                                                                            if ($diff == 0) {
                                                                                                                                                echo $diff;
                                                                                                                                            } else {
                                                                                                                                                echo '<span style="color:green">+' . $diff . ' &#x25B2</span>';
                                                                                                                                            }
                                                                                                                                        } else {
                                                                                                                                            echo '<span style="color:red">' . $diff . ' &#x25BC</span>';
                                                                                                                                        }

                                                                                                                                        $total_difference += $diff;
                                                                                                                                    } ?></th>
                                <?php } ?>
                                <td><?php echo $targets - $surveys;  ?></td>
                            </tr>
                            <tr>
                                <th style="text-align: left;">Targets Cumulative</th>
                                <?php
                                foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>><?php
                                                                                                                                    if ($impact_quarter->targets and $impact_quarter->surveys) {
                                                                                                                                        echo $impact_quarter->cumulative_targets;
                                                                                                                                    } ?></th>
                                <?php } ?>
                                <td></td>
                            </tr>

                            <tr>
                                <th style="text-align: left;">Survey Cumulative</th>
                                <?php
                                foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>><?php
                                                                                                                                    if ($impact_quarter->targets and $impact_quarter->surveys) {
                                                                                                                                        echo $impact_quarter->cumulative_surveys;
                                                                                                                                    } ?></th>
                                <?php } ?>
                                <td></td>
                            </tr>
                            <tr>
                                <th style="text-align: left;">Cumulative Difference</th>
                                <?php
                                foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>><?php
                                                                                                                                    if ($impact_quarter->targets and $impact_quarter->surveys) {
                                                                                                                                        $diff = $impact_quarter->cumulative_surveys - $impact_quarter->cumulative_targets;
                                                                                                                                        if ($diff >= 0) {
                                                                                                                                            if ($diff == 0) {
                                                                                                                                                echo $diff;
                                                                                                                                            } else {
                                                                                                                                                echo '<span style="color:green">+' . $diff . ' &#x25B2</span>';
                                                                                                                                            }
                                                                                                                                        } else {
                                                                                                                                            echo '<span style="color:red">' . $diff . ' &#x25BC</span>';
                                                                                                                                        }
                                                                                                                                    } ?></th>
                                <?php } ?>
                                <td></td>
                            </tr>
                            <tr>
                                <th></th>
                                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>>
                                        <button class="btn btn-warning btn-sm" style="font-size: 9px;" onclick="get_quarterly_component_wise(<?php echo $impact_quarter->impact_quarter_id; ?>)">
                                            Components
                                        </button>
                                    </th>
                                    </a>
                                <?php } ?>
                                <th></th>
                            </tr>
                            <tr>
                                <th></th>
                                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>>
                                        <button class="btn btn-danger btn-sm" style="font-size: 9px;" onclick="get_quarterly_sub_component_wise(<?php echo $impact_quarter->impact_quarter_id; ?>)">
                                            Sub-Compo.
                                        </button>
                                    </th>
                                    </a>
                                <?php } ?>
                                <th></th>
                            </tr>
                            <tr>
                                <th></th>
                                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                                    <th <?php if ($impact_quarter->status == 1) { ?> style="background-color: #7EE3AD;" <?php } ?>>
                                        <button class="btn btn-success btn-sm" style="font-size: 9px;" onclick="get_quarterly_categories_wise(<?php echo $impact_quarter->impact_quarter_id; ?>)">
                                            Categories
                                        </button>
                                    </th>
                                    </a>
                                <?php } ?>
                                <th></th>
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
                <div id="Impact_Surveys_by_Region_and_Quarter"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4" style="display: none;">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="Trend_Analysis_Targets_vs_Achievements"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="Trend_Analysis_Targets_vs_Achievements_cummulative"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT field_monitor, COUNT(*) as total FROM `impact_surveys` GROUP BY field_monitor ORDER BY total DESC;";
        $field_monitors = $this->db->query($query)->result();

        // Prepare data for Highcharts
        $categories = [];
        $data = [];

        foreach ($field_monitors as $field_monitor) {
            $categories[] = $field_monitor->field_monitor;
            $data[] = (int) $field_monitor->total;
        }

        ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Field Visit conducted by the M&EC Team so far</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="table_2" style="font-size: 12px;">
                        <tr>
                            <th>#</th>
                            <th>Field Staff</th>
                            <th>Field Visit</th>
                        </tr>
                        <?php
                        $count = 1;
                        foreach ($field_monitors as $field_monitor) { ?>
                            <tr>
                                <th><?php echo $count++ ?></th>
                                <th><?php echo $field_monitor->field_monitor ?></th>
                                <th><?php echo $field_monitor->total ?></th>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="field_staff_visits" style="width:100%; "></div>

            </div>
        </div>
        <!-- Container for Highcharts -->

        <script>
            Highcharts.chart('field_staff_visits', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: ''
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'Field Staff'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Number of Visits'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{y} ',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size
                            }

                        }
                    }
                },
                series: [{
                    name: 'Visits',
                    data: <?php echo json_encode($data); ?>,
                    color: '#007bff'
                }]
            });
        </script>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="component_wise_visits" style="width:100%; height:400px;"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $categories = $this->db->query($query)->result();
        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Component and Region wise field visits</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table_3">
                        <thead>
                            <tr>
                                <th>Regions</th>
                                <?php foreach ($categories as $component) { ?>
                                    <th><?php echo $component->component; ?></th>
                                <?php } ?>
                                <th>Cumulative</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $chartData = []; // Array for Highcharts data
                            $regionsArray = [];

                            foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    $cum = 0;
                                    $dataRow = ["name" => ucfirst($region->region), "data" => []];
                                    foreach ($categories as $component) {
                                        $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE region = ? AND component = ?";
                                        $survey_count = $this->db->query($query, [$region->region,  $component->component])->row();
                                        $count = $survey_count->total;
                                        $cum += $count;
                                        $dataRow['data'][] = $count;
                                        echo "<td>$count</td>";
                                    }
                                    echo "<th>$cum</th>";
                                    $chartData[] = $dataRow;
                                    $regionsArray[] = ucfirst($region->region);
                                    ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <?php
                                $cum = 0;
                                $totalData = ["name" => "Total", "data" => []];
                                foreach ($categories as $component) {
                                    $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE component = ?";
                                    $survey_count = $this->db->query($query, [$component->component])->row();
                                    $count = $survey_count->total;
                                    $cum += $count;
                                    echo "<td>$count</td>";
                                    $totalData['data'][] = $count;
                                }
                                echo "<th>$cum</th>";
                                $chartData[] = $totalData;
                                ?>
                            </tr>
                        </tfoot>
                    </table>


                </div>
            </div>
        </div>
    </div>

    <script>
        Highcharts.chart('component_wise_visits', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Component and Region wise field visits'
            },
            xAxis: {
                categories: <?php echo json_encode(array_column($categories, 'component')); ?>,
                title: {
                    text: 'Components'
                }
            },
            yAxis: {
                title: {
                    text: 'Number of Visits'
                }
            },
            legend: {
                enabled: true
            },
            tooltip: {
                shared: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{y} ',
                        crop: false, // Don't hide labels outside the plot area
                        overflow: 'none', // Prevent hiding when overflowing
                        allowOverlap: true,
                        rotation: -90,
                        style: {
                            fontSize: '9px' // Change to your desired font size
                        }

                    }
                }
            },
            series: <?php echo json_encode($chartData, JSON_NUMERIC_CHECK); ?>
        });
    </script>

</div>
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $categories = $this->db->query($query)->result();
        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Sub-Components and Region wise field visits</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-bordered" id="table_4">
                        <thead>
                            <tr>
                                <th>Regions</th>
                                <?php foreach ($categories as $sub_component) { ?>
                                    <th><?php echo $sub_component->sub_component; ?></th>
                                <?php } ?>
                                <th>Cumulative</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $chartData = []; // Array for Highcharts
                            $regionsArray = [];

                            foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    $cum = 0;
                                    $dataRow = ["name" => ucfirst($region->region), "data" => []];
                                    foreach ($categories as $sub_component) {
                                        $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE region = ? AND sub_component = ?";
                                        $survey_count = $this->db->query($query, [$region->region, $sub_component->sub_component])->row();
                                        $count = $survey_count->total;
                                        $cum += $count;
                                        $dataRow['data'][] = $count;
                                        echo "<td>$count</td>";
                                    }
                                    echo "<th>$cum</th>";
                                    $chartData[] = $dataRow;
                                    $regionsArray[] = ucfirst($region->region);
                                    ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <?php
                                $cum = 0;
                                $totalData = ["name" => "Total", "data" => []];
                                foreach ($categories as $sub_component) {
                                    $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE sub_component = ?";
                                    $survey_count = $this->db->query($query, [$sub_component->sub_component])->row();
                                    $count = $survey_count->total;
                                    $cum += $count;
                                    echo "<td>$count</td>";
                                    $totalData['data'][] = $count;
                                }
                                echo "<th>$cum</th>";
                                $chartData[] = $totalData;
                                ?>
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
                <div id="sub-component-chart" style="width:100%; height:400px;"></div>
            </div>
        </div>
        <script>
            Highcharts.chart('sub-component-chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Sub-Component and Region wise field visits'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($categories, 'sub_component')); ?>,
                    title: {
                        text: 'Sub-Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Number of Visits'
                    }
                },
                legend: {
                    enabled: true
                },
                tooltip: {
                    shared: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{y} ',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size
                            }

                        }
                    }
                },
                series: <?php echo json_encode($chartData, JSON_NUMERIC_CHECK); ?>
            });
        </script>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php
        $query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $categories = $this->db->query($query)->result();
        ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Categorys and Region wise field visits</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered" id="table_5">
                        <thead>
                            <tr>
                                <th>Regions</th>
                                <?php foreach ($categories as $category) { ?>
                                    <th><?php echo $category->category; ?></th>
                                <?php } ?>
                                <th>Cumulative</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $chartData = []; // Array for Highcharts
                            $regionsArray = [];

                            foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    $cum = 0;
                                    $dataRow = ["name" => ucfirst($region->region), "data" => []];
                                    foreach ($categories as $category) {
                                        $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE region = ? AND category = ?";
                                        $survey_count = $this->db->query($query, [$region->region, $category->category])->row();
                                        $count = $survey_count->total;
                                        $cum += $count;
                                        $dataRow['data'][] = $count;
                                        echo "<td>$count</td>";
                                    }
                                    echo "<th>$cum</th>";
                                    $chartData[] = $dataRow;
                                    $regionsArray[] = ucfirst($region->region);
                                    ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <?php
                                $cum = 0;
                                $totalData = ["name" => "Total", "data" => []];
                                foreach ($categories as $category) {
                                    $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE category = ?";
                                    $survey_count = $this->db->query($query, [$category->category])->row();
                                    $count = $survey_count->total;
                                    $cum += $count;
                                    echo "<td>$count</td>";
                                    $totalData['data'][] = $count;
                                }
                                echo "<th>$cum</th>";
                                $chartData[] = $totalData;
                                ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="category-chart" style="width:100%; height:400px;"></div>
            </div>
        </div>
    </div>
</div>
<script>
    Highcharts.chart('category-chart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Category and Region wise field visits'
        },
        xAxis: {
            categories: <?php echo json_encode(array_column($categories, 'category')); ?>,
            title: {
                text: 'Categorys'
            }
        },
        yAxis: {
            title: {
                text: 'Number of Visits'
            }
        },
        legend: {
            enabled: true
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{y} ',
                    crop: false, // Don't hide labels outside the plot area
                    overflow: 'none', // Prevent hiding when overflowing
                    allowOverlap: true,
                    rotation: -90,
                    style: {
                        fontSize: '9px' // Change to your desired font size
                    }

                }
            }
        },
        series: <?php echo json_encode($chartData, JSON_NUMERIC_CHECK); ?>
    });
</script>

<script>
    Highcharts.chart("Impact_Surveys_by_Region_and_Quarter", {
        chart: {
            type: "column"
        },
        title: {
            text: "Impact Surveys by Region Wise"
        },
        xAxis: {
            categories: [
                <?php foreach ($regions as $region) {
                    echo "'" . ucfirst($region->region) . "',";
                } ?>
            ],
            title: {
                text: "Regions"
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: "Number of Surveys"
            }
        },
        tooltip: {
            shared: true,
            valueSuffix: "Surveys Visits"
        },
        plotOptions: {
            bar: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{y} ',
                    crop: false, // Don't hide labels outside the plot area
                    overflow: 'none', // Prevent hiding when overflowing
                    allowOverlap: true,
                    //rotation: -90,
                    style: {
                        fontSize: '12px' // Change to your desired font size
                    }

                }
            }

        },
        series: [
            <?php
            if (1 == 2) {
                foreach ($impact_quarters as $impact_quarter) { ?> {
                        name: "<?php echo $impact_quarter->impact_quarter; ?>",
                        data: [
                            <?php foreach ($regions as $region) { ?>
                                <?php
                                $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE region = ? AND impact_quarter_id = ?";
                                $survey_count = $this->db->query($query, [$region->region, $impact_quarter->impact_quarter_id])->row();
                                echo $survey_count->total . ",";
                                ?>
                            <?php } ?>
                        ]
                    },
            <?php }
            } ?> {
                name: "Cumulative",
                type: "bar",
                data: [
                    <?php foreach ($regions as $region) { ?>
                        <?php echo $region->total . ","; ?>
                    <?php } ?>
                ],
                marker: {
                    lineWidth: 2,
                    lineColor: Highcharts.getOptions().colors[3],
                    fillColor: "white"
                }
            }
        ]
    });




    Highcharts.chart("Trend_Analysis_Targets_vs_Achievements_cummulative", {
        chart: {
            type: "spline"
        },
        title: {
            text: "Trend Analysis: Cumulative Targets vs. Achievements"
        },
        xAxis: {
            categories: [
                <?php
                $query = "SELECT impact_quarter FROM impact_quarters ORDER BY impact_quarter_id ASC";
                $quarters = $this->db->query($query)->result();
                foreach ($quarters as $quarter) {
                    echo "'" . $quarter->impact_quarter . "',";
                }
                ?>
            ],
            title: {
                text: "Impact Quarters"
            }
        },
        yAxis: {
            title: {
                text: "Cumulative Total"
            }
        },
        tooltip: {
            shared: true
        },
        plotOptions: {
            spline: {
                pointPadding: 0.2,
                borderWidth: 0,
                dataLabels: {
                    enabled: true,
                    format: '{y} ',
                    crop: false, // Don't hide labels outside the plot area
                    overflow: 'none', // Prevent hiding when overflowing
                    allowOverlap: true,
                    //rotation: -90,
                    style: {
                        fontSize: '8px' // Change to your desired font size
                    }

                }
            }

        },

        series: [{
                name: "Targets",
                data: (function() {
                    let cumulative = 0;
                    return [
                        <?php
                        $query = "SELECT targets FROM impact_quarters ORDER BY impact_quarter_id ASC";
                        $targets = $this->db->query($query)->result();
                        foreach ($targets as $target) {
                            echo ($cumulative += $target->targets) . ",";
                        }
                        ?>
                    ];
                })()
            },
            {
                name: "Achievements",
                data: (function() {
                    let achieved_cumulative = 0;
                    return [
                        <?php
                        $query = "SELECT COUNT(*) as achieved FROM impact_surveys GROUP BY impact_quarter_id  ORDER BY impact_quarter_id ASC";
                        $achievements = $this->db->query($query)->result();
                        foreach ($achievements as $achievement) {
                            echo ($achieved_cumulative += $achievement->achieved) . ",";
                        }
                        ?>
                    ];
                })()
            }
        ]
    });



    function get_quarterly_sub_component_wise(impact_quarter_id, region = null) {
        $.ajax({
                method: "POST",
                url: "<?php echo site_url(ADMIN_DIR . 'impact_analysis/get_quarterly_sub_component_wise'); ?>",
                data: {
                    impact_quarter_id: impact_quarter_id,
                    region: region
                },
            })
            .done(function(response) {
                //console.log(respose);
                $('#modal').modal('show');
                $('#modal_title').html('Quarterly Sub Components Wise Field Visits');
                $('#modal_body').html(response);
                // Ensure .modal-xl is 90% wide
                $('.modal-dialog').css({
                    'max-width': '95%',
                    'width': '95%'
                });
            });
    }

    function get_quarterly_component_wise(impact_quarter_id, region = null) {
        $.ajax({
                method: "POST",
                url: "<?php echo site_url(ADMIN_DIR . 'impact_analysis/get_quarterly_component_wise'); ?>",
                data: {
                    impact_quarter_id: impact_quarter_id,
                    region: region
                },
            })
            .done(function(response) {
                //console.log(respose);
                $('#modal').modal('show');
                $('#modal_title').html('Quarterly Components Wise Field Visits');
                $('#modal_body').html(response);
                // Ensure .modal-xl is 90% wide
                $('.modal-dialog').css({
                    'max-width': '95%',
                    'width': '95%'
                });
            });
    }

    function get_quarterly_categories_wise(impact_quarter_id, region = null) {
        $.ajax({
                method: "POST",
                url: "<?php echo site_url(ADMIN_DIR . 'impact_analysis/get_quarterly_categories_wise'); ?>",
                data: {
                    impact_quarter_id: impact_quarter_id,
                    region: region
                },
            })
            .done(function(response) {
                //console.log(respose);
                $('#modal').modal('show');
                $('#modal_title').html('Quarterly Categories Wise Field Visits');
                $('#modal_body').html(response);
                // Ensure .modal-xl is 90% wide
                $('.modal-dialog').css({
                    'max-width': '95%',
                    'width': '95%'
                });
            });
    }


    function get_quarterly_field_visits_district_wise(impact_quarter_id, region = null) {
        $.ajax({
                method: "POST",
                url: "<?php echo site_url(ADMIN_DIR . 'impact_analysis/get_quarterly_field_visits_district_wise'); ?>",
                data: {
                    impact_quarter_id: impact_quarter_id,
                    region: region
                },
            })
            .done(function(response) {
                //console.log(respose);
                $('#modal').modal('show');
                $('#modal_title').html('region Wise Quarterly Field Visits');
                $('#modal_body').html(response);
                // Ensure .modal-xl is 90% wide
                $('.modal-dialog').css({
                    'max-width': '95%',
                    'width': '95%'
                });
            });
    }
</script>