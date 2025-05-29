<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis: Employment Growth Among Skilled and Unskilled Labor</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Employment_Growth'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Employment_Growth',['table_1', 'table_2', 'table_3', 'table_4'], ['Summary', 'Region and Component Wise' , 'Region and Sub Component Wise', 'Region and Categories Wise'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
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
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region-wise Employment Growth Among Skilled and Unskilled Labors</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_1">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="10" style="text-align: center;">
                                    Region-wise Employment Growth Among Skilled and Unskilled Labors
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
                            <?php
                            $chartData = array();

                            foreach ($regions as $index => $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
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
                                    $chartData[ucfirst($region->region)] = [
                                        'unskilled_increase' => (float) $result->unskilled_increase,
                                        'skilled_increase' => (float) $result->skilled_increase
                                    ];

                                    $total += $result->total;

                                    $unskilled_befor_weight += $result->unskilled_before * $result->total;
                                    $unskilled_after_weight += $result->unskilled_after * $result->total;
                                    $unskilled_increase_weight += $result->unskilled_labor_increase * $result->total;
                                    $unskilled_per_increase_weight += $result->unskilled_increase * $result->total;

                                    $skilled_befor_weight += $result->skilled_before * $result->total;
                                    $skilled_after_weight += $result->skilled_after * $result->total;
                                    $skilled_increase_weight += $result->skilled_labor_increase * $result->total;
                                    $skilled_per_increase_weight += $result->skilled_increase * $result->total;



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
                            <tr>
                                <th>Weighted Average</th>
                                <td><small><?php echo $total; ?></small></td>
                                <th><?php echo round($unskilled_befor_weight / $total, 2); ?></th>
                                <th><?php echo round($unskilled_after_weight / $total, 2); ?></th>
                                <th><?php echo round($unskilled_increase_weight / $total, 2); ?></th>
                                <th><?php echo round($unskilled_per_increase_weight / $total, 2); ?></th>
                                <th><?php echo round($skilled_befor_weight / $total, 2); ?></th>
                                <th><?php echo round($skilled_after_weight / $total, 2); ?></th>
                                <th><?php echo round($skilled_increase_weight / $total, 2); ?></th>
                                <th><?php echo round($skilled_per_increase_weight / $total, 2); ?></th>
                                <?php

                                $chartData['Weighted Average'] = [
                                    'unskilled_increase' => round($unskilled_per_increase_weight / $total, 2),
                                    'skilled_increase' => round($skilled_per_increase_weight / $total, 2)
                                ]; ?>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">

                <div id="laborGrowthChart" style="width: 100%;"></div>

                <script>
                    chartData = <?php echo json_encode($chartData); ?>;
                    categories = Object.keys(chartData);

                    skilledData = categories.map(region => chartData[region].skilled_increase);
                    unskilledData = categories.map(region => chartData[region].unskilled_increase);

                    Highcharts.chart('laborGrowthChart', {
                        chart: {
                            type: 'column'
                        },
                        title: {
                            text: 'Region-wise % Increase in Skilled vs Unskilled Labor'
                        },
                        xAxis: {
                            categories: categories,
                            crosshair: true,
                            title: {
                                text: 'Regions'
                            }
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: '% Increase in Employment'
                            }
                        },
                        tooltip: {
                            shared: true,
                            valueSuffix: '%'
                        },


                        plotOptions: {
                            column: {
                                pointPadding: 0.2,
                                borderWidth: 0,
                                dataLabels: {
                                    enabled: true,
                                    format: '{y} %',
                                    crop: false, // Don't hide labels outside the plot area
                                    overflow: 'none', // Prevent hiding when overflowing
                                    allowOverlap: true,
                                    rotation: -90,
                                    style: {
                                        fontSize: '9px' // Change to your desired font size
                                    }

                                },

                            }
                        },
                        series: [{
                            name: 'Unskilled Labor',
                            data: unskilledData,
                            color: '#f45b5b'
                        }, {
                            name: 'Skilled Labor',
                            data: skilledData,
                            color: '#90ed7d'
                        }]
                    });
                </script>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region-wise Employment Growth Among Skilled and Unskilled Labors</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table_medium" id="table_2">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo (2 + (COUNT($components) * 9)); ?>" style="text-align: center;">
                                    Region and Components Wise Employment Growth Among Skilled and Unskilled Labors
                                </th>
                            </tr>
                            <tr>
                                <th rowspan="3">Region</th>
                                <?php foreach ($components as $component) { ?>
                                    <th colspan="9">Component <?php echo $component->component; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($components as $component) { ?>
                                    <th colspan="5">Unskilled Labor</th>
                                    <th colspan="4">Skilled Labor</th>
                                <?php } ?>
                            </tr>
                            <tr>

                                <?php foreach ($components as $component) { ?>
                                    <th><small>Total</small></th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Increase</th>
                                    <th>% Increase</th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Increase</th>
                                    <th>% Increase</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $chartData = array();
                            $weighted_averages = array();
                            //var_dump($regions);
                            foreach ($regions as $index => $region) { ?>

                                <tr>

                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php foreach ($components as $component) { ?>
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
                                        WHERE `region` = " . $this->db->escape($region->region) . "
                                        AND component = " . $this->db->escape($component->component) . "";
                                        $result = $this->db->query($query)->row();

                                        $weighted_averages[$component->component]['total'] += $result->total;
                                        $weighted_averages[$component->component]['unskilled_befor_weight'] += $result->unskilled_before * $result->total;
                                        $weighted_averages[$component->component]['unskilled_after_weight'] += $result->unskilled_after * $result->total;
                                        $weighted_averages[$component->component]['unskilled_increase_weight'] += $result->unskilled_labor_increase * $result->total;
                                        $weighted_averages[$component->component]['unskilled_per_increase_weight'] += $result->unskilled_increase * $result->total;
                                        $weighted_averages[$component->component]['skilled_befor_weight'] += $result->skilled_before * $result->total;
                                        $weighted_averages[$component->component]['skilled_after_weight'] += $result->skilled_after * $result->total;
                                        $weighted_averages[$component->component]['skilled_increase_weight'] += $result->skilled_labor_increase * $result->total;
                                        $weighted_averages[$component->component]['skilled_per_increase_weight'] += $result->skilled_increase * $result->total;


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
                                    <?php } ?>

                                </tr>

                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($components as $component) { ?>
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
                                    FROM `impact_surveys` 
                                    WHERE  component = " . $this->db->escape($component->component) . "";
                                    $result = $this->db->query($query)->row();

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
                                <?php } ?>
                            </tr>

                            <tr>
                                <th>Weighted Average</th>
                                <?php foreach ($weighted_averages as $weighted_average) { ?>
                                    <td><small><?php echo $weighted_average['total']; ?></small></td>
                                    <th><?php echo round($weighted_average['unskilled_befor_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_after_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_per_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_befor_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_after_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_per_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>


<?php

$query = "SELECT `sub_component` FROM `impact_surveys` 
GROUP BY `sub_component` ORDER BY `sub_component` ASC";
$sub_components_result = $this->db->query($query);
$sub_components = $sub_components_result->result();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region-wise and Categories Wise Employment Growth Among Skilled and Unskilled Labors</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table_medium" id="table_3">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo (2 + (COUNT($sub_components) * 9)); ?>" style="text-align: center;">
                                    Region and Sub Component Wise Employment Growth Among Skilled and Unskilled Labors
                                </th>
                            </tr>
                            <tr>
                                <th rowspan="3">Region</th>
                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th colspan="9">Sub Component <?php echo $sub_component->sub_component; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th colspan="5">Unskilled Labor</th>
                                    <th colspan="4">Skilled Labor</th>
                                <?php } ?>
                            </tr>
                            <tr>

                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th><small>Total</small></th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Increase</th>
                                    <th>% Increase</th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Increase</th>
                                    <th>% Increase</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $chartData = array();
                            $weighted_averages = array();
                            //var_dump($regions);
                            foreach ($regions as $index => $region) { ?>

                                <tr>

                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php foreach ($sub_components as $sub_component) { ?>
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
                                        WHERE `region` = " . $this->db->escape($region->region) . "
                                        AND sub_component = " . $this->db->escape($sub_component->sub_component) . "";
                                        $result = $this->db->query($query)->row();

                                        $weighted_averages[$sub_component->sub_component]['total'] += $result->total;
                                        $weighted_averages[$sub_component->sub_component]['unskilled_befor_weight'] += $result->unskilled_before * $result->total;
                                        $weighted_averages[$sub_component->sub_component]['unskilled_after_weight'] += $result->unskilled_after * $result->total;
                                        $weighted_averages[$sub_component->sub_component]['unskilled_increase_weight'] += $result->unskilled_labor_increase * $result->total;
                                        $weighted_averages[$sub_component->sub_component]['unskilled_per_increase_weight'] += $result->unskilled_increase * $result->total;
                                        $weighted_averages[$sub_component->sub_component]['skilled_befor_weight'] += $result->skilled_before * $result->total;
                                        $weighted_averages[$sub_component->sub_component]['skilled_after_weight'] += $result->skilled_after * $result->total;
                                        $weighted_averages[$sub_component->sub_component]['skilled_increase_weight'] += $result->skilled_labor_increase * $result->total;
                                        $weighted_averages[$sub_component->sub_component]['skilled_per_increase_weight'] += $result->skilled_increase * $result->total;


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
                                    <?php } ?>

                                </tr>

                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($sub_components as $sub_component) { ?>
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
                                    FROM `impact_surveys` 
                                    WHERE  sub_component = " . $this->db->escape($sub_component->sub_component) . "";
                                    $result = $this->db->query($query)->row();

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
                                <?php } ?>
                            </tr>

                            <tr>
                                <th>Weighted Average</th>
                                <?php foreach ($weighted_averages as $weighted_average) { ?>
                                    <td><small><?php echo $weighted_average['total']; ?></small></td>
                                    <th><?php echo round($weighted_average['unskilled_befor_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_after_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_per_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_befor_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_after_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_per_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

<?php

$query = "SELECT `category` FROM `impact_surveys` 
GROUP BY `category` ORDER BY `category` ASC";
$categorys_result = $this->db->query($query);
$categorys = $categorys_result->result();
?>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region-wise and Categories Wise Employment Growth Among Skilled and Unskilled Labors</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table_medium" id="table_4">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo (2 + (COUNT($categorys) * 9)); ?>" style="text-align: center;">
                                    Region and Categories Wise Employment Growth Among Skilled and Unskilled Labors
                                </th>
                            </tr>
                            <tr>
                                <th rowspan="3">Region</th>
                                <?php foreach ($categorys as $category) { ?>
                                    <th colspan="9">Category <?php echo $category->category; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($categorys as $category) { ?>
                                    <th colspan="5">Unskilled Labor</th>
                                    <th colspan="4">Skilled Labor</th>
                                <?php } ?>
                            </tr>
                            <tr>

                                <?php foreach ($categorys as $category) { ?>
                                    <th><small>Total</small></th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Increase</th>
                                    <th>% Increase</th>
                                    <th>Before</th>
                                    <th>After</th>
                                    <th>Increase</th>
                                    <th>% Increase</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $chartData = array();
                            $weighted_averages = array();
                            //var_dump($regions);
                            foreach ($regions as $index => $region) { ?>

                                <tr>

                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php foreach ($categorys as $category) { ?>
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
                                        WHERE `region` = " . $this->db->escape($region->region) . "
                                        AND category = " . $this->db->escape($category->category) . "";
                                        $result = $this->db->query($query)->row();

                                        $weighted_averages[$category->category]['total'] += $result->total;
                                        $weighted_averages[$category->category]['unskilled_befor_weight'] += $result->unskilled_before * $result->total;
                                        $weighted_averages[$category->category]['unskilled_after_weight'] += $result->unskilled_after * $result->total;
                                        $weighted_averages[$category->category]['unskilled_increase_weight'] += $result->unskilled_labor_increase * $result->total;
                                        $weighted_averages[$category->category]['unskilled_per_increase_weight'] += $result->unskilled_increase * $result->total;
                                        $weighted_averages[$category->category]['skilled_befor_weight'] += $result->skilled_before * $result->total;
                                        $weighted_averages[$category->category]['skilled_after_weight'] += $result->skilled_after * $result->total;
                                        $weighted_averages[$category->category]['skilled_increase_weight'] += $result->skilled_labor_increase * $result->total;
                                        $weighted_averages[$category->category]['skilled_per_increase_weight'] += $result->skilled_increase * $result->total;


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
                                    <?php } ?>

                                </tr>

                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($categorys as $category) { ?>
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
                                    FROM `impact_surveys` 
                                    WHERE  category = " . $this->db->escape($category->category) . "";
                                    $result = $this->db->query($query)->row();

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
                                <?php } ?>
                            </tr>

                            <tr>
                                <th>Weighted Average</th>
                                <?php foreach ($weighted_averages as $weighted_average) { ?>
                                    <td><small><?php echo $weighted_average['total']; ?></small></td>
                                    <th><?php echo round($weighted_average['unskilled_befor_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_after_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['unskilled_per_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_befor_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_after_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                    <th><?php echo round($weighted_average['skilled_per_increase_weight'] / $weighted_average['total'], 2); ?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>