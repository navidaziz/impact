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
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
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
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Average Increase in Crops Yield</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 12px;">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="6">Increase in Crops Yield (ton/ha)</th>
                            </tr>

                            <tr>
                                <th>Crops</th>
                                <th class="text-center">Before <small>(Ha)</small></th>
                                <th class="text-center">After <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(%)</small></th>
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
                                        <td class="text-center"><?php echo $result->before; ?></td>
                                        <td class="text-center"><?php echo $result->after; ?></td>
                                        <td class="text-center"><?php echo $result->increase; ?></td>
                                        <th class="text-center"><?php echo $result->per_increase; ?></th>
                                    <?php } else { ?>
                                        <?php
                                        $query = "SELECT  COUNT(*) as total,
                                              ROUND(AVG(" . $crop . "_yield), 2) AS per_increase
                                              FROM `impact_surveys`";

                                        $result = $this->db->query($query)->row();
                                        ?><td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">-</td>
                                        <th class="text-center"><?php echo $result->per_increase; ?></th>
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
                                <th>Average</th>

                                <th class="text-center"><?php echo number_format(round($befor_weight / ($total - (2515 * 2)), 2), 2); ?></th>
                                <th class="text-center"><?php echo number_format(round($after_weight / ($total - (2515 * 2)), 2), 2); ?></th>
                                <th class="text-center"><?php echo number_format(round($increase_weight / ($total - (2515 * 2)), 2), 2); ?></th>
                                <th class="text-center"><?php echo number_format(round($per_increase_weight / $total, 2), 2); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="cropsYield" style="width: 100%; "></div>
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

</div>


<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Component Wise Increase in Crops Yield (ton/ha)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table_medium">
                        <thead>
                            <tr>
                                <th style="display: none;" colspan="<?php echo (count($components) * 3) + 4; ?>">Component Wise Increase in Crops Yield (ton/ha)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Crops</th>
                                <?php foreach ($components as $component) { ?>
                                    <th colspan="4">Component <?php echo $component->component; ?></th>
                                <?php } ?>
                                <th colspan="4">Cumulative</th>
                            </tr>
                            <tr>

                                <?php foreach ($components as $component) { ?>
                                    <th>Before <small>(Ha)</small></th>
                                    <th>After <small>(Ha)</small></th>
                                    <th>Increase <small>(Ha)</small></th>
                                    <th>Increase <small>(%)</small></th>
                                <?php } ?>
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
                            $componentData['Percentage Increase'] = [
                                'name' => 'Percentage Increase',
                                'data' => []
                            ];
                            foreach ($crops as $crop) {
                                $cropData[ucfirst($crop)] = [
                                    'name' => ucfirst($crop),
                                    'data' => []
                                ];

                            ?>
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
                                            <td style="text-align: center;"><?php echo $result->before; ?></td>
                                            <td style="text-align: center;"><?php echo $result->after; ?></td>
                                            <td style="text-align: center;"><?php echo $result->increase; ?></td>
                                            <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
                                        <?php } else { ?>
                                            <?php
                                            $query = "SELECT 
                                            COUNT(*) as total,
                                            ROUND(AVG(" . $crop . "_yield),2) AS per_increase
                                            FROM `impact_surveys`
                                            WHERE component = ? ";

                                            $result = $this->db->query($query, [$component->component])->row();
                                            ?>

                                            <td style="text-align: center;">-</td>
                                            <td style="text-align: center;">-</td>
                                            <td style="text-align: center;">-</td>
                                            <th style="text-align: center;"><?php echo $result->per_increase; ?></th>

                                    <?php }

                                        if ($result->per_increase) {
                                            $cropData[ucfirst($crop)]['data'][] = $result->per_increase;
                                        } else {
                                            $cropData[ucfirst($crop)]['data'][] = 0;
                                        }

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
                                        <td style="text-align: center;"><?php echo $result->before; ?></td>
                                        <td style="text-align: center;"><?php echo $result->after; ?></td>
                                        <td style="text-align: center;"><?php echo $result->increase; ?></td>
                                        <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
                                    <?php } else { ?>
                                        <?php
                                        $query = "SELECT 
                                        COUNT(*) as total,
                                        ROUND(AVG(" . $crop . "_yield), 2) AS per_increase
                                        FROM `impact_surveys`";

                                        $result = $this->db->query($query)->row();
                                        ?>
                                        <td style="text-align: center;">-</td>
                                        <td style="text-align: center;">-</td>
                                        <td style="text-align: center;">-</td>
                                        <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
                                    <?php }
                                    if ($result->per_increase) {
                                        $cropData[ucfirst($crop)]['data'][] = $result->per_increase;
                                    } else {
                                        $cropData[ucfirst($crop)]['data'][] = 0;
                                    }
                                    $weighted_average['Over All']['total'] += $result->total;
                                    $weighted_average['Over All']['before'] += $result->before * $result->total;
                                    $weighted_average['Over All']['after'] += $result->after * $result->total;
                                    $weighted_average['Over All']['increase'] += $result->increase * $result->total;
                                    $weighted_average['Over All']['per_increase'] += $result->per_increase * $result->total;
                                    $total += $result->total;
                                    ?>
                                </tr>
                            <?php


                                $component->weighted_avg = $weighted_average;
                            } ?>
                        </tbody>
                        <tfoot>

                            <tr>
                                <th>Average</th>
                                <?php
                                foreach ($weighted_average as $key => $value) { ?>
                                    <th style="text-align: center;"><?php echo number_format(round($value['before'] / ($value['total'] - (($value['total'] / 5) * 2)), 2), 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format(round($value['after'] / ($value['total'] - (($value['total'] / 5) * 2)), 2), 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format(round($value['increase'] / ($value['total'] - (($value['total'] / 5) * 2)), 2), 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format(round($value['per_increase'] / $value['total'], 2), 2); ?></th>
                                <?php
                                    if ($value['per_increase']) {
                                        $componentData['Percentage Increase']['data'][] = $value['per_increase'] / $value['total'];
                                    } else {
                                        $componentData['Percentage Increase']['data'][] = 0;
                                    }
                                } ?>
                            </tr>

                        </tfoot>

                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="col-md-4">
        <div id="ComponentAverageIncrese" style="width: 100; height:300px"></div>

        <script>
            Highcharts.chart('ComponentAverageIncrese', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Increase in Crops Yield (%)'
                },
                xAxis: {
                    categories: <?php
                                $componentNames = array_column($components, 'component');
                                $componentNames[] = 'Percentage Incress'; // Add "Overall" to the end
                                echo json_encode($componentNames);
                                ?>,
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
                    bar: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} %'
                        }
                    }
                },
                series: <?php echo json_encode(array_values($componentData), JSON_NUMERIC_CHECK); ?>
            });
        </script>
    </div>
    <div class="col-md-12">
        <div id="cropYieldChart" style="width: 100%;"></div>


        <script>
            Highcharts.chart('cropYieldChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Crops Yield (%)'
                },
                xAxis: {
                    categories: <?php
                                $componentNames = array_column($components, 'component');
                                $componentNames[] = 'Cumulative'; // Add "Overall" to the end
                                echo json_encode($componentNames);
                                ?>,
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
                            format: '{point.y:.2f} %'
                        }
                    }
                },
                series: <?php echo json_encode(array_values($cropData), JSON_NUMERIC_CHECK); ?>
            });
        </script>

    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region and Component Wise Increase in Wheat Yield (ton/ha)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table_medium">
                        <thead>
                            <tr>
                                <th colspan="<?php echo ((1 + (count($components) * 5)) + 5); ?>">Region and Component Wise Increase in Wheat Yield (ton/ha)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Regions</th>
                                <?php foreach ($components as $component) { ?>
                                    <th colspan="5">Component <?php echo $component->component; ?></th>
                                <?php } ?>
                                <th colspan="5">Cumulative</th>
                            </tr>
                            <tr>

                                <?php foreach ($components as $component) { ?>
                                    <th>Total </th>
                                    <th>Before <small>(Ha)</small></th>
                                    <th>After <small>(Ha)</small></th>
                                    <th>Increase <small>(Ha)</small></th>
                                    <th>Increase <small>(%)</small></th>
                                <?php } ?>
                                <th>Total </th>
                                <th>Before <small>(Ha)</small></th>
                                <th>After <small>(Ha)</small></th>
                                <th>Increase <small>(Ha)</small></th>
                                <th>Increase <small>(%)</small></th>
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
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((AVG(wheat_yield_after) * 2.471)/1000) - ((AVG(wheat_yield_before) * 2.471)/1000),2) as increase,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                                        $result = $this->db->query($query, [$component->component, $region->region])->row();
                                    ?>
                                        <td><?php echo $result->total; ?></td>
                                        <td><?php echo $result->before; ?></td>
                                        <td><?php echo $result->after; ?></td>
                                        <td><?php echo $result->increase; ?></td>
                                        <th><?php echo $result->per_increase; ?></th>
                                    <?php
                                        $weighted_average[$component->component]['total'] += $result->total;
                                        $weighted_average[$component->component]['before'] += $result->before * $result->total;
                                        $weighted_average[$component->component]['after'] += $result->after * $result->total;
                                        $weighted_average[$component->component]['increase'] += $result->increase * $result->total;
                                        $weighted_average[$component->component]['per_increase'] += $result->per_increase * $result->total;
                                        $total += $result->total;
                                    } ?>

                                    <?php $query = "SELECT 
                            COUNT(*) as total,
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((AVG(wheat_yield_after) * 2.471)/1000) - ((AVG(wheat_yield_before) * 2.471)/1000),2) as increase,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`
                            WHERE  region = ? ";
                                    $result = $this->db->query($query, [$region->region])->row();
                                    $weighted_average['commulative']['total'] += $result->total;
                                    $weighted_average['commulative']['before'] += $result->before * $result->total;
                                    $weighted_average['commulative']['after'] += $result->after * $result->total;
                                    $weighted_average['commulative']['increase'] += $result->increase * $result->total;
                                    $weighted_average['commulative']['per_increase'] += $result->per_increase * $result->total;
                                    $total += $result->total;
                                    ?>
                                    <td><?php echo $result->total; ?></td>
                                    <td><?php echo $result->before; ?></td>
                                    <td><?php echo $result->after; ?></td>
                                    <td><?php echo $result->increase; ?></td>
                                    <th><?php echo $result->per_increase; ?></th>
                                </tr>
                            <?php

                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($components as $component) {
                                    $query = "SELECT COUNT(*) as total,
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((AVG(wheat_yield_after) * 2.471)/1000) - ((AVG(wheat_yield_before) * 2.471)/1000),2) as increase,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                                    $result = $this->db->query($query, [$component->component])->row();
                                ?>
                                    <th><?php echo $result->total; ?></th>
                                    <th><?php echo $result->before; ?></th>
                                    <th><?php echo $result->after; ?></th>
                                    <th><?php echo $result->increase; ?></th>
                                    <th><?php echo $result->per_increase; ?></th>
                                <?php } ?>
                                <?php $query = "SELECT 
                            COUNT(*) as total,
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2) AS `before`, 
                            ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) AS `after`,
                            ROUND(((AVG(wheat_yield_after) * 2.471)/1000) - ((AVG(wheat_yield_before) * 2.471)/1000),2) as increase,
                            ROUND(((ROUND((AVG(wheat_yield_after) * 2.471)/1000, 2) - 
                            ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2))/ROUND((AVG(wheat_yield_before) * 2.471)/1000, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys` ";
                                $result = $this->db->query($query)->row();
                                ?>
                                <th><?php echo $result->total; ?></th>
                                <th><?php echo $result->before; ?></th>
                                <th><?php echo $result->after; ?></th>
                                <th><?php echo $result->increase; ?></th>
                                <th><?php echo $result->per_increase; ?></th>

                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <?php
                                foreach ($weighted_average as $key => $value) { ?>
                                    <th><?php echo $value['total']; ?></th>
                                    <th style="text-align: center;"><?php echo round($value['before'] / ($value['total']), 2); ?></th>
                                    <th style="text-align: center;"><?php echo round($value['after'] / ($value['total']), 2); ?></th>
                                    <th style="text-align: center;"><?php echo round($value['increase'] / ($value['total']), 2); ?></th>
                                    <th style="text-align: center;"><?php echo round($value['per_increase'] / ($value['total']), 2); ?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>




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