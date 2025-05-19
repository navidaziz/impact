<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis on Increase in Cropping Patteren</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Cropping_Pattern'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard' ], ['Summary', 'Crop & Component Wise' , 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>


<hr />
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $crops = array("wheat", "maize", "maize_hybrid", "sugarcane", "fodder", "vegetable", "fruit_orchard");
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
                <strong>Average Increase in Cropping Patteren</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" style="font-size: 12px;" id="table_1">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="6">Increase in Cropping Patteren </th>
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
                                    $query = "SELECT COUNT(*) as total, 
                                    ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2) AS `before`, 
                                    ROUND((AVG(" . $crop . "_cp_after) * 2.471), 2) AS `after`,
                                    ROUND(((AVG(" . $crop . "_cp_after) * 2.471)) - ((AVG(" . $crop . "_cp_before) * 2.471)),2) as increase,
                                    ROUND(((ROUND((AVG(" . $crop . "_cp_after) * 2.471), 2) - 
                                    ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2))/ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2)) * 100, 2) AS per_increase
                                    FROM `impact_surveys` WHERE " . $crop . "_cp_before IS NOT NULL and " . $crop . "_cp_after IS NOT NULL";
                                    $result = $this->db->query($query)->row();
                                    $chart_data[] = ["name" => ucfirst($crop),  "before" => $result->before, "after" => $result->after, "increase" => $result->increase, "per_increase" => $result->per_increase];
                                    ?>
                                    <td class="text-center"><?php echo $result->before; ?></td>
                                    <td class="text-center"><?php echo $result->after; ?></td>
                                    <td class="text-center"><?php echo $result->increase; ?></td>
                                    <th class="text-center"><?php echo $result->per_increase; ?></th>

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
        <div id="cropsPatteren" style="width: 100%; "></div>
        <script>
            Highcharts.chart('cropsPatteren', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Cropping Patteren Increase'
                },
                xAxis: {
                    categories: ["Before", "After", "Increase", "Increase %"]
                },
                yAxis: {
                    title: {
                        text: 'Patteren Increase Avg - %'
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
        <div id="CcropPatterenChart" style="width: 100%; height: 300px;"></div>
        <script>
            Highcharts.chart('CcropPatterenChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Crops Wise Patteren Increase Comparison'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($chart_data, 'name')); ?>
                },
                yAxis: {
                    title: {
                        text: 'Patteren Increase Avg - %'
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
        <div id="CcropPatterenChartPercentage" style="width: 100%; height: 300px;"></div>
        <script>
            Highcharts.chart('CcropPatterenChartPercentage', {
                chart: {
                    type: 'bar'
                },
                title: {
                    text: 'Crops Wise Patteren Increase Comparison'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($chart_data, 'name')); ?>
                },
                yAxis: {
                    title: {
                        text: 'Patteren Increase Avg - %'
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
                <strong>Component Wise Increase in Cropping Patteren </strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table_medium" id="table_2">
                        <thead>
                            <tr>
                                <th style="display: none;" colspan="<?php echo (count($components) * 3) + 4; ?>">Component Wise Increase in Cropping Patteren </th>
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
                                        $query = "SELECT 
                                            COUNT(*) as total,
                                            ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2) AS `before`, 
                                            ROUND((AVG(" . $crop . "_cp_after) * 2.471), 2) AS `after`,
                                            ROUND(((AVG(" . $crop . "_cp_after) * 2.471)) - ((AVG(" . $crop . "_cp_before) * 2.471)),2) as increase,
                                            ROUND(((ROUND((AVG(" . $crop . "_cp_after) * 2.471), 2) - 
                                            ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2))/ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2)) * 100, 2) AS per_increase
                                            FROM `impact_surveys`  
                                            WHERE component = ? ";
                                        $result = $this->db->query($query, [$component->component])->row(); ?>
                                        <td style="text-align: center;"><?php echo $result->before; ?></td>
                                        <td style="text-align: center;"><?php echo $result->after; ?></td>
                                        <td style="text-align: center;"><?php echo $result->increase; ?></td>
                                        <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
                                    <?php
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
                                    $query = "SELECT 
                                        COUNT(*) as total,
                                        ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2) AS `before`, 
                                        ROUND((AVG(" . $crop . "_cp_after) * 2.471), 2) AS `after`,
                                        ROUND(((AVG(" . $crop . "_cp_after) * 2.471)) - ((AVG(" . $crop . "_cp_before) * 2.471)),2) as increase,
                                        ROUND(((ROUND((AVG(" . $crop . "_cp_after) * 2.471), 2) - 
                                        ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2))/ROUND((AVG(" . $crop . "_cp_before) * 2.471), 2)) * 100, 2) AS per_increase
                                        FROM `impact_surveys`";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td style="text-align: center;"><?php echo $result->before; ?></td>
                                    <td style="text-align: center;"><?php echo $result->after; ?></td>
                                    <td style="text-align: center;"><?php echo $result->increase; ?></td>
                                    <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
                                    <?php
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
                    text: 'Increase in Cropping Patteren (%)'
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
                        text: 'Patteren Increase (%)'
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
        <div id="cropPatterenChart" style="width: 100%;"></div>


        <script>
            Highcharts.chart('cropPatterenChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Increase in Cropping Patteren (%)'
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
                        text: 'Patteren Increase (%)'
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
    <?php
    foreach ($crops as $s_crop) { ?>
        <div class="col-md-12" style="margin-bottom: 10px;">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Region and Component Wise Increase in <?php echo ucfirst($s_crop); ?> Patteren </strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table_medium" id="<?php echo $s_crop; ?>">
                            <thead>
                                <tr style="display: none;">
                                    <th colspan="<?php echo ((1 + (count($components) * 5)) + 5); ?>">Region and Component Wise Increase in <?php echo ucfirst($s_crop) ?> Patteren </th>
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
                                            ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2) AS `before`, 
                                            ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) AS `after`,
                                            ROUND(((AVG(" . $s_crop . "_cp_after) * 2.471)) - ((AVG(" . $s_crop . "_cp_before) * 2.471)),2) as increase,
                                            ROUND(((ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) - 
                                            ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2))/ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2)) * 100, 2) AS per_increase
                                            FROM `impact_surveys`  
                                            WHERE component = ?
                                            AND region = ? ";
                                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                                        ?>
                                            <td style="text-align: center;"><?php echo $result->total; ?></td>
                                            <td style="text-align: center;"><?php echo $result->before; ?></td>
                                            <td style="text-align: center;"><?php echo $result->after; ?></td>
                                            <td style="text-align: center;"><?php echo $result->increase; ?></td>
                                            <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
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
                                            ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2) AS `before`, 
                                            ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) AS `after`,
                                            ROUND(((AVG(" . $s_crop . "_cp_after) * 2.471)) - ((AVG(" . $s_crop . "_cp_before) * 2.471)),2) as increase,
                                            ROUND(((ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) - 
                                            ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2))/ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2)) * 100, 2) AS per_increase
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
                                        <td style="text-align: center;"><?php echo $result->total; ?></td>
                                        <td style="text-align: center;"><?php echo $result->before; ?></td>
                                        <td style="text-align: center;"><?php echo $result->after; ?></td>
                                        <td style="text-align: center;"><?php echo $result->increase; ?></td>
                                        <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
                                    </tr>
                                <?php

                                } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Average</th>
                                    <?php foreach ($components as $component) {
                                        $query = "SELECT COUNT(*) as total,
                                        ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2) AS `before`, 
                                        ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) AS `after`,
                                        ROUND(((AVG(" . $s_crop . "_cp_after) * 2.471)) - ((AVG(" . $s_crop . "_cp_before) * 2.471)),2) as increase,
                                        ROUND(((ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) - 
                                        ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2))/ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2)) * 100, 2) AS per_increase
                                        FROM `impact_surveys`  
                                        WHERE component = ? ";
                                        $result = $this->db->query($query, [$component->component])->row();
                                    ?>
                                        <th style="text-align: center;"><?php echo $result->total; ?></th>
                                        <th style="text-align: center;"><?php echo $result->before; ?></th>
                                        <th style="text-align: center;"><?php echo $result->after; ?></th>
                                        <th style="text-align: center;"><?php echo $result->increase; ?></th>
                                        <th style="text-align: center;"><?php echo $result->per_increase; ?></th>
                                    <?php } ?>
                                    <?php $query = "SELECT 
                                        COUNT(*) as total,
                                        ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2) AS `before`, 
                                        ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) AS `after`,
                                        ROUND(((AVG(" . $s_crop . "_cp_after) * 2.471)) - ((AVG(" . $s_crop . "_cp_before) * 2.471)),2) as increase,
                                        ROUND(((ROUND((AVG(" . $s_crop . "_cp_after) * 2.471), 2) - 
                                        ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2))/ROUND((AVG(" . $s_crop . "_cp_before) * 2.471), 2)) * 100, 2) AS per_increase
                                        FROM `impact_surveys` ";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <th style="text-align: center;"><?php echo $result->total; ?></th>
                                    <th style="text-align: center;"><?php echo $result->before; ?></th>
                                    <th style="text-align: center;"><?php echo $result->after; ?></th>
                                    <th style="text-align: center;"><?php echo $result->increase; ?></th>
                                    <th style="text-align: center;"><?php echo $result->per_increase; ?></th>

                                </tr>
                                <tr>
                                    <th>Weighted Average</th>
                                    <?php
                                    foreach ($weighted_average as $key => $value) { ?>
                                        <th style="text-align: center;"><?php echo $value['total']; ?></th>
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

    <?php } ?>
</div>


<hr />