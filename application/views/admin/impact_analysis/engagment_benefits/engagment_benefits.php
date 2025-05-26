<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis: Employment Growth Among Skilled and Unskilled Labor</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Engagment_Benefits'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'wheat', 'maize' , 'sugarcane' , 'vegetable' , 'orchard'], ['Summary', 'Crop & Component Wise' , 'Wheat', 'Maize' , 'Sugarcane' , 'Vegetable' , 'Orchard' ])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>
<hr />
<?php
$query = "SELECT `region` FROM `impact_surveys` 
GROUP BY `region` ASC;";
$regions_result = $this->db->query($query);
$regions = $regions_result->result();
$query = "SELECT `component` FROM `impact_surveys` 
GROUP BY `component` ORDER BY `component` ASC";
$components_result = $this->db->query($query);
$components = $components_result->result();
?>


<div class="row">
    <div class="col-md-12">

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th colspan="7" style="text-align: center;">
                        <h5>Region-wise Labor Statistics</h5>
                    </th>
                </tr>
                <tr>

                    <th rowspan="2">Region</th>
                    <th><small></small></th>
                    <th colspan="4">Unskilled Labor</th>
                    <th colspan="4">Skilled Labor</th>
                </tr>
                <tr>
                    <th><small>Total</small></th>
                    <th>Before</th>
                    <th>After</th>
                    <th>Increase</th>
                    <th>% Increase</th>
                    <th>Before</th>
                    <th>After</th>
                    <th>Increase</th>
                    <th>% Increase</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $index => $region) { ?>
                    <tr>
                        <th><?php echo $region->region; ?></th>
                        <?php
                        $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(unskilled_labor_before),2) AS unskilled_before, 
                                    ROUND(AVG(unskilled_labor_after),2) AS unskilled_after,
                                    ROUND((AVG(unskilled_labor_after)-AVG(unskilled_labor_before)),2) as unskilled_labor_increase,
                                    ROUND(( (AVG(unskilled_labor_after) - AVG(unskilled_labor_before)) / NULLIF(AVG(unskilled_labor_before), 0) ) * 100, 2) AS unskilled_increase,
                                    ROUND(AVG(skilled_labor_before),2) AS skilled_before, 
                                    ROUND(AVG(skilled_labor_after),2) AS skilled_after,
                                    ROUND((AVG(skilled_labor_after)-AVG(skilled_labor_before)),2) as skilled_labor_increase,
                                    ROUND(( (AVG(skilled_labor_after) - AVG(skilled_labor_before)) / NULLIF(AVG(skilled_labor_before), 0) ) * 100, 2) AS skilled_increase
                                    FROM `impact_surveys`
                                    WHERE `region` = " . $this->db->escape($region->region) . "";
                        $result = $this->db->query($query)->row();
                        $laborData[$region->region][$component->component] = [
                            'unskilled_before' => $result->unskilled_before,
                            'unskilled_after' => $result->unskilled_after,
                            'skilled_before' => $result->skilled_before,
                            'skilled_after' => $result->skilled_after,
                            'unskilled_increase' => $result->unskilled_increase,
                            'skilled_increase' => $result->skilled_increase
                        ];
                        ?>
                        <td><small><?php echo $result->total; ?></small></td>
                        <td><?php echo $result->unskilled_before; ?></td>
                        <td><?php echo $result->unskilled_after; ?></td>
                        <td><?php echo $result->unskilled_labor_increase; ?></td>
                        <th><?php echo $result->unskilled_increase; ?></th>
                        <td><?php echo $result->skilled_before; ?></td>
                        <td><?php echo $result->skilled_after; ?></td>
                        <td><?php echo $result->skilled_labor_increase; ?></td>
                        <th><?php echo $result->skilled_increase; ?></th>


                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Average</th>
                    <?php $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(unskilled_labor_before),2) AS unskilled_before, 
                                    ROUND(AVG(unskilled_labor_after),2) AS unskilled_after,
                                    ROUND((AVG(unskilled_labor_after)-AVG(unskilled_labor_before)),2) as unskilled_labor_increase,
                                    ROUND(( (AVG(unskilled_labor_after) - AVG(unskilled_labor_before)) / NULLIF(AVG(unskilled_labor_before), 0) ) * 100, 2) AS unskilled_increase,
                                    ROUND(AVG(skilled_labor_before),2) AS skilled_before, 
                                    ROUND(AVG(skilled_labor_after),2) AS skilled_after,
                                    ROUND((AVG(skilled_labor_after)-AVG(skilled_labor_before)),2) as skilled_labor_increase,
                                    ROUND(( (AVG(skilled_labor_after) - AVG(skilled_labor_before)) / NULLIF(AVG(skilled_labor_before), 0) ) * 100, 2) AS skilled_increase
                                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    $overallData[$component->component] = [
                        'unskilled_before' => $result->unskilled_before,
                        'unskilled_after' => $result->unskilled_after,
                        'skilled_before' => $result->skilled_before,
                        'skilled_after' => $result->skilled_after,
                        'unskilled_increase' => $result->unskilled_increase,
                        'skilled_increase' => $result->skilled_increase
                    ];

                    ?>
                    <td><small><?php echo $result->total; ?></small></td>
                    <th><?php echo $result->unskilled_before; ?></th>
                    <th><?php echo $result->unskilled_after; ?></th>
                    <th><?php echo $result->unskilled_labor_increase; ?></th>
                    <th><?php echo $result->unskilled_increase; ?></th>
                    <th><?php echo $result->skilled_before; ?></th>
                    <th><?php echo $result->skilled_after; ?></th>
                    <th><?php echo $result->skilled_labor_increase; ?></th>
                    <th><?php echo $result->skilled_increase; ?></th>
                </tr>
            </tfoot>
        </table>




    </div>
</div>


<div class="row">
    <div class="col-md-12">

        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th colspan="<?php echo ((count($components) * 6) + 1); ?>" style="text-align: center;">
                        <h5>Region-wise Labor Statistics</h5>
                    </th>
                </tr>
                <tr>
                    <th rowspan="3">Region</th>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="6" style="text-align: center;">Component <?php echo $component->component; ?></th>
                    <?php } ?>
                </tr>
                <tr>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="3">Unskilled Labor</th>
                        <th colspan="3">Skilled Labor</th>
                    <?php } ?>
                </tr>
                <tr> <?php foreach ($components as $component) { ?>
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
                        <?php foreach ($components as $component) {
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
                                'unskilled_before' => $result->unskilled_before,
                                'unskilled_after' => $result->unskilled_after,
                                'skilled_before' => $result->skilled_before,
                                'skilled_after' => $result->skilled_after,
                                'unskilled_increase' => $result->unskilled_increase,
                                'skilled_increase' => $result->skilled_increase
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
                    <?php foreach ($components as $component) {
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
                            'unskilled_before' => $result->unskilled_before,
                            'unskilled_after' => $result->unskilled_after,
                            'skilled_before' => $result->skilled_before,
                            'skilled_after' => $result->skilled_after,
                            'unskilled_increase' => $result->unskilled_increase,
                            'skilled_increase' => $result->skilled_increase
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

    const components = <?php echo json_encode(array_column($components, 'component')); ?>;
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