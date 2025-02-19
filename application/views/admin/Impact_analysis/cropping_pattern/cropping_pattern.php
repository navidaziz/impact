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
                    <th colspan="<?php echo (count($components) * 3) + 4; ?>">Increase in Crops Yield (ton/ha)</th>
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
                $cropData['data'][] = $result->per_increase ?? 0; // Ensure no NULL values
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
                            $query = "SELECT ROUND((AVG(wheat_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(wheat_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(wheat_cp_before)  / 2.714), 2))/ROUND((AVG(wheat_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND((AVG(wheat_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(wheat_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(wheat_cp_before)  / 2.714), 2))/ROUND((AVG(wheat_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                        $query = "SELECT ROUND((AVG(wheat_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(wheat_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(wheat_cp_before)  / 2.714), 2))/ROUND((AVG(wheat_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND((AVG(wheat_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(wheat_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(wheat_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(wheat_cp_before)  / 2.714), 2))/ROUND((AVG(wheat_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                                $query = "SELECT ROUND((AVG(wheat_cp_before)  / 2.714), 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            $query = "SELECT ROUND((AVG(wheat_cp_before)  / 2.714), 2) AS `before` 
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
                                $query = "SELECT ROUND((AVG(wheat_cp_after)  / 2.714), 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            $query = "SELECT ROUND((AVG(wheat_cp_after)  / 2.714), 2) AS `after` 
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
                                $query = "SELECT ROUND(((ROUND((AVG(wheat_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(wheat_cp_before)  / 2.714), 2))/ROUND((AVG(wheat_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->per_increase . ",";
                            }
                            $query = "SELECT ROUND(((ROUND((AVG(wheat_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(wheat_cp_before)  / 2.714), 2))/ROUND((AVG(wheat_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                            $query = "SELECT ROUND((AVG(maize_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(maize_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(maize_cp_before)  / 2.714), 2))/ROUND((AVG(maize_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND((AVG(maize_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(maize_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(maize_cp_before)  / 2.714), 2))/ROUND((AVG(maize_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                        $query = "SELECT ROUND((AVG(maize_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(maize_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(maize_cp_before)  / 2.714), 2))/ROUND((AVG(maize_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND((AVG(maize_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(maize_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(maize_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(maize_cp_before)  / 2.714), 2))/ROUND((AVG(maize_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                                $query = "SELECT ROUND((AVG(maize_cp_before)  / 2.714), 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            $query = "SELECT ROUND((AVG(maize_cp_before)  / 2.714), 2) AS `before` 
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
                                $query = "SELECT ROUND((AVG(maize_cp_after)  / 2.714), 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            $query = "SELECT ROUND((AVG(maize_cp_after)  / 2.714), 2) AS `after` 
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
                                $query = "SELECT ROUND(((ROUND((AVG(maize_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(maize_cp_before)  / 2.714), 2))/ROUND((AVG(maize_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->per_increase . ",";
                            }
                            $query = "SELECT ROUND(((ROUND((AVG(maize_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(maize_cp_before)  / 2.714), 2))/ROUND((AVG(maize_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                            $query = "SELECT ROUND((AVG(sugarcane_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(sugarcane_cp_before)  / 2.714), 2))/ROUND((AVG(sugarcane_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND((AVG(sugarcane_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(sugarcane_cp_before)  / 2.714), 2))/ROUND((AVG(sugarcane_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                        $query = "SELECT ROUND((AVG(sugarcane_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(sugarcane_cp_before)  / 2.714), 2))/ROUND((AVG(sugarcane_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND((AVG(sugarcane_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(sugarcane_cp_before)  / 2.714), 2))/ROUND((AVG(sugarcane_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                                $query = "SELECT ROUND((AVG(sugarcane_cp_before)  / 2.714), 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            $query = "SELECT ROUND((AVG(sugarcane_cp_before)  / 2.714), 2) AS `before` 
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
                                $query = "SELECT ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            $query = "SELECT ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) AS `after` 
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
                                $query = "SELECT ROUND(((ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(sugarcane_cp_before)  / 2.714), 2))/ROUND((AVG(sugarcane_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->per_increase . ",";
                            }
                            $query = "SELECT ROUND(((ROUND((AVG(sugarcane_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(sugarcane_cp_before)  / 2.714), 2))/ROUND((AVG(sugarcane_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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