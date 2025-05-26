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

        // Prepare data for charts
        $component_categories = array_column($components_data, 'component');
        $component_values = array_column($components_data, 'avg_increase');

        $sub_component_categories = array_column($sub_components_data, 'sub_component');
        $sub_component_values = array_column($sub_components_data, 'avg_increase');
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
                <?php
                $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                $regions = $this->db->query($query)->result();
                foreach ($regions as $region) {
                    // Fetch data for each region
                    $region_components_query = "SELECT `component`, ROUND(AVG(income_improved_per), 2) as avg_increase 
                                                   FROM `impact_surveys` 
                                                   WHERE `region` = '" . $region->region . "' 
                                                   GROUP BY `component` 
                                                   ORDER BY `component` ASC";
                    $region_components_data = $this->db->query($region_components_query)->result();

                    $region_sub_components_query = "SELECT `sub_component`, ROUND(AVG(income_improved_per), 2) as avg_increase 
                                                        FROM `impact_surveys` 
                                                        WHERE `region` = '" . $region->region . "' 
                                                        GROUP BY `sub_component` 
                                                        ORDER BY `sub_component` ASC";
                    $region_sub_components_data = $this->db->query($region_sub_components_query)->result();

                    // Calculate total average for the region
                    $total_avg = 0;
                    foreach ($region_components_data as $data) {
                        $total_avg += $data->avg_increase;
                    }
                    foreach ($region_sub_components_data as $data) {
                        $total_avg += $data->avg_increase;
                    }
                    $total_avg /= round((count($region_components_data) + count($region_sub_components_data)), 2);
                ?>
                    <tr>
                        <td><?php echo $region->region; ?></td>
                        <?php foreach ($components_data as $component): ?>
                            <td><?php echo $component->avg_increase; ?></td>
                        <?php endforeach; ?>
                        <?php foreach ($sub_components_data as $sub_component): ?>
                            <td><?php echo $sub_component->avg_increase; ?></td>
                        <?php endforeach; ?>
                        <td><?php echo $total_avg; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php

                // Fetch data for each region
                $region_components_query = "SELECT `component`, ROUND(AVG(income_improved_per), 2) as avg_increase 
                                                   FROM `impact_surveys` 
                                                   GROUP BY `component` 
                                                   ORDER BY `component` ASC";
                $region_components_data = $this->db->query($region_components_query)->result();

                $region_sub_components_query = "SELECT `sub_component`, ROUND(AVG(income_improved_per), 2) as avg_increase 
                                                        FROM `impact_surveys`
                                                        GROUP BY `sub_component` 
                                                        ORDER BY `sub_component` ASC";
                $region_sub_components_data = $this->db->query($region_sub_components_query)->result();

                // Calculate total average for the region
                $total_avg = 0;
                foreach ($region_components_data as $data) {
                    $total_avg += $data->avg_increase;
                }
                foreach ($region_sub_components_data as $data) {
                    $total_avg += $data->avg_increase;
                }
                $total_avg /= round((count($region_components_data) + count($region_sub_components_data)), 2);
                ?>
                <tr>
                    <td>Average Income Increase (%)</td>
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

        <script>
            $(document).ready(function() {
                // Component Chart
                Highcharts.chart('component_chart', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Income Improvement by Component'
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
                        text: 'Income Improvement by Sub-Component'
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
            });
        </script>

        <div class="col-md-12">


        </div>

    </div>
</div>