<div class="row">
    <div class="col-md-6">
        <h4>Average Increase in Irrigated Cultural Command Area</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Irrigated_CCA'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'table_3', 'table_4'], ['Sheet1', 'Sheet2', 'Sheet3', 'Sheet4'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>


    </div>
</div>


<hr />

<?php
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
                    <table class="table table-bordered table-hover mb-0" id="table_1" style="font-size: 12px;">
                        <thead class="thead-light">
                            <tr>
                                <th style="display: none; text-align:center" colspan="5">Average Increase in Irrigated CCA</th>
                            </tr>
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
                                $beforeData[] = (float) $result->before;
                                $afterData[] = (float)$result->after;
                                $increaseData[] = (float) round($increase, 2);
                                $perIncreaseData[] = (float) $result->per_increase;
                            ?>
                                <tr>
                                    <td><?php echo ucfirst($region->region) ?></td>
                                    <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
                                    <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($increase, 2); ?></td>
                                    <th class="text-center"><?php echo number_format($result->per_increase, 2); ?></th>
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
                                <td class="text-center"><?php echo number_format(round($per_increase_weight / $total, 2), 2); ?></td>
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
                <div id="areaChart" style="height: 260px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="increaseChart" style="height: 260px;"></div>
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
                    borderWidth: 0,
                    dataLabels: {
                        enabled: true,
                        format: '{y}',
                        style: {
                            fontSize: '9px' // Change to your desired font size
                        }

                    }
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
    <div class="col-md-12>
        <div class=" card shadow-sm mb-4" style="margin-top: 8px;">
        <div class="card-header bg-primary text-white">
            <strong>Average Increase in Irrigated CCA by Region and Component Wise</strong>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="table_2" class="table table-bordered table-hover table-sm">
                    <thead class="thead-light">
                        <tr>
                            <th style=" display:none; text-align:center" colspan="<?php //echo (1 + (count($component) * 4) + 4) 
                                                                                    ?>">Average Increase in Irrigated CCA by Region and Component Wise</th>
                        </tr>
                        <tr>
                            <th rowspan="2" class="align-middle">Regions</th>
                            <?php foreach ($components as $component) { ?>
                                <th colspan="4" class="text-center">Component <?php echo $component->component; ?></th>
                            <?php } ?>
                            <th colspan="4" class="text-center">Overall Average</th>
                        </tr>
                        <tr>
                            <?php foreach ($components as $component) { ?>
                                <!-- <th class="text-center"><small>Total</small></th> -->
                                <th class="text-center">Before <small>(Ha)</small></th>
                                <th class="text-center">After <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(Ha)</small></th>
                                <th class="text-center">Increase <small>(%)</small></th>
                            <?php } ?>
                            <!-- <th class="text-center"><small>Total</small></th> -->
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
                        $chart_data = [];
                        foreach ($regions as $region) {  ?>
                            <tr>
                                <th><?php echo ucfirst($region->region) ?></th>
                                <?php













                                foreach ($components as $component) {
                                    // $chart_data[$component->component] = [
                                    //     'regions' => [],
                                    //     'before' => [],
                                    //     'after' => [],
                                    //     'increase' => [],
                                    //     'per_increase' => [],
                                    // ];
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
                                    // Prepare chart data
                                    $chart_data[$component->component]['regions'][] = ucfirst($region->region);
                                    $chart_data[$component->component]['before'][] = $result->before ?? 0;
                                    $chart_data[$component->component]['after'][] = $result->after ?? 0;
                                    $chart_data[$component->component]['increase'][] = $result->increase ?? 0;
                                    $chart_data[$component->component]['per_increase'][] = $result->per_increase ?? 0;

                                ?>
                                    <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
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
                                $chart_data['Over All']['regions'][] = ucfirst($region->region);
                                $chart_data['Over All']['before'][] = $result->before ?? 0;
                                $chart_data['Over All']['after'][] = $result->after ?? 0;
                                $chart_data['Over All']['increase'][] = $result->increase ?? 0;
                                $chart_data['Over All']['per_increase'][] = $result->per_increase ?? 0;
                                ?>
                                <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
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
                                <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
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
                            <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
                            <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                            <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                            <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                            <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Weighted Average</th>

                            <?php foreach ($components as $component) { ?>
                                <!-- <td class="text-center"><small><?php echo $component->total; ?></small></td> -->
                                <td class="text-center"><?php echo round($component->before / $component->total, 2); ?></td>
                                <td class="text-center"><?php echo round($component->after / $component->total, 2); ?></td>
                                <td class="text-center"><?php echo round($component->increase / $component->total, 2); ?></td>
                                <td class="text-center"><?php echo round($component->per_increase / $component->total, 2); ?></td>
                            <?php } ?>
                            <!-- <td class="text-center"><small><?php echo $total; ?></small></td> -->
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
    <?php


    function toFloatArray($array)
    {
        return array_map('floatval', $array);
    }
    ?>
    <?php foreach ($chart_data as $component_name => $data): ?>
        <div class="col-md-4">
            <div id="chart_<?php echo $component_name; ?>" style="width:100%; height:400px; margin-bottom: 50px;"></div>
        </div>
    <?php endforeach; ?>
    <script>
        <?php foreach ($chart_data as $component_name => $data): ?>
            Highcharts.chart('chart_<?php echo $component_name; ?>', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Component <?php echo $component_name; ?> - Region-wise CCA Impact'
                },
                xAxis: {
                    categories: <?php echo json_encode($data['regions']); ?>,
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Hectares / %'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ''
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{y}',
                            style: {
                                fontSize: '9px' // Change to your desired font size
                            }

                        }
                    }
                },
                series: [{
                        name: 'Before (Ha)',
                        data: <?php echo json_encode(toFloatArray($data['before'])); ?>
                    },
                    {
                        name: 'After (Ha)',
                        data: <?php echo json_encode(toFloatArray($data['after'])); ?>
                    },
                    {
                        name: 'Increase (Ha)',
                        data: <?php echo json_encode(toFloatArray($data['increase'])); ?>
                    },
                    {
                        name: 'Increase (%)',
                        data: <?php echo json_encode(toFloatArray($data['per_increase'])); ?>
                        // You can optionally use type: 'line' if you want it overlaid
                    }
                ]
            });
        <?php endforeach; ?>
    </script>

</div>


</div>


<div class="row">
    <?php $query = "SELECT `sub_component` FROM `impact_surveys`
    GROUP BY `sub_component` ORDER BY `sub_component` ASC";
    $sub_components = $this->db->query($query)->result();
    ?>
    <div class="col-md-12">
        <div class="card shadow-sm mb-4" style="margin-top: 8px;">
            <div class="card-header bg-primary text-white">
                <strong>Average Increase in Irrigated CCA by Region and Sub Component Wise</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="table_3" class="table table-bordered table-hover table_small">
                        <thead class="thead-light">
                            <tr>
                                <th style=" display:none; text-align:center" colspan="<?php //echo (1 + (count($categorys) * 4)) 
                                                                                        ?>">Average Increase in Irrigated CCA by Region and Sub Component Wise</th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="align-middle">Regions</th>
                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th colspan="4" class="text-center">Sub Component <?php echo $sub_component->sub_component; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <!-- <th class="text-center"><small>Total</small></th> -->
                                    <th class="text-center">Before <small>(Ha)</small></th>
                                    <th class="text-center">After <small>(Ha)</small></th>
                                    <th class="text-center">Increase <small>(Ha)</small></th>
                                    <th class="text-center">Increase <small>(%)</small></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $before = 0;
                            $aftere = 0;
                            $increase = 0;
                            $per_increase = 0;
                            foreach ($sub_components as $sub_component) {
                                $sub_component->total = 0;
                                $sub_component->before = 0;
                                $sub_component->after = 0;
                                $sub_component->increase = 0;
                                $sub_component->per_increase = 0;
                            }
                            foreach ($regions as $region) {  ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    foreach ($sub_components as $sub_component) {
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
                                        WHERE sub_component = ? AND region = ?";
                                        $result = $this->db->query($query, [$sub_component->sub_component, $region->region])->row();
                                        // Accumulate region totals
                                        $sub_component->total += $result->total;
                                        $sub_component->before += $result->before * $result->total;
                                        $sub_component->after += $result->after * $result->total;
                                        $sub_component->increase += $result->increase * $result->total;
                                        $sub_component->per_increase += $result->per_increase * $result->total;
                                    ?>
                                        <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
                                        <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                                    <?php } ?>

                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">
                            <tr>
                                <th>Average</th>
                                <?php
                                foreach ($sub_components as $sub_component) {
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
                                WHERE sub_component = ?";
                                    $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                ?>
                                    <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
                                    <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                                <?php } ?>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>

                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <td class="text-center">
                                        <?php echo $sub_component->total != 0 ? round($sub_component->before / $sub_component->total, 2) : '0.00'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $sub_component->total != 0 ? round($sub_component->after / $sub_component->total, 2) : '0.00'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $sub_component->total != 0 ? round($sub_component->increase / $sub_component->total, 2) : '0.00'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $sub_component->total != 0 ? round($sub_component->per_increase / $sub_component->total, 2) : '0.00'; ?>
                                    </td>
                                <?php } ?>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <?php
    $query = "SELECT `category` FROM `impact_surveys`
    GROUP BY `category` ORDER BY `category` ASC";
    $categorys = $this->db->query($query)->result();
    ?>
    <div class="col-md-12">
        <div class="card shadow-sm mb-4" style="margin-top: 8px;">
            <div class="card-header bg-primary text-white">
                <strong>Average Increase in Irrigated CCA by Region and Categories Wise</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="table_4" class="table table-bordered table-hover table_small">
                        <thead class="thead-light">
                            <tr>
                                <th style=" display:none; text-align:center" colspan="<?php //echo (1 + (count($categorys) * 4)) 
                                                                                        ?>">Average Increase in Irrigated CCA by Region and Categories Wise</th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="align-middle">Regions</th>
                                <?php foreach ($categorys as $category) { ?>
                                    <th colspan="4" class="text-center">Categories <?php echo $category->category; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($categorys as $category) { ?>
                                    <!-- <th class="text-center"><small>Total</small></th> -->
                                    <th class="text-center">Before <small>(Ha)</small></th>
                                    <th class="text-center">After <small>(Ha)</small></th>
                                    <th class="text-center">Increase <small>(Ha)</small></th>
                                    <th class="text-center">Increase <small>(%)</small></th>
                                <?php } ?>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($categorys as $category) {
                                $category->total = 0;
                                $category->before = 0;
                                $category->after = 0;
                                $category->increase = 0;
                                $category->per_increase = 0;
                            }
                            foreach ($regions as $region) {  ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    foreach ($categorys as $category) {
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
                                        WHERE category = ? AND region = ?";
                                        $result = $this->db->query($query, [$category->category, $region->region])->row();
                                        // Accumulate region totals
                                        $category->total += $result->total;
                                        $category->before += $result->before * $result->total;
                                        $category->after += $result->after * $result->total;
                                        $category->increase += $result->increase * $result->total;
                                        $category->per_increase += $result->per_increase * $result->total;
                                    ?>
                                        <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
                                        <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                        <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">
                            <tr>
                                <th>Average</th>
                                <?php
                                foreach ($categorys as $category) {
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
                                WHERE category = ?";
                                    $result = $this->db->query($query, [$category->category])->row();
                                ?>
                                    <!-- <td class="text-center"><small><?php echo $result->total; ?></small></td> -->
                                    <td class="text-center"><?php echo number_format($result->before, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->after, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->increase, 2); ?></td>
                                    <td class="text-center"><?php echo number_format($result->per_increase, 2); ?></td>
                                <?php } ?>

                            </tr>
                            <tr>
                                <th>Weighted Average</th>

                                <?php foreach ($categorys as $category) { ?>
                                    <td class="text-center">
                                        <?php echo $category->total != 0 ? round($category->before / $category->total, 2) : '0.00'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $category->total != 0 ? round($category->after / $category->total, 2) : '0.00'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $category->total != 0 ? round($category->increase / $category->total, 2) : '0.00'; ?>
                                    </td>
                                    <td class="text-center">
                                        <?php echo $category->total != 0 ? round($category->per_increase / $category->total, 2) : '0.00'; ?>
                                    </td>
                                <?php } ?>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>