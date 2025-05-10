<h4>Impact Analysis on Citizen Engagement and Benefits in Income and Employment</h4>
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