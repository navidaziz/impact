<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis: Impact on Citizen Income</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Income_Improvement'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Income_Improvement',['table_1', 'table_2','table_3', 'table_4'], ['Summary', 'Region and Component Wise' , 'Region and Sub Component Wise', 'Region and Categories Wise'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
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
    <div class="col-md-6">
        <?php
        $chartData = array();
        $categories = array();
        $perIncreaseData = array();
        ?>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Average Improved Income </strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="table_1">
                        <thead class="thead-light">
                            <tr>
                                <th style="display: none; text-align:center" colspan="5">Average Improved Income </th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th><small>Total</small></th>
                                <th class="text-center">Increase <small>(%)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($regions as $region) {
                                $query = "SELECT COUNT(*) as total,
                                ROUND(AVG(income_improved_per), 2) AS `per_increase`
                                FROM `impact_surveys`
                                WHERE region = ? ";
                                $result = $this->db->query($query, array($region->region));
                                $row = $result->row();
                                $categories[] = ucfirst($region->region);
                                $per_increase_weight += $row->per_increase * $row->total;
                                $perIncreaseData[] = (float) $row->per_increase;
                                $total += $row->total;
                            ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <th class="text-center"><small><?php echo $row->total; ?></small></th>
                                    <th class="text-center"><?php echo number_format($row->per_increase, 2); ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">
                            <?php $query = "SELECT COUNT(*) as total,
                            ROUND(AVG(income_improved_per), 2) AS `avg_per_increase`
                            FROM `impact_surveys` ";
                            $result = $this->db->query($query, array($region->region));
                            $row = $result->row();

                            $categories[] = 'Average';
                            $perIncreaseData[] = (float) $row->avg_per_increase;
                            $categories[] = 'Weighted Average';
                            ?>
                            <tr>
                                <th>Average</th>
                                <th class="text-center"><small><?php echo $row->total; ?></small></th>
                                <th class="text-center"><?php echo number_format($row->avg_per_increase, 2); ?></th>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <td class="text-center"><small><?php echo $total; ?></small></td>
                                <td class="text-center"><?php
                                                        $weightedAverage = round($per_increase_weight / $total, 2);
                                                        $perIncreaseData[] = (float) $weightedAverage;
                                                        echo number_format($weightedAverage, 2); ?></td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="increaseChart" style="height: 310px;"></div>
            </div>
        </div>
    </div>
    <script>
        // Percentage Increase Chart
        Highcharts.chart('increaseChart', {
            chart: {
                type: 'spline'
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
                spline: {
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
    <div class="col-md-6">
        <div class="card shadow-sm mb-4" style="margin-top: 8px;">
            <div class="card-body">
                <div id="region_component_chart_income_imp" style="min-width: 100%; height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm mb-4" style="margin-top: 8px;">
            <div class="card-header bg-primary text-white">
                <strong>Average Improved Income by Regions and Component Wise</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="table_2" class="table table-bordered table-hover table-striped table-sm" style="font-size: 12px;">
                        <thead class="thead-light">
                            <tr style="display: n one;">
                                <th colspan="7">Average Improved Income by Regions and Component Wise</th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="align-middle">Regions</th>
                                <?php foreach ($components as $component) { ?>
                                    <th colspan="2" class="text-center">Component <?php echo $component->component; ?></th>
                                <?php } ?>
                                <th colspan="2" class="text-center">Overall Average</th>
                            </tr>
                            <tr>
                                <?php foreach ($components as $component) { ?>
                                    <th class="text-center"><small>Total</small></th>
                                    <th class="text-center">Income Improved Per <small>AVG</small></th>
                                <?php } ?>
                                <th class="text-center"><small>Total</small></th>
                                <th class="text-center">Income Improved Per <small>AVG</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $income_improved_per = 0;
                            $categories = array();
                            foreach ($components as $component) {
                                $component->total = 0;
                                $component->income_improved_per = 0;
                            }
                            $chart_data = array();
                            foreach ($regions as $region) {
                                $categories[] = ucfirst($region->region);
                            ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    foreach ($components as $component) {

                                        $query = "SELECT 
                                        COUNT(*) as total,
                                        ROUND(AVG(income_improved_per), 2) AS `income_improved_per`
                                        FROM `impact_surveys`  
                                        WHERE component = ? AND region = ?";
                                        $result = $this->db->query($query, array($component->component, $region->region));
                                        $row = $result->row();
                                        // Accumulate region totals
                                        $component->total += $row->total;
                                        $component->income_improved_per += $row->income_improved_per * $row->total;
                                        // Prepare chart data
                                        if (!isset($chart_data[$component->component])) {
                                            $chart_data[$component->component] = array(
                                                'regions' => array(),
                                                'income_improved_per' => array()
                                            );
                                        }
                                        $chart_data[$component->component]['regions'][] = ucfirst($region->region);
                                        $chart_data[$component->component]['income_improved_per'][] = isset($row->income_improved_per) ? (float) $row->income_improved_per : (float) 0;

                                    ?>
                                        <td class="text-center"><small><?php echo $row->total; ?></small></td>
                                        <th class="text-center"><?php echo number_format($row->income_improved_per, 2); ?></th>
                                    <?php } ?>

                                    <?php
                                    // Get overall averages for the region
                                    $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(income_improved_per), 2) AS `income_improved_per`
                                    FROM `impact_surveys`  
                                    WHERE region = ?";
                                    $result = $this->db->query($query, array($region->region));
                                    $row = $result->row();

                                    $total += $row->total;
                                    $income_improved_per += $row->income_improved_per * $row->total;
                                    if (!isset($chart_data['Over All'])) {
                                        $chart_data['Over All'] = array(
                                            'regions' => array(),
                                            'income_improved_per' => array()
                                        );
                                    }
                                    $chart_data['Over All']['regions'][] = ucfirst($region->region);
                                    $chart_data['Over All']['income_improved_per'][] = isset($row->income_improved_per) ? (float) $row->income_improved_per : (float)  0;
                                    ?>
                                    <td class="text-center"><small><?php echo $row->total; ?></small></td>
                                    <th class="text-center"><?php echo number_format($row->income_improved_per, 2); ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">

                            <tr>
                                <th>Weighted Average</th>

                                <?php
                                $categories[] = 'Weighted Average';
                                foreach ($components as $component) {
                                    $chart_data[$component->component]['regions'][] = 'Weighted AVG';
                                    $chart_data[$component->component]['income_improved_per'][] = $component->total != 0 ? round($component->income_improved_per / $component->total, 2) : 0;
                                ?>
                                    <td class="text-center"><small><?php echo $component->total; ?></small></td>
                                    <td class="text-center"><?php echo $component->total != 0 ? round($component->income_improved_per / $component->total, 2) : '0.00'; ?></td>
                                <?php }
                                $chart_data['Over All']['regions'][] = 'Weighted AVG';
                                $chart_data['Over All']['income_improved_per'][] = $total != 0 ? round($income_improved_per / $total, 2) : 0;
                                ?>
                                <td class="text-center"><small><?php echo $total; ?></small></td>
                                <td class="text-center"><?php echo $total != 0 ? round($income_improved_per / $total, 2) : '0.00'; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <script>
        chartData = <?php echo json_encode($chart_data); ?>;

        Highcharts.chart('region_component_chart_income_imp', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Average Improved Income by Regions and Component Wise'
            },
            xAxis: {
                categories: chartData['Over All']['regions'],
                title: {
                    text: 'Regions'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Income Improved Per (Average)'
                }
            },
            tooltip: {
                shared: true
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

                },
                spline: {
                    dataLabels: {
                        enabled: true,
                        format: '{y} %',
                        crop: false, // Don't hide labels outside the plot area
                        overflow: 'none', // Prevent hiding when overflowing
                        allowOverlap: true,
                        rotation: -90,
                        style: {
                            fontSize: '9px' // Change to your desired font size

                        },
                    }
                }

            },
            series: Object.entries(chartData).map(([key, val]) => ({
                name: key === 'Over All' ? 'Overall Average' : `Component ${key}`,
                data: val.income_improved_per,
                type: key === 'Over All' ? 'spline' : 'column',
            }))
        });
    </script>


</div>

<div class="row">
    <?php
    $query = "SELECT `sub_component` FROM `impact_surveys` 
                                                        GROUP BY `sub_component` ORDER BY `sub_component` ASC";
    $sub_components_result = $this->db->query($query);
    $sub_components = $sub_components_result->result();
    ?>
    <div class="col-md-12">
        <div class="card shadow-sm mb-4" style="margin-top: 8px;">
            <div class="card-header bg-primary text-white">
                <strong>Average Improved Income by Regions and Sub Components Wise</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="table_3" class="table table-bordered table-hover table-striped table-sm" style="font-size: 12px;">
                        <thead class="thead-light">
                            <tr style="display: n one;">
                                <th colspan="<?php echo (1 + (count($sub_components) * 2) + 2) ?>">Average Improved Income by Regions and Sub Components Wise</th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="align-middle">Regions</th>
                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th colspan="2" class="text-center">Sub Component <?php echo $sub_component->sub_component; ?></th>
                                <?php } ?>
                                <th colspan="2" class="text-center">Overall Average</th>
                            </tr>
                            <tr>
                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th class="text-center"><small>Total</small></th>
                                    <th class="text-center">Income Improved Per <small>AVG</small></th>
                                <?php } ?>
                                <th class="text-center"><small>Total</small></th>
                                <th class="text-center">Income Improved Per <small>AVG</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $income_improved_per = 0;
                            $categories = array();
                            foreach ($sub_components as $sub_component) {
                                $sub_component->total = 0;
                                $sub_component->income_improved_per = 0;
                            }
                            $chart_data = array();
                            foreach ($regions as $region) {
                                $categories[] = ucfirst($region->region);
                            ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    foreach ($sub_components as $sub_component) {

                                        $query = "SELECT 
                                        COUNT(*) as total,
                                        ROUND(AVG(income_improved_per), 2) AS `income_improved_per`
                                        FROM `impact_surveys`  
                                        WHERE sub_component = ? AND region = ?";
                                        $result = $this->db->query($query, array($sub_component->sub_component, $region->region));
                                        $row = $result->row();
                                        // Accumulate region totals
                                        $sub_component->total += $row->total;
                                        $sub_component->income_improved_per += $row->income_improved_per * $row->total;
                                        // Prepare chart data
                                        if (!isset($chart_data[$sub_component->sub_component])) {
                                            $chart_data[$sub_component->sub_component] = array(
                                                'regions' => array(),
                                                'income_improved_per' => array()
                                            );
                                        }
                                        $chart_data[$sub_component->sub_component]['regions'][] = ucfirst($region->region);
                                        $chart_data[$sub_component->sub_component]['income_improved_per'][] = isset($row->income_improved_per) ? (float) $row->income_improved_per : (float) 0;

                                    ?>
                                        <td class="text-center"><small><?php echo $row->total; ?></small></td>
                                        <th class="text-center"><?php echo number_format($row->income_improved_per, 2); ?></th>
                                    <?php } ?>

                                    <?php
                                    // Get overall averages for the region
                                    $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(income_improved_per), 2) AS `income_improved_per`
                                    FROM `impact_surveys`  
                                    WHERE region = ?";
                                    $result = $this->db->query($query, array($region->region));
                                    $row = $result->row();

                                    $total += $row->total;
                                    $income_improved_per += $row->income_improved_per * $row->total;
                                    if (!isset($chart_data['Over All'])) {
                                        $chart_data['Over All'] = array(
                                            'regions' => array(),
                                            'income_improved_per' => array()
                                        );
                                    }
                                    $chart_data['Over All']['regions'][] = ucfirst($region->region);
                                    $chart_data['Over All']['income_improved_per'][] = isset($row->income_improved_per) ? (float) $row->income_improved_per : (float)  0;
                                    ?>
                                    <td class="text-center"><small><?php echo $row->total; ?></small></td>
                                    <th class="text-center"><?php echo number_format($row->income_improved_per, 2); ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">

                            <tr>
                                <th>Weighted Average</th>

                                <?php
                                $categories[] = 'Weighted Average';
                                foreach ($sub_components as $sub_component) {
                                    $chart_data[$sub_component->sub_component]['regions'][] = 'Weighted AVG';
                                    $chart_data[$sub_component->sub_component]['income_improved_per'][] = $sub_component->total != 0 ? round($sub_component->income_improved_per / $sub_component->total, 2) : 0;
                                ?>
                                    <td class="text-center"><small><?php echo $sub_component->total; ?></small></td>
                                    <td class="text-center"><?php echo $sub_component->total != 0 ? round($sub_component->income_improved_per / $sub_component->total, 2) : '0.00'; ?></td>
                                <?php }
                                $chart_data['Over All']['regions'][] = 'Weighted AVG';
                                $chart_data['Over All']['income_improved_per'][] = $total != 0 ? round($income_improved_per / $total, 2) : 0;
                                ?>
                                <td class="text-center"><small><?php echo $total; ?></small></td>
                                <td class="text-center"><?php echo $total != 0 ? round($income_improved_per / $total, 2) : '0.00'; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">

        <?php
        $query = "SELECT `category` FROM `impact_surveys` 
                                                        GROUP BY `category` ORDER BY `category` ASC";
        $categorys_result = $this->db->query($query);
        $categorys = $categorys_result->result();
        ?>
        <div class="card shadow-sm mb-4" style="margin-top: 8px;">
            <div class="card-header bg-primary text-white">
                <strong>Average Improved Income by Regions and Category Wise</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="table_4" class="table table-bordered table-hover table-striped table-sm" style="font-size: 12px;">
                        <thead class="thead-light">
                            <tr style="display: n one;">
                                <th colspan="<?php echo (1 + (count($categorys) * 2) + 2) ?>">Average Improved Income by Regions and Category Wise</th>
                            </tr>
                            <tr>
                                <th rowspan="2" class="align-middle">Regions</th>
                                <?php foreach ($categorys as $category) { ?>
                                    <th colspan="2" class="text-center">Category <?php echo $category->category; ?></th>
                                <?php } ?>
                                <th colspan="2" class="text-center">Overall Average</th>
                            </tr>
                            <tr>
                                <?php foreach ($categorys as $category) { ?>
                                    <th class="text-center"><small>Total</small></th>
                                    <th class="text-center">Income Improved Per <small>AVG</small></th>
                                <?php } ?>
                                <th class="text-center"><small>Total</small></th>
                                <th class="text-center">Income Improved Per <small>AVG</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $income_improved_per = 0;
                            $categories = array();
                            foreach ($categorys as $category) {
                                $category->total = 0;
                                $category->income_improved_per = 0;
                            }
                            $chart_data = array();
                            foreach ($regions as $region) {
                                $categories[] = ucfirst($region->region);
                            ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php
                                    foreach ($categorys as $category) {

                                        $query = "SELECT 
                                        COUNT(*) as total,
                                        ROUND(AVG(income_improved_per), 2) AS `income_improved_per`
                                        FROM `impact_surveys`  
                                        WHERE category = ? AND region = ?";
                                        $result = $this->db->query($query, array($category->category, $region->region));
                                        $row = $result->row();
                                        // Accumulate region totals
                                        $category->total += $row->total;
                                        $category->income_improved_per += $row->income_improved_per * $row->total;
                                        // Prepare chart data
                                        if (!isset($chart_data[$category->category])) {
                                            $chart_data[$category->category] = array(
                                                'regions' => array(),
                                                'income_improved_per' => array()
                                            );
                                        }
                                        $chart_data[$category->category]['regions'][] = ucfirst($region->region);
                                        $chart_data[$category->category]['income_improved_per'][] = isset($row->income_improved_per) ? (float) $row->income_improved_per : (float) 0;

                                    ?>
                                        <td class="text-center"><small><?php echo $row->total; ?></small></td>
                                        <th class="text-center"><?php echo number_format($row->income_improved_per, 2); ?></th>
                                    <?php } ?>

                                    <?php
                                    // Get overall averages for the region
                                    $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(income_improved_per), 2) AS `income_improved_per`
                                    FROM `impact_surveys`  
                                    WHERE region = ?";
                                    $result = $this->db->query($query, array($region->region));
                                    $row = $result->row();

                                    $total += $row->total;
                                    $income_improved_per += $row->income_improved_per * $row->total;
                                    if (!isset($chart_data['Over All'])) {
                                        $chart_data['Over All'] = array(
                                            'regions' => array(),
                                            'income_improved_per' => array()
                                        );
                                    }
                                    $chart_data['Over All']['regions'][] = ucfirst($region->region);
                                    $chart_data['Over All']['income_improved_per'][] = isset($row->income_improved_per) ? (float) $row->income_improved_per : (float)  0;
                                    ?>
                                    <td class="text-center"><small><?php echo $row->total; ?></small></td>
                                    <th class="text-center"><?php echo number_format($row->income_improved_per, 2); ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot class="font-weight-bold">

                            <tr>
                                <th>Weighted Average</th>

                                <?php
                                $categories[] = 'Weighted Average';
                                foreach ($categorys as $category) {
                                    $chart_data[$category->category]['regions'][] = 'Weighted AVG';
                                    $chart_data[$category->category]['income_improved_per'][] = $category->total != 0 ? round($category->income_improved_per / $category->total, 2) : 0;
                                ?>
                                    <td class="text-center"><small><?php echo $category->total; ?></small></td>
                                    <td class="text-center"><?php echo $category->total != 0 ? round($category->income_improved_per / $category->total, 2) : '0.00'; ?></td>
                                <?php }
                                $chart_data['Over All']['regions'][] = 'Weighted AVG';
                                $chart_data['Over All']['income_improved_per'][] = $total != 0 ? round($income_improved_per / $total, 2) : 0;
                                ?>
                                <td class="text-center"><small><?php echo $total; ?></small></td>
                                <td class="text-center"><?php echo $total != 0 ? round($income_improved_per / $total, 2) : '0.00'; ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>