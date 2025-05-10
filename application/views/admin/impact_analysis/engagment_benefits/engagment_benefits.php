<h4>Labor Impact Analysis: Skilled and Unskilled Employment Changes</h4>
<hr />


<div class="row">
    <div class="col-md-12">
        <?php
        // Fetch all data in single queries for efficiency
        $components_query = "SELECT `component`, ROUND(AVG(income_improved_per), 2) as avg_increase 
                           FROM `impact_surveys` 
                           GROUP BY `component` 
                           ORDER BY `component` ASC";
        $components_data = $this->db->query($components_query)->result();

        $sub_components_query = "SELECT `sub_component`, ROUND(AVG(income_improved_per), 2) as avg_increase 
                               FROM `impact_surveys` 
                               GROUP BY `sub_component` 
                               ORDER BY `sub_component` ASC";
        $sub_components_data = $this->db->query($sub_components_query)->result();

        $total_query = "SELECT ROUND(AVG(income_improved_per), 2) as total_avg 
                       FROM `impact_surveys`";
        $total_avg = $this->db->query($total_query)->row()->total_avg;

        // Get all regions
        $regions_query = "SELECT DISTINCT `region` FROM `impact_surveys` ORDER BY `region` ASC";
        $regions = $this->db->query($regions_query)->result();

        // Prepare data for charts
        $component_categories = array_column($components_data, 'component');
        $component_values = array_column($components_data, 'avg_increase');

        $sub_component_categories = array_column($sub_components_data, 'sub_component');
        $sub_component_values = array_column($sub_components_data, 'avg_increase');

        // Prepare data for region-component chart
        $region_component_data = [];
        $region_component_categories = [];

        foreach ($regions as $region) {
            $region_component_categories[] = $region->region;

            $query = "SELECT `component`, ROUND(AVG(income_improved_per), 2) as avg_increase
                      FROM `impact_surveys`
                      WHERE `region` = '" . $region->region . "'
                      GROUP BY `component`
                      ORDER BY `component` ASC";
            $result = $this->db->query($query)->result();

            foreach ($result as $row) {
                if (!isset($region_component_data[$row->component])) {
                    $region_component_data[$row->component] = [];
                }
                $region_component_data[$row->component][] = (float)$row->avg_increase;
            }
        }

        // Prepare data for region-subcomponent chart
        $region_subcomponent_data = [];
        $region_subcomponent_categories = [];

        foreach ($regions as $region) {
            $region_subcomponent_categories[] = $region->region;

            $query = "SELECT `sub_component`, ROUND(AVG(income_improved_per), 2) as avg_increase
                      FROM `impact_surveys`
                      WHERE `region` = '" . $region->region . "'
                      GROUP BY `sub_component`
                      ORDER BY `sub_component` ASC";
            $result = $this->db->query($query)->result();

            foreach ($result as $row) {
                if (!isset($region_subcomponent_data[$row->sub_component])) {
                    $region_subcomponent_data[$row->sub_component] = [];
                }
                $region_subcomponent_data[$row->sub_component][] = (float)$row->avg_increase;
            }
        }
        ?>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Metric</th>
                    <th colspan="<?php echo count($components_data); ?>">Components</th>
                    <th colspan="<?php echo count($sub_components_data); ?>">Sub-Components</th>
                    <th>Overall Average</th>
                </tr>
                <tr>
                    <th>Name</th>
                    <?php foreach ($components_data as $component): ?>
                        <th><?php echo $component->component; ?></th>
                    <?php endforeach; ?>
                    <?php foreach ($sub_components_data as $sub_component): ?>
                        <th><?php echo $sub_component->sub_component; ?></th>
                    <?php endforeach; ?>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region): ?>
                    <?php
                    // Calculate averages for this region
                    $region_components = [];
                    foreach ($components_data as $component) {
                        $query = "SELECT ROUND(AVG(income_improved_per), 2) as avg_increase
                                  FROM `impact_surveys`
                                  WHERE `region` = '" . $region->region . "'
                                  AND `component` = '" . $component->component . "'";
                        $result = $this->db->query($query)->row();
                        $region_components[] = $result ? $result->avg_increase : 0;
                    }

                    $region_subcomponents = [];
                    foreach ($sub_components_data as $sub_component) {
                        $query = "SELECT ROUND(AVG(income_improved_per), 2) as avg_increase
                                  FROM `impact_surveys`
                                  WHERE `region` = '" . $region->region . "'
                                  AND `sub_component` = '" . $sub_component->sub_component . "'";
                        $result = $this->db->query($query)->row();
                        $region_subcomponents[] = $result ? $result->avg_increase : 0;
                    }

                    // Calculate region total average
                    $region_total = array_merge($region_components, $region_subcomponents);
                    $region_avg = count($region_total) > 0 ? round(array_sum($region_total) / count($region_total), 2) : 0;
                    ?>
                    <tr>
                        <td><?php echo $region->region; ?></td>
                        <?php foreach ($region_components as $value): ?>
                            <td><?php echo $value; ?></td>
                        <?php endforeach; ?>
                        <?php foreach ($region_subcomponents as $value): ?>
                            <td><?php echo $value; ?></td>
                        <?php endforeach; ?>
                        <td><?php echo $region_avg; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td>National Average</td>
                    <?php foreach ($components_data as $component): ?>
                        <td><?php echo $component->avg_increase; ?></td>
                    <?php endforeach; ?>
                    <?php foreach ($sub_components_data as $sub_component): ?>
                        <td><?php echo $sub_component->avg_increase; ?></td>
                    <?php endforeach; ?>
                    <td><?php echo $total_avg; ?></td>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-4">
            <div class="col-md-6">
                <div id="component_chart" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
            </div>
            <div class="col-md-6">
                <div id="sub_component_chart" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div id="region_component_chart" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
            </div>
            <div class="col-md-6">
                <div id="region_subcomponent_chart" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                // Component Chart
                Highcharts.chart('component_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'National Income Improvement by Component'
                    },
                    xAxis: {
                        categories: <?php echo json_encode($component_categories); ?>,
                        title: {
                            text: 'Components'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Percentage Increase'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            colorByPoint: true
                        }
                    },
                    series: [{
                        name: 'Income Increase',
                        data: <?php echo json_encode($component_values, JSON_NUMERIC_CHECK); ?>
                    }]
                });

                // Sub-Component Chart
                Highcharts.chart('sub_component_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'National Income Improvement by Sub-Component'
                    },
                    xAxis: {
                        categories: <?php echo json_encode($sub_component_categories); ?>,
                        title: {
                            text: 'Sub-Components'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Percentage Increase'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            pointPadding: 0.2,
                            borderWidth: 0,
                            colorByPoint: true
                        }
                    },
                    series: [{
                        name: 'Income Increase',
                        data: <?php echo json_encode($sub_component_values, JSON_NUMERIC_CHECK); ?>
                    }]
                });

                // Region-Component Chart
                Highcharts.chart('region_component_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Income Improvement by Region (Component-wise)'
                    },
                    xAxis: {
                        categories: <?php echo json_encode($region_component_categories); ?>,
                        title: {
                            text: 'Regions'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Percentage Increase'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            //stacking: 'normal',
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [
                        <?php foreach ($region_component_data as $component => $values): ?> {
                                name: '<?php echo $component; ?>',
                                data: <?php echo json_encode($values, JSON_NUMERIC_CHECK); ?>
                            },
                        <?php endforeach; ?>
                    ]
                });

                // Region-Subcomponent Chart
                Highcharts.chart('region_subcomponent_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Income Improvement by Region (Sub-Component-wise)'
                    },
                    xAxis: {
                        categories: <?php echo json_encode($region_subcomponent_categories); ?>,
                        title: {
                            text: 'Regions'
                        }
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Percentage Increase'
                        }
                    },
                    tooltip: {
                        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                            '<td style="padding:0"><b>{point.y:.1f}%</b></td></tr>',
                        footerFormat: '</table>',
                        shared: true,
                        useHTML: true
                    },
                    plotOptions: {
                        column: {
                            //stacking: 'normal',
                            pointPadding: 0.2,
                            borderWidth: 0
                        }
                    },
                    series: [
                        <?php foreach ($region_subcomponent_data as $subcomponent => $values): ?> {
                                name: '<?php echo $subcomponent; ?>',
                                data: <?php echo json_encode($values, JSON_NUMERIC_CHECK); ?>
                            },
                        <?php endforeach; ?>
                    ]
                });
            });
        </script>
    </div>
</div>


<?php
// Fetch all data in single queries for efficiency
$components_query = "
    SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
$components_data = $this->db->query($components_query)->result();

$sub_components_query = "
    SELECT `sub_component` FROM `impact_surveys`  GROUP BY `sub_component`  ORDER BY `sub_component` ASC";
$sub_components_data = $this->db->query($sub_components_query)->result();

// Get all regions
$regions_query = "SELECT DISTINCT `region` FROM `impact_surveys` ORDER BY `region` ASC";
$regions = $this->db->query($regions_query)->result();

// Prepare labor data structure
$laborData = [];


// Prepare overall data
$overallData = [];

?>

<div class="row">
    <div class="col-md-12">

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th colspan="<?php echo ((count($components_data) * 6) + 1); ?>" style="text-align: center;">
                        <h5>Region-wise Labor Statistics</h5>
                    </th>
                </tr>
                <tr>
                    <th rowspan="3">Region</th>
                    <?php foreach ($components_data as $component) { ?>
                        <th colspan="6" style="text-align: center;">Component <?php echo $component->component; ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <?php foreach ($components_data as $component) { ?>
                        <th colspan="3">Unskilled Labor</th>
                        <th colspan="3">Skilled Labor</th>
                    <?php } ?>
                </tr>
                <tr> <?php foreach ($components_data as $component) { ?>
                        <th>Before</th>
                        <th>After</th>
                        <th>% Increase</th>
                        <th>Before</th>
                        <th>After</th>
                        <th>% Increase</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $index => $region) { ?>
                    <tr>
                        <th><?php echo $region->region; ?></th>
                        <?php foreach ($components_data as $component) {
                            $query = "
                                    SELECT 
                                    `sub_component`,
                                    ROUND(AVG(unskilled_labor_before)) AS unskilled_before, 
                                    ROUND(AVG(unskilled_labor_after)) AS unskilled_after,
                                    ROUND(( (AVG(unskilled_labor_after) - AVG(unskilled_labor_before)) / NULLIF(AVG(unskilled_labor_before), 0) ) * 100, 2) AS unskilled_increase,
                                    ROUND(AVG(skilled_labor_before)) AS skilled_before, 
                                    ROUND(AVG(skilled_labor_after)) AS skilled_after,
                                    ROUND(( (AVG(skilled_labor_after) - AVG(skilled_labor_before)) / NULLIF(AVG(skilled_labor_before), 0) ) * 100, 2) AS skilled_increase
                                    FROM `impact_surveys`
                                    WHERE `region` = " . $this->db->escape($region->region) . "
                                    AND `component` = " . $this->db->escape($component->component) . "
                                    GROUP BY `component`";
                            $result = $this->db->query($query)->row();
                            $laborData[$region->region][$component->component] = [
                                'unskilled_before' => $result->unskilled_before ?? 0,
                                'unskilled_after' => $result->unskilled_after ?? 0,
                                'skilled_before' => $result->skilled_before ?? 0,
                                'skilled_after' => $result->skilled_after ?? 0,
                                'unskilled_increase' => $result->unskilled_increase ?? 0,
                                'skilled_increase' => $result->skilled_increase ?? 0
                            ];
                        ?>

                            <td><?php echo $result->unskilled_before; ?></td>
                            <td><?php echo $result->unskilled_after; ?></td>
                            <td><?php echo $result->unskilled_increase; ?></td>
                            <td><?php echo $result->skilled_before; ?></td>
                            <td><?php echo $result->skilled_after; ?></td>
                            <td><?php echo $result->skilled_increase; ?></td>

                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Over All AVG</th>
                    <?php foreach ($components_data as $component) {
                        $query = "
                                    SELECT 
                                    `sub_component`,
                                    ROUND(AVG(unskilled_labor_before)) AS unskilled_before, 
                                    ROUND(AVG(unskilled_labor_after)) AS unskilled_after,
                                    ROUND(( (AVG(unskilled_labor_after) - AVG(unskilled_labor_before)) / NULLIF(AVG(unskilled_labor_before), 0) ) * 100, 2) AS unskilled_increase,
                                    ROUND(AVG(skilled_labor_before)) AS skilled_before, 
                                    ROUND(AVG(skilled_labor_after)) AS skilled_after,
                                    ROUND(( (AVG(skilled_labor_after) - AVG(skilled_labor_before)) / NULLIF(AVG(skilled_labor_before), 0) ) * 100, 2) AS skilled_increase
                                    FROM `impact_surveys`
                                    WHERE `component` = " . $this->db->escape($component->component) . "
                                    GROUP BY `component`";
                        $result = $this->db->query($query)->row();
                        $overallData[$component->component] = [
                            'unskilled_before' => $result->unskilled_before ?? 0,
                            'unskilled_after' => $result->unskilled_after ?? 0,
                            'skilled_before' => $result->skilled_before ?? 0,
                            'skilled_after' => $result->skilled_after ?? 0,
                            'unskilled_increase' => $result->unskilled_increase ?? 0,
                            'skilled_increase' => $result->skilled_increase ?? 0
                        ];

                    ?>

                        <th><?php echo $result->unskilled_before; ?></th>
                        <th><?php echo $result->unskilled_after; ?></th>
                        <th><?php echo $result->unskilled_increase; ?></th>
                        <th><?php echo $result->skilled_before; ?></th>
                        <th><?php echo $result->skilled_after; ?></th>
                        <th><?php echo $result->skilled_increase; ?></th>

                    <?php } ?>
                </tr>
            </tfoot>
        </table>




    </div>
</div>


<script>
    function convertToNo(obj) {
        if (typeof obj !== 'object' || obj === null) return obj;
        const newObj = Array.isArray(obj) ? [] : {};
        for (const key in obj) {
            if (typeof obj[key] === 'object' && obj[key] !== null) {
                newObj[key] = convertToNo(obj[key]);
            } else if (!isNaN(obj[key])) {
                newObj[key] = Number(obj[key]);
            } else {
                newObj[key] = obj[key];
            }
        }
        return newObj;
    }

    const components = <?php echo json_encode(array_column($components_data, 'component')); ?>;
    const regions = <?php echo json_encode(array_column($regions, 'region')); ?>;
    let laborData = <?php echo json_encode($laborData); ?>;
    let overallData = <?php echo json_encode($overallData); ?>;

    // Convert all numeric strings to numbers
    laborData = convertToNo(laborData);
    overallData = convertToNo(overallData);

    // 1. Before/After Comparison Chart
    Highcharts.chart('before-after-chart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Labor Employment: Before vs After'
        },
        xAxis: {
            categories: components,
            crosshair: true
        },
        yAxis: {
            title: {
                text: 'Number of Employees'
            }
        },
        tooltip: {
            headerFormat: '<b>{point.key}</b><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
        series: [{
                name: 'Unskilled Before',
                data: components.map(c => overallData[c].unskilled_before),
                color: '#7cb5ec'
            },
            {
                name: 'Unskilled After',
                data: components.map(c => overallData[c].unskilled_after),
                color: '#434348'
            },
            {
                name: 'Skilled Before',
                data: components.map(c => overallData[c].skilled_before),
                color: '#90ed7d'
            },
            {
                name: 'Skilled After',
                data: components.map(c => overallData[c].skilled_after),
                color: '#f7a35c'
            }
        ]
    });

    // 2. Percentage Increase Chart
    Highcharts.chart('increase-chart', {
        chart: {
            type: 'bar'
        },
        title: {
            text: 'Percentage Employment Increase'
        },
        xAxis: {
            categories: components
        },
        yAxis: {
            title: {
                text: 'Percentage Increase (%)'
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
                name: 'Unskilled Labor',
                data: components.map(c => overallData[c].unskilled_increase),
                color: '#7cb5ec'
            },
            {
                name: 'Skilled Labor',
                data: components.map(c => overallData[c].skilled_increase),
                color: '#90ed7d'
            }
        ]
    });

    // 3. Regional Breakdown Chart
    const regionalSeries = [];
    components.forEach((comp, i) => {
        regionalSeries.push({
            name: `${comp} - Unskilled`,
            data: regions.map(r => laborData[r][comp].unskilled_increase),
            stack: comp,
            color: Highcharts.getOptions().colors[i * 2]
        });
        regionalSeries.push({
            name: `${comp} - Skilled`,
            data: regions.map(r => laborData[r][comp].skilled_increase),
            stack: comp,
            color: Highcharts.getOptions().colors[i * 2 + 1]
        });
    });

    Highcharts.chart('regional-chart', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Regional Labor Increases'
        },
        xAxis: {
            categories: regions
        },
        yAxis: {
            title: {
                text: 'Percentage Increase (%)'
            },
            stackLabels: {
                enabled: true,
                format: '{total}%'
            }
        },
        tooltip: {
            headerFormat: '<b>{point.x}</b><br/>',
            pointFormat: '{series.name}: {point.y}%<br/>Total: {point.stackTotal}%'
        },
        plotOptions: {
            column: {
                stacking: 'normal',
                dataLabels: {
                    enabled: true,
                    format: '{point.y}%'
                }
            }
        },
        series: regionalSeries
    });
</script>

<!-- Add these containers where you want the charts -->
<div class="row">
    <div class="col-md-12">
        <div id="before-after-chart" style="height: 400px; margin-bottom: 30px;"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="increase-chart" style="height: 400px; margin-bottom: 30px;"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div id="regional-chart" style="height: 500px;"></div>
    </div>
</div>