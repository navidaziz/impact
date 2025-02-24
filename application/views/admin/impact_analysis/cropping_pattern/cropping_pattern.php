<h4>Impact Analysis on Cropping Patteren</h4>

<div class="row">
    <div class="col-md-6">
        <?php
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
        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table_medium">
                    <thead>
                        <tr>
                            <th colspan="4">Average Change in Crop Area</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Crops</th>
                            <th colspan="3">Cumulative</th>
                        </tr>
                        <tr>
                            <th>Before <small>(Ha)</small></th>
                            <th>After <small>(Ha)</small></th>
                            <th>Increase <small>(%)</small></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($crops as $crop) { ?>
                            <tr>
                                <th><?php echo ucfirst($crop) ?></th>

                                <?php

                                $query = "SELECT ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2) AS `before`, 
                        ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2) AS `after`,
                        ROUND(((ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2) - 
                        ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                        FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();

                                // Accumulate values
                                $cumulative_before += $result->before;
                                $cumulative_after += $result->after;
                                $cumulative_percentage += $result->per_increase;
                                $cumulative_count++;

                                // Store data for chart
                                $chart_data[] = ["name" => ucfirst($crop), "before" => $result->before, "after" => $result->after, "per_increase" => $result->per_increase];
                                ?>
                                <td><?php echo $result->before; ?></td>
                                <td><?php echo $result->after; ?></td>
                                <td><?php echo $result->per_increase; ?></td>


                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Total Avg</th>
                            <th><?php echo round($cumulative_before / $cumulative_count, 2); ?></th>
                            <th><?php echo round($cumulative_after / $cumulative_count, 2); ?></th>
                            <th><?php echo round($cumulative_percentage / $cumulative_count, 2); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-6">
                <div id="cropsYield" style="width: 100%; height: 300px;"></div>
                <script>
                    Highcharts.chart('cropsYield', {
                        chart: {
                            type: 'bar'
                        },
                        title: {
                            text: 'Average Change in Crop Area'
                        },
                        xAxis: {
                            categories: ["Before", "After", "Increase"]
                        },
                        yAxis: {
                            title: {
                                text: 'AVG Change In Area Avg - %'
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
                                data: [<?php echo round($cumulative_before / $cumulative_count, 2) ?>,
                                    <?php echo round($cumulative_after / $cumulative_count, 2); ?>,
                                    <?php echo round($cumulative_percentage / $cumulative_count, 2); ?>
                                ]
                            }

                        ]
                    });
                </script>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="CcropYieldChart" style="width: 100%; height: 300px;"></div>
        <script>
            Highcharts.chart('CcropYieldChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Crops Wise AVG Change In Area Comparison'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($chart_data, 'name')); ?>
                },
                yAxis: {
                    title: {
                        text: 'AVG Change In Area Avg - %'
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
                        data: <?php echo json_encode(array_column($chart_data, 'before'), JSON_NUMERIC_CHECK); ?>
                    }, {
                        name: 'After',
                        data: <?php echo json_encode(array_column($chart_data, 'after'), JSON_NUMERIC_CHECK); ?>
                    }, {
                        name: 'Increase',
                        data: <?php echo json_encode(array_column($chart_data, 'per_increase'), JSON_NUMERIC_CHECK); ?>
                    }

                ]
            });
        </script>
    </div>
    <div class="col-md-12"></div>



</div>



<div class="row">
    <div class="col-md-6">
        <?php

        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components) * 3) + 4; ?>">Component Wise and Crop Wise Average Change in Area </th>
                </tr>
                <tr>
                    <th rowspan="2">Crops</th>
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
                <?php foreach ($crops as $crop) { ?>
                    <tr>
                        <th><?php echo ucfirst($crop) ?></th>
                        <?php foreach ($components as $component) {

                            $query = "SELECT ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                            $result = $this->db->query($query, [$component->component])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>


                        <?php } ?>

                        <?php
                        $query = "SELECT ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`";
                        $result = $this->db->query($query)->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>

        </table>


    </div>
    <div class="col-md-6">
        <div id="cropYieldChart" style="width: 100%; height: 300px;"></div>


        <?php

        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

        $data = [];

        foreach ($crops as $crop) {
            $cropData = [
                'name' => ucfirst($crop),
                'data' => []
            ];

            foreach ($components as $component) {
                $query = "SELECT 
                        COALESCE(ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2), 0) AS `before`, 
                        COALESCE(ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2), 0) AS `after`,
                        COALESCE(ROUND(((ROUND((AVG(" . $crop . "_cp_after)  / 2.714), 2) - 
                        ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2)) /
                        ROUND((AVG(" . $crop . "_cp_before)  / 2.714), 2)) * 100, 2), 0) AS per_increase
                      FROM `impact_surveys`  
                      WHERE component = ?";


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
                    text: 'Component Wise and Crop Wise Average Change in Area'
                },
                xAxis: {
                    categories: <?php echo json_encode(array_column($components, 'component')); ?>,
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'AVG Change In Area (%)'
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
    <?php
    $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
    $regions = $this->db->query($query)->result();

    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();

    //$crops = ["wheat", "maize", "sugarcane", "fodder", "vegetable", "fruit_orchard"];
    ?>

    <?php foreach ($crops as $crop) { ?>
        <div class="col-md-6">
            <table class="table table-bordered table_medium" id="<?php echo $crop . "_y_table"; ?>">
                <thead>
                    <tr>
                        <th colspan="<?php echo (count($components) * 3) + 4; ?>">Average Change In <?php echo ucwords(str_replace("_", " ", $crop)); ?> Area (Ha)</th>
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
                            <th><?php echo ucfirst($region->region); ?></th>
                            <?php foreach ($components as $component) {
                                $query = "SELECT 
                                    ROUND(AVG({$crop}_cp_before) / 2.714, 2) AS `before`, 
                                    ROUND(AVG({$crop}_cp_after) / 2.714, 2) AS `after`,
                                    ROUND(((ROUND(AVG({$crop}_cp_after) / 2.714, 2) - ROUND(AVG({$crop}_cp_before) / 2.714, 2)) / ROUND(AVG({$crop}_cp_before) / 2.714, 2)) * 100, 2) AS per_increase
                                    FROM `impact_surveys`  
                                    WHERE component = ? AND region = ?";

                                $result = $this->db->query($query, [$component->component, $region->region])->row();
                            ?>
                                <td><?php if ($result->before) {
                                        echo $result->before;
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td>
                                    <?php if ($result->after) {
                                        echo $result->after;
                                    } else {
                                        echo '-';
                                    } ?></td>
                                <td>
                                    <?php if ($result->per_increase) {
                                        echo $result->per_increase;
                                    } else {
                                        echo '-';
                                    } ?></td>
                            <?php } ?>

                            <?php
                            $query = "SELECT 
                                ROUND(AVG({$crop}_cp_before) / 2.714, 2) AS `before`, 
                                ROUND(AVG({$crop}_cp_after) / 2.714, 2) AS `after`,
                                ROUND(((ROUND(AVG({$crop}_cp_after) / 2.714, 2) - ROUND(AVG({$crop}_cp_before) / 2.714, 2)) / ROUND(AVG({$crop}_cp_before) / 2.714, 2)) * 100, 2) AS per_increase
                                FROM `impact_surveys` WHERE region = ?";
                            $result = $this->db->query($query, [$region->region])->row();
                            ?>
                            <td><?php if ($result->before) {
                                    echo $result->before;
                                } else {
                                    echo '-';
                                } ?></td>
                            <td>
                                <?php if ($result->after) {
                                    echo $result->after;
                                } else {
                                    echo '-';
                                } ?></td>
                            <td>
                                <?php if ($result->per_increase) {
                                    echo $result->per_increase;
                                } else {
                                    echo '-';
                                } ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <div id="<?php echo $crop . "_y_chart"; ?>" style="width:100%;"></div>
            <script>
                Highcharts.chart('<?php echo $crop . "_y_chart"; ?>', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Average Change In <?php echo ucwords(str_replace("_", " ", $crop)); ?> Area (Ha)'
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
                                format: '{point.y:.2f}'
                            }
                        }
                    },
                    series: [{
                            name: 'Before Avg(Ha)',
                            data: [
                                <?php foreach ($components as $component) {
                                    $query = "SELECT ROUND(AVG({$crop}_cp_before) / 2.714, 2) AS `before` 
                                              FROM `impact_surveys` WHERE component = ?";
                                    $result = $this->db->query($query, [$component->component])->row();
                                    echo $result->before . ",";
                                }
                                ?>
                            ]
                        },
                        {
                            name: 'After Avg(Ha)',
                            data: [
                                <?php foreach ($components as $component) {
                                    $query = "SELECT ROUND(AVG({$crop}_cp_after) / 2.714, 2) AS `after` 
                                              FROM `impact_surveys` WHERE component = ?";
                                    $result = $this->db->query($query, [$component->component])->row();
                                    echo $result->after . ",";
                                }
                                ?>
                            ]
                        },
                        {
                            name: 'Increase Avg(%)',
                            data: [
                                <?php foreach ($components as $component) {
                                    $query = "SELECT ROUND(((ROUND(AVG({$crop}_cp_after) / 2.714, 2) - ROUND(AVG({$crop}_cp_before) / 2.714, 2)) / ROUND(AVG({$crop}_cp_before) / 2.714, 2)) * 100, 2) AS per_increase
                                              FROM `impact_surveys` WHERE component = ?";
                                    $result = $this->db->query($query, [$component->component])->row();
                                    echo $result->per_increase . ",";
                                }
                                ?>
                            ]
                        }
                    ]
                });
            </script>
        </div>
    <?php } ?>
</div>