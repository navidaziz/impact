<h4>Average Increase in Irrigated Cultural Command Area</h4>
<hr />

<?php
$g_component_title = 'Average Increase in Irrigated CCA by Components';
$g_sub_component_title = 'Average Increase in Irrigated CCA by Sub-Components';
$g_category_title = 'Average Increase in Irrigated CCA by Categories';

$component_title = 'Average Increase in Irrigated CCA by Region and Components Wise';
$sub_component_title = 'Average Increase in Irrigated CCA by Region and Sub-Components Wise';
$category_title = 'Average Increase in Irrigated CCA by Region and Categories Wise';

$query = "SELECT `region` FROM `impact_surveys` 
GROUP BY `region` ASC;";
$regions = $this->db->query($query)->result();
$query = "SELECT `component` FROM `impact_surveys` 
GROUP BY `component` ORDER BY `component` ASC";
$components = $this->db->query($query)->result();
?>
<div class="row">
    <div class="col-md-4">
        <?php


        // Prepare data for chart
        $chartData = [];
        $categories = [];
        $beforeData = [];
        $afterData = [];
        $increaseData = [];
        $perIncreaseData = [];

        $befor_weight = $after_weight = $increase_weight = $total = $per_increase_weight = 0;
        ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Average Increase in Irrigated CCA </strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" style="font-size: 14px;">
                        <thead class="thead-light">
                            <tr>
                                <th>Regions</th>
                                <!-- <th class="text-center"><small>Total</small></th> -->
                                <th class="text-center">Before <small>(Ha)</small></th>
                                <th class="text-center">After <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(%)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($regions as $region) {
                                $query = "SELECT COUNT(*) as total,
                                ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`,
                                ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                                ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) -
                                ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                                FROM `impact_surveys`
                                WHERE region = ? ";
                                $result = $this->db->query($query, [$region->region])->row();

                                $increase = ($result->after - $result->before);
                                $befor_weight += $result->before * $result->total;
                                $after_weight += $result->after * $result->total;
                                $increase_weight += $increase * $result->total;
                                $total += $result->total;
                                $per_increase_weight += $result->per_increase * $result->total;

                                // Prepare chart data
                                $categories[] = ucfirst($region->region);
                                $beforeData[] = (float)$result->before;
                                $afterData[] = (float)$result->after;
                                $increaseData[] = (float)$increase;
                                $perIncreaseData[] = (float)$result->per_increase;
                            ?>
                                <tr>
                                    <td><?php echo ucfirst($region->region) ?></td>
                                    <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
                                    <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($increase, 2); ?></td>
                                    <th class="text-center"><?php echo number_format($result->per_increase, 2); ?>%</th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">
                            <tr>
                                <th><small>Weighted Average</small></th>
                                <!-- <td class="text-center"><small><?php echo $total; ?></small></td> -->
                                <td class="text-center"><?php echo number_format(round($befor_weight / $total, 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($after_weight / $total, 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($increase_weight / $total, 2), 2); ?></td>
                                <td class="text-center"><?php echo number_format(round($per_increase_weight / $total, 2), 2); ?>%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="areaChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="increaseChart" style="height: 350px;"></div>
            </div>
        </div>
    </div>
    <script>
        Highcharts.chart('areaChart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Irrigation Area Before and After'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                crosshair: true
            },
            yAxis: {
                title: {
                    text: 'Area (Hectares)'
                }
            },
            tooltip: {
                shared: true,
                valueSuffix: ' Ha'
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Before',
                data: <?php echo json_encode($beforeData); ?>,
                color: '#ff7b7b'
            }, {
                name: 'After',
                data: <?php echo json_encode($afterData); ?>,
                color: '#7bff7b'
            }, {
                name: 'Increase',
                data: <?php echo json_encode($increaseData); ?>,
                color: '#7b7bff',
                type: 'line',
                marker: {
                    symbol: 'diamond'
                }
            }]
        });

        // Percentage Increase Chart
        Highcharts.chart('increaseChart', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Percentage Increase by Region'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                title: {
                    text: null
                }
            },
            yAxis: {
                title: {
                    text: 'Percentage Increase',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: '%'
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true,
                        format: '{y}%'
                    }
                }
            },
            series: [{
                name: 'Increase %',
                data: <?php echo json_encode($perIncreaseData); ?>,
                color: '#ffc107'
            }]
        });
    </script>
</div>
<div class="row">

    <div class="col-md-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><?php echo $component_title; ?></h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2" class="align-middle">Regions</th>
                                <?php foreach ($components as $component) { ?>
                                    <th colspan="5" class="text-center"><?php echo $component->component; ?></th>
                                <?php } ?>
                                <th colspan="5" class="text-center">Overall Average</th>
                            </tr>
                            <tr>
                                <?php foreach ($components as $component) { ?>
                                    <th class="text-center"><small>Total</small></th>
                                    <th class="text-center">Before <small>(Ha)</small></th>
                                    <th class="text-center">After <small>(Ha)</small></th>
                                    <th class="text-center">Increase <small>(Ha)</small></th>
                                    <th class="text-center">Increase <small>(%)</small></th>
                                <?php } ?>
                                <th class="text-center"><small>Total</small></th>
                                <th class="text-center">Before <small>(Ha)</small></th>
                                <th class="text-center">After <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(%)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $before = 0;
                            $aftere = 0;
                            $increase = 0;
                            $per_increase = 0;
                            foreach ($components as $component) {
                                $component->total = 0;
                                $component->before = 0;
                                $component->after = 0;
                                $component->increase = 0;
                                $component->per_increase = 0;
                            }
                            foreach ($regions as $region) {  ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    foreach ($components as $component) {
                                        $query = "SELECT 
                                        COUNT(*) as total,
                                        ROUND(AVG(irrigated_area_before / 2.471), 2) AS `before`,
                                        ROUND(AVG(irrigated_area_after / 2.471), 2) AS `after`,
                                        ROUND(AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471), 2) AS `increase`,
                                        ROUND(
                                            (AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471)) / 
                                            AVG(irrigated_area_before / 2.471) * 100, 
                                        2) AS `per_increase`
                                        FROM `impact_surveys`  
                                        WHERE component = ? AND region = ?";
                                        $result = $this->db->query($query, [$component->component, $region->region])->row();
                                        // Accumulate region totals
                                        $component->total += $result->total;
                                        $component->before += $result->before * $result->total;
                                        $component->after += $result->after * $result->total;
                                        $component->increase += $result->increase * $result->total;
                                        $component->per_increase += $result->per_increase * $result->total;
                                    ?>
                                        <td class="text-center"><small><?php echo $result->total; ?></small></td>
                                        <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                                    <?php } ?>

                                    <?php
                                    // Get overall averages for the region
                                    $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(irrigated_area_before / 2.471), 2) AS `before`,
                                    ROUND(AVG(irrigated_area_after / 2.471), 2) AS `after`,
                                    ROUND(AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471), 2) AS `increase`,
                                    ROUND(
                                        (AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471)) / 
                                        AVG(irrigated_area_before / 2.471) * 100, 
                                    2) AS `per_increase`
                                    FROM `impact_surveys`  
                                    WHERE region = ?";
                                    $result = $this->db->query($query, [$region->region])->row();

                                    $total += $result->total;
                                    $before += $result->before * $result->total;
                                    $after += $result->after * $result->total;
                                    $increase += $result->increase * $result->total;
                                    $per_increase += $result->per_increase * $result->total;
                                    ?>
                                    <td class="text-center"><small><?php echo $result->total; ?></small></td>
                                    <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">
                            <tr>
                                <th>Average</th>
                                <?php
                                foreach ($components as $component) {
                                    $query = "SELECT 
                                COUNT(*) as total,
                                ROUND(AVG(irrigated_area_before / 2.471), 2) AS `before`,
                                ROUND(AVG(irrigated_area_after / 2.471), 2) AS `after`,
                                ROUND(AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471), 2) AS `increase`,
                                ROUND(
                                    (AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471)) / 
                                    AVG(irrigated_area_before / 2.471) * 100, 
                                2) AS `per_increase`
                                FROM `impact_surveys`  
                                WHERE component = ?";
                                    $result = $this->db->query($query, [$component->component])->row();
                                ?>
                                    <td class="text-center"><small><?php echo $result->total; ?></small></td>
                                    <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                                <?php } ?>

                                <?php
                                $query = "SELECT 
                            COUNT(*) as total,
                            ROUND(AVG(irrigated_area_before / 2.471), 2) AS `before`,
                            ROUND(AVG(irrigated_area_after / 2.471), 2) AS `after`,
                            ROUND(AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471), 2) AS `increase`,
                            ROUND(
                                (AVG(irrigated_area_after / 2.471) - AVG(irrigated_area_before / 2.471)) / 
                                AVG(irrigated_area_before / 2.471) * 100, 
                            2) AS `per_increase`
                            FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();
                                ?>
                                <td class="text-center"><small><?php echo $result->total; ?></small></td>
                                <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>

                                <?php foreach ($components as $component) { ?>
                                    <td class="text-center"><small><?php echo $component->total; ?></small></td>
                                    <td class="text-center"><?php echo round($component->before / $component->total, 2); ?></td>
                                    <td class="text-center"><?php echo round($component->after / $component->total, 2); ?></td>
                                    <td class="text-center"><?php echo round($component->increase / $component->total, 2); ?></td>
                                    <td class="text-center"><?php echo round($component->per_increase / $component->total, 2); ?></td>
                                <?php } ?>
                                <td class="text-center"><small><?php echo $total; ?></small></td>
                                <td class="text-center"><?php echo round($before / $total, 2); ?></td>
                                <td class="text-center"><?php echo round($after / $total, 2); ?></td>
                                <td class="text-center"><?php echo round($increase / $total, 2); ?></td>
                                <td class="text-center"><?php echo round($per_increase / $total, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6">
        <table class="table table-bordered table_small">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components) * 3) + 7; ?>"><?php echo $component_title; ?></th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="4"><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th colspan="4">AVG</th>
                </tr>
                <tr>
                    <?php foreach ($components as $component) { ?>
                        <th><small>Total</small></th>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th><small>Total</small></th>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                $before = 0;
                $aftere = 0;
                $increase = 0;
                $per_increase = 0;
                foreach ($components as $component) {
                    $component->total = 0;
                    $component->before = 0;
                    $component->after = 0;
                    $component->increase = 0;
                    $component->per_increase = 0;
                }
                foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($components as $component) {
                            $query = "SELECT COUNT(*) as total, 
                            ROUND(SUM(irrigated_area_before / 2.471) / COUNT(*), 2) AS `before`,
                            ROUND(SUM(irrigated_area_after / 2.471) / COUNT(*), 2) AS `after`,
                            ROUND(
                            ( (SUM(irrigated_area_after / 2.471) / COUNT(*)) - 
                            (SUM(irrigated_area_before / 2.471) / COUNT(*)) 
                            ) / (SUM(irrigated_area_before / 2.471) / COUNT(*)) * 100
                            , 2) AS `per_increase`
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ?;";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                            $component->weight = $result->total;
                            $component->before = $result->before;
                            $component->after = $result->after;
                            $component->befor_weight += $result->before * $result->total;
                            $component->after_weight += $result->after * $result->total;
                            $component->total += $result->total;
                            $component->per_increase_weight += $result->per_increase * $result->total;
                        ?>
                            <td><small><?php echo $result->total; ?></small></td>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT COUNT(*) as total,
                            ROUND(SUM(irrigated_area_before / 2.471) / COUNT(*), 2) AS `before`,
                            ROUND(SUM(irrigated_area_after / 2.471) / COUNT(*), 2) AS `after`,
                            ROUND(
                            ( (SUM(irrigated_area_after / 2.471) / COUNT(*)) - 
                            (SUM(irrigated_area_before / 2.471) / COUNT(*)) 
                            ) / (SUM(irrigated_area_before / 2.471) / COUNT(*)) * 100
                            , 2) AS `per_increase`
                            FROM `impact_surveys`  
                            WHERE region = ?";
                        $result = $this->db->query($query, [$region->region])->row();
                        $befor_weight += $result->before * $result->total;
                        $after_weight += $result->after * $result->total;
                        $total += $result->total;
                        $per_increase_weight += $result->per_increase * $result->total;
                        ?>
                        <td><small><?php echo $result->total; ?></small></td>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Average</th>
                    <?php foreach ($components as $component) {
                        $query = "SELECT  COUNT(*) as total,
                            ROUND(SUM(irrigated_area_before / 2.471) / COUNT(*), 2) AS `before`,
                            ROUND(SUM(irrigated_area_after / 2.471) / COUNT(*), 2) AS `after`,
                            ROUND(
                            ( (SUM(irrigated_area_after / 2.471) / COUNT(*)) - 
                            (SUM(irrigated_area_before / 2.471) / COUNT(*)) 
                            ) / (SUM(irrigated_area_before / 2.471) / COUNT(*)) * 100
                            , 2) AS `per_increase`
                            FROM `impact_surveys`  
                            WHERE component = ? ;";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><small><?php echo $result->total; ?></small></td>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>

                    <?php $query = "SELECT COUNT(*) as total,
                            ROUND(SUM(irrigated_area_before / 2.471) / COUNT(*), 2) AS `before`,
                            ROUND(SUM(irrigated_area_after / 2.471) / COUNT(*), 2) AS `after`,
                            ROUND(
                            ( (SUM(irrigated_area_after / 2.471) / COUNT(*)) - 
                            (SUM(irrigated_area_before / 2.471) / COUNT(*)) 
                            ) / (SUM(irrigated_area_before / 2.471) / COUNT(*)) * 100
                            , 2) AS `per_increase`
                            FROM `impact_surveys`";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><small><?php echo $result->total; ?></small></td>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
                <tr>
                    <th>Weighted Average</th>
                    <?php foreach ($components as $component) { ?>
                        <td><small><?php echo $component->total; ?></small></td>
                        <td><?php echo round($component->befor_weight / $component->total, 2); ?></td>
                        <td><?php echo round($component->after_weight / $component->total, 2); ?></td>
                        <td><?php echo round($component->per_increase_weight / $component->total, 2); ?></td>
                    <?php } ?>
                    <td><small><?php echo $total; ?></small></td>
                    <td><?php echo round($befor_weight / $total, 2); ?></td>
                    <td><?php echo round($after_weight / $total, 2); ?></td>
                    <td><?php echo round($per_increase_weight / $total, 2); ?></td>

                </tr>
            </tfoot>

        </table>

        <div id="irr_components" style="width:100%; height:500px;"></div>
        <script>
            Highcharts.chart('irr_components', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo $g_component_title; ?>'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($components as $component) {
                            echo "'" . $component->component . "',";
                        } ?>
                    ],
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG -  %'
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
                        name: 'Before (Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'After (Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'AVG (%)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
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

    <div class="col-md-12">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $sub_components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_small">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($sub_components) * 3) + 4; ?>"><?php echo $sub_component_title; ?></th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($sub_components as $sub_component) { ?>
                        <th colspan="3"><?php echo $sub_component->sub_component; ?></th>
                    <?php } ?>
                    <th colspan="3">AVG</th>
                </tr>
                <tr>

                    <?php foreach ($sub_components as $sub_component) { ?>
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
                        <?php foreach ($sub_components as $sub_component) {
                            $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE sub_component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$sub_component->sub_component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
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
                    <?php foreach ($sub_components as $sub_component) {
                        $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE sub_component = ? ";
                        $result = $this->db->query($query, [$sub_component->sub_component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>

        <div id="irr_sub_components" style="width:100%; height:500px;"></div>
        <script>
            Highcharts.chart('irr_sub_components', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo $g_sub_component_title; ?>'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($sub_components as $sub_component) {
                            echo "'" . $sub_component->sub_component . "',";
                        } ?>
                    ],
                    title: {
                        text: 'Sub Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG -  %'
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
                        name: 'Before (Ha)',
                        data: [
                            <?php
                            foreach ($sub_components as $sub_component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before` 
                                  FROM `impact_surveys` WHERE sub_component = ? ";
                                $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                echo $result->before . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'After (Ha)',
                        data: [
                            <?php
                            foreach ($sub_components as $sub_component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after` 
                                  FROM `impact_surveys` WHERE sub_component = ? ";
                                $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                echo $result->after . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'AVG (%)',
                        data: [
                            <?php
                            foreach ($sub_components as $sub_component) {
                                $query = "SELECT ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE sub_component = ? ";
                                $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                echo $result->per_increase . ",";
                            }
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>

    <div class="col-md-12">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $categories = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_small">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($categories) * 3) + 4; ?>"><?php echo $sub_component_title; ?></th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($categories as $category) { ?>
                        <th colspan="3"><?php echo $category->category; ?></th>
                    <?php } ?>
                    <th colspan="3">AVG</th>
                </tr>
                <tr>

                    <?php foreach ($categories as $category) { ?>
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
                        <?php foreach ($categories as $category) {
                            $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE category = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$category->category, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
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
                    <?php foreach ($categories as $category) {
                        $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE category = ? ";
                        $result = $this->db->query($query, [$category->category])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>

        <div id="irr_categories" style="width:100%; height:500px;"></div>
        <script>
            Highcharts.chart('irr_categories', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo $g_category_title; ?>'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($categories as $category) {
                            echo "'" . $category->category . "',";
                        } ?>
                    ],
                    title: {
                        text: 'Sub Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG -  %'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ''
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
                        name: 'Before (Ha)',
                        data: [
                            <?php
                            foreach ($categories as $category) {
                                $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.471, 2) AS `before` 
                                  FROM `impact_surveys` WHERE category = ? ";
                                $result = $this->db->query($query, [$category->category])->row();
                                echo $result->before . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'After (Ha)',
                        data: [
                            <?php
                            foreach ($categories as $category) {
                                $query = "SELECT ROUND(AVG(irrigated_area_after) / 2.471, 2) AS `after` 
                                  FROM `impact_surveys` WHERE category = ? ";
                                $result = $this->db->query($query, [$category->category])->row();
                                echo $result->after . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'AVG (%)',
                        data: [
                            <?php
                            foreach ($categories as $category) {
                                $query = "SELECT ROUND(((ROUND(AVG(irrigated_area_after) / 2.471, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.471, 2))/ROUND(AVG(irrigated_area_before) / 2.471, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE category = ? ";
                                $result = $this->db->query($query, [$category->category])->row();
                                echo $result->per_increase . ",";
                            }
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>

</div>