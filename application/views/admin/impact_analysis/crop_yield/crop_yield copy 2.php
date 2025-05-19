<h4></h4>
<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis on Increase in Crops Yield</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Crop_Yields'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'table_3', 'table_4'], ['Sheet1', 'Sheet2', 'Sheet3', 'Sheet4'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>


    </div>
</div>


<hr />
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Average Increase in Crops Yield</strong>
            </div>

            <?php
            $crops = array("wheat", "maize", "sugarcane", "vegetable", "orchard");
            $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
            $components = $this->db->query($query)->result();

            // Initialize cumulative sums
            $cumulative_before = 0;
            $cumulative_after = 0;
            $cumulative_percentage = 0;
            $cumulative_count = 0;

            $chart_data = []; // Data for Highcharts
            ?>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table_medium">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="6">Increase in Crops Yield (ton/ha)</th>
                            </tr>

                            <tr>
                                <th>Crops</th>
                                <th>Before <small>(Ha)</small></th>
                                <th>After <small>(Ha)</small></th>
                                <th>Increase <small>(Ha)</small></th>
                                <th>Increase <small>(%)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($crops as $crop) { ?>
                                <tr>
                                    <th><?php echo ucfirst($crop) ?></th>

                                    <?php
                                    if ($crop != 'vegetable' and $crop != 'orchard') {
                                        $query = "SELECT COUNT(*) as total, 
                                    ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2) AS `before`, 
                                    ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2) AS `after`,
                                    ROUND(((AVG(" . $crop . "_yield_after) * 2.471)/1000) - ((AVG(" . $crop . "_yield_before) * 2.471)/1000),2) as increase,
                                    ROUND(((ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2) - 
                                    ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2))/ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                    FROM `impact_surveys` WHERE " . $crop . "_yield_before IS NOT NULL and " . $crop . "_yield_after IS NOT NULL";
                                        $result = $this->db->query($query)->row();
                                        $chart_data[] = ["name" => ucfirst($crop),  "before" => $result->before, "after" => $result->after, "increase" => $result->increase, "per_increase" => $result->per_increase];
                                    ?>
                                        <td><?php echo $result->before; ?></td>
                                        <td><?php echo $result->after; ?></td>
                                        <td><?php echo $result->increase; ?></td>
                                        <td><?php echo $result->per_increase; ?></td>
                                    <?php } else { ?>
                                        <?php
                                        $query = "SELECT  COUNT(*) as total,
                                              ROUND(AVG(" . $crop . "_yield), 2) AS per_increase
                                              FROM `impact_surveys`";

                                        $result = $this->db->query($query)->row();
                                        ?><td>-</td>
                                        <td>-</td>
                                        <td>-</td>
                                        <td><?php echo $result->per_increase; ?></td>
                                    <?php
                                        $chart_data[] = ["name" => ucfirst($crop), "before" => 0, "after" => 0, "increase" => 0, "per_increase" => $result->per_increase];
                                    } ?>
                                </tr>
                            <?php
                                $befor_weight += $result->before * $result->total;
                                $after_weight += $result->after * $result->total;
                                $increase_weight += $result->increase * $result->total;
                                $total += $result->total;
                                $per_increase_weight += $result->per_increase * $result->total;
                            } ?>
                        </tbody>
                        <tfoot>

                            <tr>
                                <th><small>Weighted Average</small></th>

                                <td class="text-center"><?php echo number_format(round($befor_weight / ($total - (2515 * 2)), 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($after_weight / ($total - (2515 * 2)), 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($increase_weight / ($total - (2515 * 2)), 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($per_increase_weight / $total, 2), 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="cropsYield" style="width: 100%; height: 300px;"></div>
        <script>
            Highcharts.chart('cropsYield', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Crops Yield Increase'
                },
                xAxis: {
                    categories: ["Before", "After", "Increase", "Increase %"]
                },
                yAxis: {
                    title: {
                        text: 'Yield Increase Avg - %'
                    }
                },
                plotOptions: {
                    bar: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: [{
                        name: 'Increase',
                        data: [<?php echo round($befor_weight / ($total - (2515 * 2)), 2) ?>,
                            <?php echo round($after_weight / ($total - (2515 * 2)), 2); ?>,
                            <?php echo round($increase_weight / ($total - (2515 * 2)), 2); ?>,
                            <?php echo round($per_increase_weight / ($total), 2); ?>
                        ]
                    }

                ]
            });
        </script>
    </div>

    <div class="col-md-6">
        <div id="CcropYieldChart" style="width: 100%; height: 300px;"></div>
        <script>
            Highcharts.chart('CcropYieldChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Crops Wise Yield Increase Comparison'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($chart_data, 'name')); ?>
                },
                yAxis: {
                    title: {
                        text: 'Yield Increase Avg - %'
                    }
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: [{
                        name: 'Before',
                        data: <?php echo json_encode(array_column($chart_data, 'before'), JSON_NUMERIC_CHECK); ?>,
                        color: '#ff7b7b'
                    }, {
                        name: 'After',
                        data: <?php echo json_encode(array_column($chart_data, 'after'), JSON_NUMERIC_CHECK); ?>,
                        color: '#7bff7b'
                    }, {
                        name: 'Increase',
                        data: <?php echo json_encode(array_column($chart_data, 'increase'), JSON_NUMERIC_CHECK); ?>,
                        color: '#7b7bff',
                        type: 'spline',
                        marker: {
                            symbol: 'diamond'
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{y}',
                            formatter: function() {
                                return (Math.ceil(this.y * 100) / 100).toFixed(2);
                            }
                        }
                    }

                    // , {
                    //     name: 'Increase %',
                    //     data: <?php echo json_encode(array_column($chart_data, 'per_increase'), JSON_NUMERIC_CHECK); ?>
                    // }

                ]
            });
        </script>
    </div>
    <div class="col-md-6">
        <div id="CcropYieldChartPercentage" style="width: 100%; height: 300px;"></div>
        <script>
            Highcharts.chart('CcropYieldChartPercentage', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Crops Wise Yield Increase Comparison'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($chart_data, 'name')); ?>
                },
                yAxis: {
                    title: {
                        text: 'Yield Increase Avg - %'
                    }
                },
                plotOptions: {
                    bar: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} %'
                        }
                    }
                },
                series: [{
                        name: 'Increase %',
                        data: <?php echo json_encode(array_column($chart_data, 'per_increase'), JSON_NUMERIC_CHECK); ?>,
                        color: '#ffc107'
                    }

                ]
            });
        </script>
    </div>

    <div class="col-md-12"></div>



</div>
</div>
</div>
</div>


<div class="row">
    <div class="col-md-12">
        <?php
        $crops = array("wheat", "maize", "sugarcane", "vegetable", "orchard");
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components) * 3) + 4; ?>">Increase in Crops Yield (ton/ha)</th>
                </tr>
                <tr>
                    <th rowspan="2">Crops</th>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="5"><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th colspan="5">Cumulative</th>
                </tr>
                <tr>

                    <?php foreach ($components as $component) { ?>
                        <th>Total</th>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Total</th>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php
                $weighted_average = [];
                $total = 0;
                foreach ($crops as $crop) { ?>
                    <tr>
                        <th><?php echo ucfirst($crop) ?></th>
                        <?php foreach ($components as $component) {
                            if ($crop != 'vegetable' and $crop != 'orchard') {
                                $query = "SELECT 
                                COUNT(*) as total,
                                ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2) AS `before`, 
                                ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2) AS `after`,
                                ROUND(((AVG(" . $crop . "_yield_after) * 2.471)/1000) - ((AVG(" . $crop . "_yield_before) * 2.471)/1000),2) as increase,
                                ROUND(((ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2) - 
                                ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2))/ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                FROM `impact_surveys`  
                                WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row(); ?>
                                <td><?php echo $result->total; ?></td>
                                <td><?php echo $result->before; ?></td>
                                <td><?php echo $result->after; ?></td>
                                <td><?php echo $result->increase; ?></td>
                                <td><?php echo $result->per_increase; ?></td>
                            <?php } else { ?>
                                <?php
                                $query = "SELECT 
                                COUNT(*) as total,
                                ROUND(AVG(" . $crop . "_yield),2) AS per_increase
                                FROM `impact_surveys`
                                WHERE component = ? ";

                                $result = $this->db->query($query, [$component->component])->row();
                                ?>
                                <td><?php echo $result->total; ?></td>
                                <td>-</td>
                                <td>-</td>
                                <td>-</td>
                                <td><?php echo $result->per_increase; ?></td>

                        <?php }

                            $weighted_average[$component->component]['total'] += $result->total;
                            $weighted_average[$component->component]['before'] += $result->before * $result->total;
                            $weighted_average[$component->component]['after'] += $result->after * $result->total;
                            $weighted_average[$component->component]['increase'] += $result->increase * $result->total;
                            $weighted_average[$component->component]['per_increase'] += $result->per_increase * $result->total;
                            $total += $result->total;
                        } ?>

                        <?php
                        if ($crop != 'vegetable' and $crop != 'orchard') {
                            $query = "SELECT 
                            COUNT(*) as total,
                            ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((AVG(" . $crop . "_yield_after) * 2.471)/1000) - ((AVG(" . $crop . "_yield_before) * 2.471)/1000),2) as increase,
                            ROUND(((ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2))/ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                        ?>
                            <td><?php echo $result->total; ?></td>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->increase; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } else { ?>
                            <?php
                            $query = "SELECT 
                            COUNT(*) as total,
                            ROUND(AVG(" . $crop . "_yield), 2) AS per_increase
                                FROM `impact_surveys`";

                            $result = $this->db->query($query)->row();
                            ?>
                            <td><?php echo $result->total; ?></td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php }
                        $weighted_average['Over All']['total'] += $result->total;
                        $weighted_average['Over All']['before'] += $result->before * $result->total;
                        $weighted_average['Over All']['after'] += $result->after * $result->total;
                        $weighted_average['Over All']['increase'] += $result->increase * $result->total;
                        $weighted_average['Over All']['per_increase'] += $result->per_increase * $result->total;
                        $total += $result->total;
                        ?>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>

                <tr>
                    <th>Weighted AVG</th>
                    <?php
                    foreach ($weighted_average as $key => $value) { ?>
                        <td><?php //echo $value['total'];
                            echo $value['total'] / 5;

                            ?></td>
                        <td><?php echo number_format(round($value['before'] / ($value['total'] - (($value['total'] / 5) * 2)), 2), 2); ?></td>
                        <td><?php echo number_format(round($value['after'] / ($value['total'] - (($value['total'] / 5) * 2)), 2), 2); ?></td>
                        <td><?php echo number_format(round($value['increase'] / ($value['total'] - (($value['total'] / 5) * 2)), 2), 2); ?></td>
                        <td><?php echo number_format(round($value['per_increase'] / $value['total'], 2), 2); ?></td>
                    <?php } ?>
                </tr>

            </tfoot>

        </table>


    </div>
    <div class="col-md-6">
        <div id="cropYieldChart" style="width: 100%; height: 300px;"></div>


        <?php
        $crops = ["wheat", "maize", "sugarcane", "vegetable", "orchard"];
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

        $data = [];

        foreach ($crops as $crop) {
            $cropData = [
                'name' => ucfirst($crop),
                'data' => []
            ];

            foreach ($components as $component) {
                if ($crop !== 'vegetable' && $crop !== 'orchard') {
                    $query = "SELECT 
                        COALESCE(ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2), 0) AS `before`, 
                        COALESCE(ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2), 0) AS `after`,
                        COALESCE(ROUND(((ROUND((AVG(" . $crop . "_yield_after) * 2.471)/1000, 2) - 
                        ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2)) /
                        ROUND((AVG(" . $crop . "_yield_before) * 2.471)/1000, 2)) * 100, 2), 0) AS per_increase
                      FROM `impact_surveys`  
                      WHERE component = ?";
                } else {
                    $query = "SELECT COALESCE(ROUND(AVG(" . $crop . "_yield),2), 0) AS per_increase 
                      FROM `impact_surveys` WHERE component = ?";
                }

                $result = $this->db->query($query, [$component->component])->row();
                if ($result->per_increase) {
                    $cropData['data'][] = $result->per_increase;
                } else {
                    $cropData['data'][] =  0;
                }
                //$cropData['data'][] = $result->per_increase ?? 0; // Ensure no NULL values
            }

            $data[] = $cropData;
        }
        ?>

        <script>
            Highcharts.chart('cropYieldChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Crops Yield (%)'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($components, 'component')); ?>,
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Yield Increase (%)'
                    }
                },
                legend: {
                    enabled: true
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: <?php echo json_encode($data, JSON_NUMERIC_CHECK); ?>
            });
        </script>

    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components) * 3) + 4; ?>">Increase in Wheat Yield (ton/ha)</th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="3"><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th colspan="3">Cumulative</th>
                </tr>
                <tr>

                    <?php foreach ($components as $component) { ?>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($components as $component) {
                            $query = "SELECT ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`
                            WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($components as $component) {
                        $query = "SELECT ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>
        <div id="wheat_yield" style="width:100%;"></div>
        <script>
            Highcharts.chart('wheat_yield', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Wheat Yield (ton/ha)'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($components as $component) {
                            echo "'" . $component->component . "',";
                        } ?> 'Cumulative'
                    ],
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Weighted Avg.'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' '
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: [{
                        name: 'Before Avg(Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            $query = "SELECT ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before` 
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->before . ",";
                            ?>
                        ]
                    },
                    {
                        name: 'After Avg(Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            $query = "SELECT ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after` 
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->after . ",";
                            ?>
                        ]
                    },
                    {
                        name: 'Increase Avg(%)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                                ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->per_increase . ",";
                            }
                            $query = "SELECT ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                                ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->per_increase . ",";
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components) * 3) + 4; ?>">Increase in Maize Yield (ton/ha)</th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="3"><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th colspan="3">Cumulative</th>
                </tr>
                <tr>

                    <?php foreach ($components as $component) { ?>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($components as $component) {
                            $query = "SELECT ROUND((AVG(maize_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(maize_yield_before) * 2.471)/1000, 2))/ROUND((AVG(maize_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND((AVG(maize_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(maize_yield_before) * 2.471)/1000, 2))/ROUND((AVG(maize_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`
                            WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($components as $component) {
                        $query = "SELECT ROUND((AVG(maize_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(maize_yield_before) * 2.471)/1000, 2))/ROUND((AVG(maize_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND((AVG(maize_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(maize_yield_before) * 2.471)/1000, 2))/ROUND((AVG(maize_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>
        <div id="maize_yield" style="width:100%;"></div>
        <script>
            Highcharts.chart('maize_yield', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Maize Yield (ton/ha)'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($components as $component) {
                            echo "'" . $component->component . "',";
                        } ?> 'Cumulative'
                    ],
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Weighted Avg.'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' '
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: [{
                        name: 'Before Avg(Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND((AVG(maize_yield_before) * 2.471)/1000, 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            $query = "SELECT ROUND((AVG(maize_yield_before) * 2.471)/1000, 2) AS `before` 
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->before . ",";
                            ?>
                        ]
                    },
                    {
                        name: 'After Avg(Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            $query = "SELECT ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) AS `after` 
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->after . ",";
                            ?>
                        ]
                    },
                    {
                        name: 'Increase Avg(%)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(((ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) - 
                                ROUND((AVG(maize_yield_before) * 2.471)/1000, 2))/ROUND((AVG(maize_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->per_increase . ",";
                            }
                            $query = "SELECT ROUND(((ROUND((AVG(maize_yield_after) * 2.471)/1000, 2) - 
                                ROUND((AVG(maize_yield_before) * 2.471)/1000, 2))/ROUND((AVG(maize_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->per_increase . ",";
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>
</div>

<hr />

<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components) * 3) + 4; ?>">Increase in Sugarcane Yield (ton/ha)</th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="3"><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th colspan="3">Cumulative</th>
                </tr>
                <tr>

                    <?php foreach ($components as $component) { ?>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($components as $component) {
                            $query = "SELECT ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2))/ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2))/ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`
                            WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($components as $component) {
                        $query = "SELECT ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2))/ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2))/ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>


    </div>
    <div class="col-md-6">
        <div id="sugarcane_yield" style="width:100%;"></div>
        <script>
            Highcharts.chart('sugarcane_yield', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Sugarcane Yield (ton/ha)'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($components as $component) {
                            echo "'" . $component->component . "',";
                        } ?> 'Cumulative'
                    ],
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Weighted Avg.'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' '
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: [{
                        name: 'Before Avg(Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            $query = "SELECT ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2) AS `before` 
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->before . ",";
                            ?>
                        ]
                    },
                    {
                        name: 'After Avg(Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            $query = "SELECT ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) AS `after` 
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->after . ",";
                            ?>
                        ]
                    },
                    {
                        name: 'Increase Avg(%)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(((ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) - 
                                ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2))/ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->per_increase . ",";
                            }
                            $query = "SELECT ROUND(((ROUND((AVG(sugarcane_yield_after) * 2.471)/1000, 2) - 
                                ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2))/ROUND((AVG(sugarcane_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys`";
                            $result = $this->db->query($query)->row();
                            echo $result->per_increase . ",";
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components)) + 4; ?>">Increase in Vegetables Yield (ton/ha)</th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($components as $component) { ?>
                        <th><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th>Cumulative</th>
                </tr>
                <tr>

                    <?php foreach ($components as $component) { ?>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($components as $component) {
                            $query = "SELECT ROUND(AVG(vegetable_yield),2) AS `vegetable_yield`
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?><td><?php echo $result->vegetable_yield; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND(AVG(vegetable_yield),2) AS `vegetable_yield`
                        FROM `impact_surveys`
                            WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->vegetable_yield; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($components as $component) {
                        $query = "SELECT ROUND(AVG(vegetable_yield),2) AS `vegetable_yield`
                        FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->vegetable_yield; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND(AVG(vegetable_yield),2) AS `vegetable_yield`
                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->vegetable_yield; ?></td>

                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6">
        <div id="vegetable_yield" style="width:100%;"></div>
        <script>
            Highcharts.chart('vegetable_yield', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Vegetables Yield (ton/ha)',
                    align: 'center'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($components as $component) {
                            echo "'" . $component->component . "',";
                        } ?> 'Overall'
                    ],
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG - %'
                    },
                    plotLines: [{
                        color: 'red', // Red color for the average line
                        width: 2, // Line thickness
                        value: <?php
                                $query = "SELECT ROUND(AVG(vegetable_yield),2) AS `vegetable_yield` FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();
                                echo $result->vegetable_yield;
                                ?>,
                        dashStyle: 'Dash', // Dashed line style
                        zIndex: 5, // Ensures the line appears above columns
                        label: {
                            text: 'Overall AVG <?php echo $result->vegetable_yield; ?>',
                            align: 'right',
                            verticalAlign: 'top',
                            style: {
                                color: 'red',
                                fontWeight: 'bold'
                            }
                        }
                    }]
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' %'
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} %'
                        }
                    }
                },
                series: [{
                    name: 'Increase',
                    color: 'rgb(0, 226, 114)', // Green color for columns
                    data: [
                        <?php
                        foreach ($components as $component) {
                            $query = "SELECT ROUND(AVG(vegetable_yield),2) AS `vegetable_yield`
                        FROM `impact_surveys` WHERE component = ? ";
                            $result = $this->db->query($query, [$component->component])->row();
                            echo $result->vegetable_yield . ",";
                        }
                        ?>
                    ]
                }]
            });
        </script>




    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div id="orchard_yield" style="width:100%;"></div>
        <script>
            Highcharts.chart('orchard_yield', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Orchard Yield (ton/ha)',
                    align: 'center'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($components as $component) {
                            echo "'" . $component->component . "',";
                        } ?> 'Overall'
                    ],
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG - %'
                    },
                    plotLines: [{
                        color: 'red', // Red color for the average line
                        width: 2, // Line thickness
                        value: <?php
                                $query = "SELECT ROUND(AVG(orchard_yield),2) AS `orchard_yield` FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();
                                echo $result->orchard_yield;
                                ?>,
                        dashStyle: 'Dash', // Dashed line style
                        zIndex: 5, // Ensures the line appears above columns
                        label: {
                            text: 'Overall AVG <?php echo $result->orchard_yield; ?>',
                            align: 'right',
                            verticalAlign: 'top',
                            style: {
                                color: 'red',
                                fontWeight: 'bold'
                            }
                        }
                    }]
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' %'
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} %'
                        }
                    }
                },
                series: [{
                    name: 'Increase',
                    color: 'rgb(0, 226, 114)', // Green color for columns
                    data: [
                        <?php
                        foreach ($components as $component) {
                            $query = "SELECT ROUND(AVG(orchard_yield),2) AS `orchard_yield`
                        FROM `impact_surveys` WHERE component = ? ";
                            $result = $this->db->query($query, [$component->component])->row();
                            echo $result->orchard_yield . ",";
                        }
                        ?>
                    ]
                }]
            });
        </script>




    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components)) + 4; ?>">Increase in Orchard Yield (ton/ha)</th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($components as $component) { ?>
                        <th><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th>Cumulative</th>
                </tr>
                <tr>

                    <?php foreach ($components as $component) { ?>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($components as $component) {
                            $query = "SELECT ROUND(AVG(orchard_yield),2) AS `orchard_yield`
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?><td><?php echo $result->orchard_yield; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND(AVG(orchard_yield),2) AS `orchard_yield`
                        FROM `impact_surveys`
                            WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->orchard_yield; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($components as $component) {
                        $query = "SELECT ROUND(AVG(orchard_yield),2) AS `orchard_yield`
                        FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->orchard_yield; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND(AVG(orchard_yield),2) AS `orchard_yield`
                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->orchard_yield; ?></td>

                </tr>
            </tfoot>
        </table>
    </div>

</div>