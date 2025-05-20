<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis on Water Losses</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Water_Losses_Raw'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Water_Losses_Compiled'); ?>" class="btn btn-warning btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Complied Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'table_3', 'table_4' , 'table_5' , 'table_6' , 'table_7' ], ['Component Summary', 'Sub Component Summary' , 'Category Summary', 'Region Wise Summary' , 'Region and Component' , 'Region and Sub Component' , 'Region and Category'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>


<hr />
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($components);
        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Components Wise Water Losses and Savings</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="table_1">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="4">Components Wise Water Losses and Savings</th>
                            </tr>
                            <tr>
                                <th>Components</th>
                                <th>Before <small>(%) Avg</small></th>
                                <th>After <small>(%) Avg</small></th>
                                <th>Reduction / Saving <small>(%) Avg</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($components as $component) {
                                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.component = '" . $component->component . "'";
                                $water_losses = $this->db->query($query)->row();

                                // Store values for Highcharts
                                $categories[] = $component->component;
                                $beforeLosses[] = $water_losses->imp_before_losses;
                                $afterLosses[] = $water_losses->imp_after_losses;
                                $reductionPercent[] = $water_losses->reduction_water_losses;
                            ?>
                                <tr>
                                    <th><?php echo $component->component; ?></th>
                                    <td style="text-align: center;"><?php echo $water_losses->imp_before_losses; ?></td>
                                    <td style="text-align: center;"><?php echo $water_losses->imp_after_losses; ?></td>
                                    <th style="text-align: center;"><?php echo $water_losses->reduction_water_losses; ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <?php
                            $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                                round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                                round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                                FROM `impact_surveys_water_losses` as wl";
                            $waterlosses = $this->db->query($query)->row();
                            $categories[] = 'Average';
                            $beforeLosses[] = $waterlosses->imp_before_losses;
                            $afterLosses[] = $waterlosses->imp_after_losses;
                            $reductionPercent[] = $waterlosses->reduction_water_losses;
                            ?>
                            <tr>
                                <th>Average</th>
                                <th style="text-align: center;"><?php echo $waterlosses->imp_before_losses; ?></th>
                                <th style="text-align: center;"><?php echo $waterlosses->imp_after_losses; ?></th>
                                <th style="text-align: center;"><?php echo $waterlosses->reduction_water_losses; ?></th>
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
                <div id="ComponentwaterLossChart" style="width:100%; height:280px;"></div>
            </div>
        </div>
    </div>
    <script>
        Highcharts.chart('ComponentwaterLossChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Component Wise Water Losses and Savings'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                title: {
                    text: 'Components'
                }
            },
            yAxis: {
                // min: 0,
                title: {
                    text: 'Percentage - AVG'
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{y} %',
                        style: {
                            fontSize: '9px' // Change to your desired font size
                        }

                    }
                }
            },
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal'
            },
            series: [{
                name: 'Before',
                data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                color: '#ff7b7b'
            }, {
                name: 'After',
                data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                color: '#7bff7b'
            }, {
                name: 'Reduction',
                data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                color: '#7b7bff',
            }]
        });
    </script>

</div>
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="SubComponentwaterLossChart" style="width:100%; height:400px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT `sub_component` as component FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($components);
        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Sub Components Wise Water Losses and Savings</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="table_2">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="4">Sub Components Wise Water Losses and Savings</th>
                            </tr>
                            <tr>
                                <th>Components</th>
                                <th>Before <small>(%) Avg</small></th>
                                <th>After <small>(%) Avg</small></th>
                                <th>Reduction / Saving <small>(%) Avg</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($components as $component) {
                                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.sub_component = '" . $component->component . "'";
                                $water_losses = $this->db->query($query)->row();
                                // Store values for Highcharts
                                $categories[] = $component->component;
                                $beforeLosses[] = $water_losses->imp_before_losses;
                                $afterLosses[] = $water_losses->imp_after_losses;
                                $reductionPercent[] = $water_losses->reduction_water_losses;
                            ?>
                                <tr>
                                    <th><?php echo $component->component; ?></th>
                                    <td style="text-align:center"><?php echo $water_losses->imp_before_losses; ?></td>
                                    <td style="text-align:center"><?php echo $water_losses->imp_after_losses; ?></td>
                                    <th style="text-align:center"><?php echo $water_losses->reduction_water_losses; ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <?php
                            $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                            round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                            round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                            FROM `impact_surveys_water_losses` as wl";
                            $waterlosses = $this->db->query($query)->row();
                            $categories[] = 'Average';
                            $beforeLosses[] = $waterlosses->imp_before_losses;
                            $afterLosses[] = $waterlosses->imp_after_losses;
                            $reductionPercent[] = $waterlosses->reduction_water_losses;
                            ?>
                            <tr>
                                <th>Average</th>
                                <th style="text-align:center"><?php echo $waterlosses->imp_before_losses; ?></th>
                                <th style="text-align:center"><?php echo $waterlosses->imp_after_losses; ?></th>
                                <th style="text-align:center"><?php echo $waterlosses->reduction_water_losses; ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>


        <script>
            Highcharts.chart('SubComponentwaterLossChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Component Wise Water Losses Comparison'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'Sub Components'
                    }
                },
                yAxis: {
                    // min: 0,
                    title: {
                        text: 'Percentage - AVG'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{y} %',
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
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                series: [{
                    name: 'Before',
                    data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#ff7b7b'
                }, {
                    name: 'After',
                    data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#7bff7b'
                }, {
                    name: 'Reduction',
                    data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                    color: '#7b7bff',
                }]
            });
        </script>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `category` as component FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($components);
        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Category Wise Water Losses and Savings</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 table_medium2" id="table_3">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="4">Category Wise Water Losses and Savings</th>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <th>Before <small>AVG</small></th>
                                <th>After <small>AVG</small></th>
                                <th>Reduction / Saving <small>Avg (%)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($components as $component) {
                                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.category = '" . $component->component . "'";
                                $water_losses = $this->db->query($query)->row();
                                // Store values for Highcharts
                                $categories[] = $component->component;
                                $beforeLosses[] = $water_losses->imp_before_losses;
                                $afterLosses[] = $water_losses->imp_after_losses;
                                $reductionPercent[] = $water_losses->reduction_water_losses;
                            ?>
                                <tr>
                                    <th><?php echo $component->component; ?></th>
                                    <td style="text-align: center;"><?php echo $water_losses->imp_before_losses; ?></td>
                                    <td style="text-align: center;"><?php echo $water_losses->imp_after_losses; ?></td>
                                    <th style="text-align: center;"><?php echo $water_losses->reduction_water_losses; ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <?php
                            $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                            round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                            round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                            FROM `impact_surveys_water_losses` as wl";
                            $waterlosses = $this->db->query($query)->row();
                            $categories[] = 'Average';
                            $beforeLosses[] = $waterlosses->imp_before_losses;
                            $afterLosses[] = $waterlosses->imp_after_losses;
                            $reductionPercent[] = $waterlosses->reduction_water_losses;
                            ?>
                            <tr>
                                <th>Average</th>
                                <th style="text-align: center;"><?php echo $waterlosses->imp_before_losses; ?></th>
                                <th style="text-align: center;"><?php echo $waterlosses->imp_after_losses; ?></th>
                                <th style="text-align: center;"><?php echo $waterlosses->reduction_water_losses; ?></th>
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
                <div id="CategoryComponentwaterLossChart" style="width:100%;"></div>
            </div>
        </div>
    </div>
    <script>
        Highcharts.chart('CategoryComponentwaterLossChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Category Wise Water Losses Comparison'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                title: {
                    text: 'Components'
                }
            },
            yAxis: {
                // min: 0,
                title: {
                    text: 'Water Losses (AVG)'
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{y} %',
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
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal'
            },
            series: [{
                name: 'Before',
                data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                color: '#ff7b7b'
            }, {
                name: 'After',
                data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                color: '#7bff7b'
            }, {
                name: 'Reduction',
                data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                color: '#7b7bff',
            }]
        });
    </script>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="regionregionwaterLossChart" style="width:100%; height:400px;"></div>
            </div>
        </div>

    </div>

    <div class="col-md-6">
        <?php
        $query = "SELECT `region` as region FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
        $regions = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($regions);
        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region Wise Water Losses and Savings</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0 " id="table_4">
                        <thead>
                            <tr>
                                <th>Regions</th>
                                <th>Before <small>AVG</small></th>
                                <th>After <small>AVG</small></th>
                                <th>Reduction / Saving <small>Avg (%)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($regions as $region) {
                                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.region = '" . $region->region . "'";
                                $water_losses = $this->db->query($query)->row();
                                // Store values for Highcharts
                                $categories[] = $region->region;
                                $beforeLosses[] = $water_losses->imp_before_losses;
                                $afterLosses[] = $water_losses->imp_after_losses;
                                $reductionPercent[] = $water_losses->reduction_water_losses;
                            ?>
                                <tr>
                                    <th><?php echo $region->region; ?></th>
                                    <td><?php echo $water_losses->imp_before_losses; ?></td>
                                    <td><?php echo $water_losses->imp_after_losses; ?></td>
                                    <td><?php echo $water_losses->reduction_water_losses; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <?php
                            $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                        round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                        round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                        FROM `impact_surveys_water_losses` as wl";
                            $waterlosses = $this->db->query($query)->row();
                            ?>
                            <tr>
                                <th>Average</th>
                                <td><?php echo $waterlosses->imp_before_losses; ?></td>
                                <td><?php echo $waterlosses->imp_after_losses; ?></td>
                                <td><?php echo $waterlosses->reduction_water_losses; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        Highcharts.chart('regionregionwaterLossChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'region Wise Water Losses Comparison'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                title: {
                    text: 'regions'
                }
            },
            yAxis: {
                // min: 0,
                title: {
                    text: 'Water Losses (AVG)'
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{y} %',
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
            legend: {
                align: 'center',
                verticalAlign: 'bottom',
                layout: 'horizontal'
            },
            series: [{
                name: 'Before',
                data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                color: '#ff7b7b'
            }, {
                name: 'After',
                data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                color: '#7bff7b'
            }, {
                name: 'Reduction',
                data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                color: '#7b7bff',
            }]
        });
    </script>
</div>

<?php
$query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
$components = $this->db->query($query)->result();
?>
<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Region and Component Wise Water Losses and Savings </strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table_medium" id="table_5">
                    <thead>
                        <tr style="display: none;">
                            <th colspan="<?php echo ((1 + (count($components) * 5)) + 5); ?>">Region and Component Wise Water Losses and Savings</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Regions</th>
                            <?php foreach ($components as $component) { ?>
                                <th colspan="4">Component <?php echo $component->component; ?></th>
                            <?php } ?>
                            <th colspan="4">Cumulative</th>
                        </tr>
                        <tr>

                            <?php foreach ($components as $component) { ?>
                                <th>Total </th>
                                <th>Before <small>AVG</small></th>
                                <th>After <small>AVG</small></th>
                                <th>Reduction / Saving <small>Avg (%)</small></th>
                            <?php } ?>
                            <th>Total </th>
                            <th>Before <small>AVG</small></th>
                            <th>After <small>AVG</small></th>
                            <th>Reduction / Saving <small>Avg (%)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $weighted_average = array();
                        foreach ($regions as $region) { ?>
                            <tr>
                                <th><?php echo ucfirst($region->region) ?></th>
                                <?php foreach ($components as $component) {


                                    $query = "SELECT 
                                    COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl 
                                    WHERE component = ?
                                    AND region = ? ";
                                    $result = $this->db->query($query, [$component->component, $region->region])->row();
                                ?>
                                    <td style="text-align: center;"><?php echo $result->total; ?></td>
                                    <td style="text-align: center;"><?php echo $result->imp_before_losses; ?></td>
                                    <td style="text-align: center;"><?php echo $result->imp_after_losses; ?></td>
                                    <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                                <?php
                                    $weighted_average[$component->component]['total'] += $result->total;
                                    $weighted_average[$component->component]['before'] += $result->imp_before_losses * $result->total;
                                    $weighted_average[$component->component]['after'] += $result->imp_after_losses * $result->total;
                                    $weighted_average[$component->component]['per_increase'] += $result->reduction_water_losses * $result->total;
                                    $total += $result->total;
                                } ?>

                                <?php $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl
                                            WHERE  region = ? ";
                                $result = $this->db->query($query, [$region->region])->row();
                                $weighted_average['commulative']['total'] += $result->total;
                                $weighted_average['commulative']['before'] += $result->imp_before_losses * $result->total;
                                $weighted_average['commulative']['after'] += $result->imp_after_losses * $result->total;
                                $weighted_average['commulative']['per_increase'] += $result->reduction_water_losses * $result->total;
                                $total += $result->total;
                                ?>
                                <td style="text-align: center;"><?php echo $result->total; ?></td>
                                <td style="text-align: center;"><?php echo $result->imp_before_losses; ?></td>
                                <td style="text-align: center;"><?php echo $result->imp_after_losses; ?></td>
                                <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                            </tr>
                        <?php

                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Average</th>
                            <?php foreach ($components as $component) {
                                $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl 
                                        WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                            ?>
                                <th style="text-align: center;"><?php echo $result->total; ?></th>
                                <th style="text-align: center;"><?php echo $result->imp_before_losses; ?></th>
                                <th style="text-align: center;"><?php echo $result->imp_after_losses; ?></th>
                                <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                            <?php } ?>
                            <?php $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl  ";
                            $result = $this->db->query($query)->row();
                            ?>
                            <th style="text-align: center;"><?php echo $result->total; ?></th>
                            <th style="text-align: center;"><?php echo $result->imp_before_losses; ?></th>
                            <th style="text-align: center;"><?php echo $result->imp_after_losses; ?></th>
                            <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>

                        </tr>
                        <tr>
                            <th>Weighted Average</th>
                            <?php
                            foreach ($weighted_average as $key => $value) { ?>
                                <th style="text-align: center;"><?php echo $value['total']; ?></th>
                                <th style="text-align: center;"><?php echo round($value['before'] / ($value['total']), 2); ?></th>
                                <th style="text-align: center;"><?php echo round($value['after'] / ($value['total']), 2); ?></th>
                                <th style="text-align: center;"><?php echo round($value['per_increase'] / ($value['total']), 2); ?></th>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
$query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
$sub_components = $this->db->query($query)->result();
?>
<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Region and Sub Component Wise Water Losses and Savings</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table_medium" id="table_6">
                    <thead>
                        <tr style="display: none;">
                            <th colspan="<?php echo ((1 + (count($sub_components) * 5)) + 5); ?>">Region and Sub Component Wise Water Losses and Savings</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Regions</th>
                            <?php foreach ($sub_components as $sub_component) { ?>
                                <th colspan="4">Sub Component <?php echo $sub_component->sub_component; ?></th>
                            <?php } ?>
                            <th colspan="4">Cumulative</th>
                        </tr>
                        <tr>

                            <?php foreach ($sub_components as $sub_component) { ?>
                                <th>Total </th>
                                <th>Before <small>AVG</small></th>
                                <th>After <small>AVG</small></th>
                                <th>Reduction / Saving <small>Avg (%)</small></th>
                            <?php } ?>
                            <th>Total </th>
                            <th>Before <small>AVG</small></th>
                            <th>After <small>AVG</small></th>
                            <th>Reduction / Saving <small>Avg (%)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $weighted_average = array();
                        foreach ($regions as $region) { ?>
                            <tr>
                                <th><?php echo ucfirst($region->region) ?></th>
                                <?php foreach ($sub_components as $sub_component) {


                                    $query = "SELECT 
                                    COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl 
                                    WHERE sub_component = ?
                                    AND region = ? ";
                                    $result = $this->db->query($query, [$sub_component->sub_component, $region->region])->row();
                                ?>
                                    <td style="text-align: center;"><?php echo $result->total; ?></td>
                                    <td style="text-align: center;"><?php echo $result->imp_before_losses; ?></td>
                                    <td style="text-align: center;"><?php echo $result->imp_after_losses; ?></td>
                                    <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                                <?php
                                    $weighted_average[$sub_component->sub_component]['total'] += $result->total;
                                    $weighted_average[$sub_component->sub_component]['before'] += $result->imp_before_losses * $result->total;
                                    $weighted_average[$sub_component->sub_component]['after'] += $result->imp_after_losses * $result->total;
                                    $weighted_average[$sub_component->sub_component]['per_increase'] += $result->reduction_water_losses * $result->total;
                                    $total += $result->total;
                                } ?>

                                <?php $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl
                                            WHERE  region = ? ";
                                $result = $this->db->query($query, [$region->region])->row();
                                $weighted_average['commulative']['total'] += $result->total;
                                $weighted_average['commulative']['before'] += $result->imp_before_losses * $result->total;
                                $weighted_average['commulative']['after'] += $result->imp_after_losses * $result->total;
                                $weighted_average['commulative']['per_increase'] += $result->reduction_water_losses * $result->total;
                                $total += $result->total;
                                ?>
                                <td style="text-align: center;"><?php echo $result->total; ?></td>
                                <td style="text-align: center;"><?php echo $result->imp_before_losses; ?></td>
                                <td style="text-align: center;"><?php echo $result->imp_after_losses; ?></td>
                                <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                            </tr>
                        <?php

                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Average</th>
                            <?php foreach ($sub_components as $sub_component) {
                                $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl 
                                        WHERE sub_component = ? ";
                                $result = $this->db->query($query, [$sub_component->sub_component])->row();
                            ?>
                                <th style="text-align: center;"><?php echo $result->total; ?></th>
                                <th style="text-align: center;"><?php echo $result->imp_before_losses; ?></th>
                                <th style="text-align: center;"><?php echo $result->imp_after_losses; ?></th>
                                <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                            <?php } ?>
                            <?php $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl  ";
                            $result = $this->db->query($query)->row();
                            ?>
                            <th style="text-align: center;"><?php echo $result->total; ?></th>
                            <th style="text-align: center;"><?php echo $result->imp_before_losses; ?></th>
                            <th style="text-align: center;"><?php echo $result->imp_after_losses; ?></th>
                            <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>

                        </tr>
                        <tr>
                            <th>Weighted Average</th>
                            <?php
                            foreach ($weighted_average as $key => $value) { ?>
                                <th style="text-align: center;"><?php echo $value['total']; ?></th>
                                <th style="text-align: center;"><?php echo round($value['before'] / ($value['total']), 2); ?></th>
                                <th style="text-align: center;"><?php echo round($value['after'] / ($value['total']), 2); ?></th>
                                <th style="text-align: center;"><?php echo round($value['per_increase'] / ($value['total']), 2); ?></th>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
$query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
$categories = $this->db->query($query)->result();
?>
<div class="col-md-12" style="margin-bottom: 10px;">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <strong>Region and Category Wise Water Losses and Savings </strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table_medium" id="table_7">
                    <thead>
                        <tr style="display: none;">
                            <th colspan="<?php echo ((1 + (count($categories) * 5)) + 5); ?>">Region and Category Wise Water Losses and Savings</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Regions</th>
                            <?php foreach ($categories as $category) { ?>
                                <th colspan="4">Category <?php echo $category->category; ?></th>
                            <?php } ?>
                            <th colspan="4">Cumulative</th>
                        </tr>
                        <tr>

                            <?php foreach ($categories as $category) { ?>
                                <th>Total </th>
                                <th>Before <small>AVG</small></th>
                                <th>After <small>AVG</small></th>
                                <th>Reduction / Saving <small>Avg (%)</small></th>
                            <?php } ?>
                            <th>Total </th>
                            <th>Before <small>AVG</small></th>
                            <th>After <small>AVG</small></th>
                            <th>Reduction / Saving <small>Avg (%)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $weighted_average = array();
                        foreach ($regions as $region) { ?>
                            <tr>
                                <th><?php echo ucfirst($region->region) ?></th>
                                <?php foreach ($categories as $category) {


                                    $query = "SELECT 
                                    COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl 
                                    WHERE category = ?
                                    AND region = ? ";
                                    $result = $this->db->query($query, [$category->category, $region->region])->row();
                                ?>
                                    <td style="text-align: center;"><?php echo $result->total; ?></td>
                                    <td style="text-align: center;"><?php echo $result->imp_before_losses; ?></td>
                                    <td style="text-align: center;"><?php echo $result->imp_after_losses; ?></td>
                                    <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                                <?php
                                    $weighted_average[$category->category]['total'] += $result->total;
                                    $weighted_average[$category->category]['before'] += $result->imp_before_losses * $result->total;
                                    $weighted_average[$category->category]['after'] += $result->imp_after_losses * $result->total;
                                    $weighted_average[$category->category]['per_increase'] += $result->reduction_water_losses * $result->total;
                                    $total += $result->total;
                                } ?>

                                <?php $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl
                                            WHERE  region = ? ";
                                $result = $this->db->query($query, [$region->region])->row();
                                $weighted_average['commulative']['total'] += $result->total;
                                $weighted_average['commulative']['before'] += $result->imp_before_losses * $result->total;
                                $weighted_average['commulative']['after'] += $result->imp_after_losses * $result->total;
                                $weighted_average['commulative']['per_increase'] += $result->reduction_water_losses * $result->total;
                                $total += $result->total;
                                ?>
                                <td style="text-align: center;"><?php echo $result->total; ?></td>
                                <td style="text-align: center;"><?php echo $result->imp_before_losses; ?></td>
                                <td style="text-align: center;"><?php echo $result->imp_after_losses; ?></td>
                                <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                            </tr>
                        <?php

                        } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Average</th>
                            <?php foreach ($categories as $category) {
                                $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl 
                                        WHERE category = ? ";
                                $result = $this->db->query($query, [$category->category])->row();
                            ?>
                                <th style="text-align: center;"><?php echo $result->total; ?></th>
                                <th style="text-align: center;"><?php echo $result->imp_before_losses; ?></th>
                                <th style="text-align: center;"><?php echo $result->imp_after_losses; ?></th>
                                <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>
                            <?php } ?>
                            <?php $query = "SELECT  COUNT(*) as total,
                                    round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                    round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                    round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                    FROM `impact_surveys_water_losses` as wl  ";
                            $result = $this->db->query($query)->row();
                            ?>
                            <th style="text-align: center;"><?php echo $result->total; ?></th>
                            <th style="text-align: center;"><?php echo $result->imp_before_losses; ?></th>
                            <th style="text-align: center;"><?php echo $result->imp_after_losses; ?></th>
                            <th style="text-align: center;"><?php echo $result->reduction_water_losses; ?></th>

                        </tr>
                        <tr>
                            <th>Weighted Average</th>
                            <?php
                            foreach ($weighted_average as $key => $value) { ?>
                                <th style="text-align: center;"><?php echo $value['total']; ?></th>
                                <th style="text-align: center;"><?php echo round($value['before'] / ($value['total']), 2); ?></th>
                                <th style="text-align: center;"><?php echo round($value['after'] / ($value['total']), 2); ?></th>
                                <th style="text-align: center;"><?php echo round($value['per_increase'] / ($value['total']), 2); ?></th>
                            <?php } ?>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>