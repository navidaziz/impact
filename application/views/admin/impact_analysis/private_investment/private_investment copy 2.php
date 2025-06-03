<div class="row">
    <div class="col-md-8">
        <h4>Impact Analysis: Private Investment in PKR (Community Share)</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Private_Investment'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Private_Investment',['table_1', 'table_2', 'table_3', 'table_4'], ['Summary', 'Component Wise' , 'Sub Component Wise', 'Categories Wise'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Private Investment in PKR (Community Share)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_1">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="13" class="text-center">Private Investment in PKR (Community Share)</th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th><small>Total</small></th>
                                <th>Cost <br />
                                    <small>Per Scheme (AVG)</small>
                                </th>
                                <th>Community Share <br />
                                    <small>Per Scheme (AVG)</small>
                                </th>
                                <th>Community Share %</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                            $regions = $this->db->query($query)->result();
                            $chartData = array();

                            ?>
                            <?php foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php
                                    $query = "SELECT 
                                        COUNT(*) AS total, 
                                        AVG(actual_cost) AS avg_actual_cost,
                                        AVG(community_share) AS avg_community_share,
                                        AVG((community_share / NULLIF(actual_cost + community_share, 0)) * 100) AS community_share_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'";
                                    $result = $this->db->query($query)->row();

                                    // Calculate community share percentage
                                    $community_share_percentage = 0;
                                    if ($result->avg_actual_cost > 0) {
                                        $community_share_percentage = $result->community_share_percentage;
                                    }
                                    $total += $result->total;
                                    $w_avg_actual_cost += $result->avg_actual_cost * $result->total;
                                    $w_avg_community_share += $result->avg_community_share * $result->total;
                                    $w_community_share_percentage += $community_share_percentage * $result->total;

                                    $chartData[ucfirst($region->region)]['avg_actual_cost'] = (float) $result->avg_actual_cost;
                                    $chartData[ucfirst($region->region)]['avg_community_share'] = (float) $result->avg_community_share;
                                    $chartData[ucfirst($region->region)]['community_share_percentage'] = (float) $community_share_percentage;
                                    ?>
                                    <td><small><?php echo $result->total; ?></small></td>
                                    <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                    <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                                    <th><?php echo number_format($community_share_percentage, 2); ?></th>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Overall</th>
                                <?php
                                $query = "SELECT 
                                        COUNT(*) AS total, 
                                        AVG(actual_cost) AS avg_actual_cost,
                                        AVG(community_share) AS avg_community_share,
                                        AVG((community_share / NULLIF(actual_cost + community_share, 0)) * 100) AS community_share_percentage
                                    FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();

                                // Calculate community share percentage
                                $community_share_percentage = 0;
                                if ($result->avg_actual_cost > 0) {
                                    $community_share_percentage = $result->community_share_percentage;
                                }
                                ?>
                                <td><small><?php echo $result->total; ?></small></td>
                                <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                                <th><?php echo number_format($community_share_percentage, 2); ?></th>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <td><small><?php echo $total; ?></small></td>
                                <th><?php echo number_format($w_avg_actual_cost / $total, 2); ?></th>
                                <th><?php echo number_format($w_avg_community_share / $total, 2); ?></th>
                                <th><?php echo number_format($w_community_share_percentage / $total, 2);
                                    $chartData['Weighted Average']['avg_actual_cost'] = (float) $w_avg_actual_cost / $total;
                                    $chartData['Weighted Average']['avg_community_share'] = (float) $w_avg_community_share / $total;
                                    $chartData['Weighted Average']['community_share_percentage'] = (float) ($w_community_share_percentage / $total);
                                    ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Main Chart Container -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="mainInvestmentChart" style="width:100%; height:430px"></div>
            </div>
        </div>
        <script>
            chartData = <?php echo json_encode($chartData); ?>;

            // Extract data
            categories = [];
            kpiaipShares = [];
            communityShares = [];
            communitySharePercentages = [];

            for (region in chartData) {
                categories.push(region);

                const kpiaip = parseFloat(chartData[region]['avg_actual_cost']) || 0;
                const community = parseFloat(chartData[region]['avg_community_share']) || 0;
                const percentage = parseFloat(chartData[region]['community_share_percentage']) || 0;

                kpiaipShares.push(kpiaip);
                communityShares.push(community);
                communitySharePercentages.push(percentage);

            }



            // Render chart
            Highcharts.chart('mainInvestmentChart', {
                chart: {
                    type: 'column',
                },
                title: {
                    text: 'Private Investment - Overall Summary',
                    style: {
                        fontSize: '14px',
                        fontWeight: 'bold'
                    }
                },
                subtitle: {
                    text: 'Overall: KPIAIP: PKR  | Community: PKR  | Share:  %'
                },
                xAxis: {
                    categories: categories,
                    crosshair: true,
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '11px'
                        }
                    }
                },
                yAxis: [{
                    title: {
                        text: 'Amount (PKR)',
                        style: {
                            fontWeight: 'bold',
                            fontSize: '12px'
                        }
                    },
                    labels: {
                        formatter: function() {
                            return this.value.toLocaleString();
                        }
                    }
                }, {
                    title: {
                        text: 'Percentage (%)',
                        style: {
                            fontWeight: 'bold',
                            fontSize: '12px'
                        }
                    },
                    opposite: true,
                    max: 100
                }],
                tooltip: {
                    shared: true,
                    formatter: function() {
                        let s = '<b>' + this.x + '</b>';
                        this.points.forEach(function(point) {
                            if (point.series.name.includes('%')) {
                                s += '<br/>' + point.series.name + ': ' + point.y.toFixed(2) + '%';
                            } else {
                                s += '<br/>' + point.series.name + ': PKR ' + point.y.toLocaleString();
                            }
                        });
                        return s;
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                plotOptions: {
                    column: {
                        grouping: false,
                        shadow: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            format: '{y:,.0f}',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            //rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size

                            }

                        }
                    },
                    spline: {
                        marker: {
                            radius: 3,
                            lineColor: '#666666',
                            lineWidth: 1
                        },
                        dataLabels: {
                            enabled: true,
                            format: '{y:,.0f}%',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            //rotation: -90,
                            style: {
                                fontSize: '7px' // Change to your desired font size
                            }

                        }
                    }
                },
                series: [{
                    name: 'Cost',
                    data: kpiaipShares,
                    color: '#FE7A7B',
                    pointPadding: 0.1,
                    groupPadding: 0.2
                }, {
                    name: 'Community Share',
                    data: communityShares,
                    color: '#7BFF7A',
                    pointPadding: 0.3,
                    groupPadding: 0.2
                }, {
                    name: 'Community Share %',
                    data: communitySharePercentages,
                    type: 'spline',
                    yAxis: 1,
                    color: '#fd7e14',
                    marker: {
                        lineWidth: 2,
                        lineColor: '#fd7e14',
                        fillColor: 'white'
                    }
                }],
                credits: {
                    enabled: false
                }
            });
        </script>



    </div>
</div>


<div class="row">
    <?php
    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();
    ?>
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region and Component Wise Private Investment in PKR (Community Share)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table_medium" id="table_2">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo (1 + count($components)) ?>" class="text-center">Region and Component Wise Private Investment in PKR (Community Share)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Regions</th>
                                <?php foreach ($components as $component) {  ?>
                                    <th colspan="4">Component <?php echo $component->component; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($components as $component) {  ?>
                                    <th><small>Total</small></th>
                                    <th>Cost <br />
                                        <small>Per Scheme (AVG)</small>
                                    </th>
                                    <th>Community Share <br />
                                        <small>Per Scheme (AVG)</small>
                                    </th>
                                    <th>Community Share %</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                            $regions = $this->db->query($query)->result();
                            $chartData = array();
                            $was = array();
                            ?>
                            <?php foreach ($regions as $region) { ?>

                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php foreach ($components as $component) {  ?>
                                        <?php
                                        $query = "SELECT COUNT(*) as total, 
                                    AVG(actual_cost) as avg_actual_cost,
                                    AVG(community_share) as avg_community_share
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND component  = '" . $component->component . "'";
                                        $result = $this->db->query($query)->row();

                                        // Calculate community share percentage
                                        $community_share_percentage = 0;
                                        if ($result->avg_actual_cost > 0) {
                                            $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                                        }
                                        $was[$component->component]['total'] += $result->total;
                                        $was[$component->component]['avg_actual_cost'] += $result->avg_actual_cost * $result->total;
                                        $was[$component->component]['avg_community_share'] += $result->avg_community_share * $result->total;
                                        $was[$component->component]['community_share_percentage'] += $community_share_percentage * $result->total;

                                        $chartData[ucfirst($region->region)][$component->component]['avg_actual_cost'] = (float) $result->avg_actual_cost;
                                        $chartData[ucfirst($region->region)][$component->component]['avg_community_share'] = (float) $result->avg_community_share;
                                        $chartData[ucfirst($region->region)][$component->component]['community_share_percentage'] = (float) $community_share_percentage;
                                        ?>
                                        <td style="text-align: center;"><small><?php echo $result->total; ?></small></td>
                                        <td style="text-align: center;"><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                        <td style="text-align: center;"><?php echo number_format($result->avg_community_share, 2); ?></td>
                                        <th style="text-align: center;"><?php echo number_format($community_share_percentage, 2); ?></th>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($components as $component) {  ?>
                                    <?php
                                    $query = "SELECT COUNT(*) as total, 
                                    AVG(actual_cost) as avg_actual_cost,
                                    AVG(community_share) as avg_community_share
                                    FROM `impact_surveys`
                                    WHERE component  = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();

                                    // Calculate community share percentage
                                    $community_share_percentage = 0;
                                    if ($result->avg_actual_cost > 0) {
                                        $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                                    }
                                    ?>
                                    <td style="text-align: center;"><small><?php echo $result->total; ?></small></td>
                                    <td style="text-align: center;"><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                    <td style="text-align: center;"><?php echo number_format($result->avg_community_share, 2); ?></td>
                                    <th style="text-align: center;"><?php echo number_format($community_share_percentage, 2); ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <?php foreach ($was as $wa) {  ?>
                                    <th style="text-align: center;"><small><?php echo $wa['total']; ?></small></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['avg_actual_cost'] / $wa['total'], 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['avg_community_share'] / $wa['total'], 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['community_share_percentage'] / $wa['total'], 2); ?></th>
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
    $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
    $sub_components = $this->db->query($query)->result();
    ?>
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Regions and Sub Components Wise Private Investment in PKR (Community Share)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table_medium" id="table_3">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo (1 + count($sub_components)) ?>" class="text-center">Regions and Sub Components Wise Private Investment in PKR (Community Share)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Regions</th>
                                <?php foreach ($sub_components as $sub_component) {  ?>
                                    <th colspan="4">Sub Component <?php echo $sub_component->sub_component; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($sub_components as $sub_component) {  ?>
                                    <th><small>Total</small></th>
                                    <th>Cost <br />
                                        <small>Per Scheme (AVG)</small>
                                    </th>
                                    <th>Community Share <br />
                                        <small>Per Scheme (AVG)</small>
                                    </th>
                                    <th>Community Share %</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                            $regions = $this->db->query($query)->result();
                            $chartData = array();
                            $was = array();
                            ?>
                            <?php foreach ($regions as $region) { ?>

                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php foreach ($sub_components as $sub_component) {  ?>
                                        <?php
                                        $query = "SELECT COUNT(*) as total, 
                                    AVG(actual_cost) as avg_actual_cost,
                                    AVG(community_share) as avg_community_share
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND sub_component  = '" . $sub_component->sub_component . "'";
                                        $result = $this->db->query($query)->row();

                                        // Calculate community share percentage
                                        $community_share_percentage = 0;
                                        if ($result->avg_actual_cost > 0) {
                                            $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                                        }
                                        $was[$sub_component->sub_component]['total'] += $result->total;
                                        $was[$sub_component->sub_component]['avg_actual_cost'] += $result->avg_actual_cost * $result->total;
                                        $was[$sub_component->sub_component]['avg_community_share'] += $result->avg_community_share * $result->total;
                                        $was[$sub_component->sub_component]['community_share_percentage'] += $community_share_percentage * $result->total;

                                        $chartData[ucfirst($region->region)][$sub_component->sub_component]['avg_actual_cost'] = (float) $result->avg_actual_cost;
                                        $chartData[ucfirst($region->region)][$sub_component->sub_component]['avg_community_share'] = (float) $result->avg_community_share;
                                        $chartData[ucfirst($region->region)][$sub_component->sub_component]['community_share_percentage'] = (float) $community_share_percentage;
                                        ?>
                                        <td style="text-align: center;"><small><?php echo $result->total; ?></small></td>
                                        <td style="text-align: center;"><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                        <td style="text-align: center;"><?php echo number_format($result->avg_community_share, 2); ?></td>
                                        <th style="text-align: center;"><?php echo number_format($community_share_percentage, 2); ?></th>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($sub_components as $sub_component) {  ?>
                                    <?php
                                    $query = "SELECT COUNT(*) as total, 
                                    AVG(actual_cost) as avg_actual_cost,
                                    AVG(community_share) as avg_community_share
                                    FROM `impact_surveys`
                                    WHERE sub_component  = '" . $sub_component->sub_component . "'";
                                    $result = $this->db->query($query)->row();

                                    // Calculate community share percentage
                                    $community_share_percentage = 0;
                                    if ($result->avg_actual_cost > 0) {
                                        $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                                    }
                                    ?>
                                    <td style="text-align: center;"><small><?php echo $result->total; ?></small></td>
                                    <td style="text-align: center;"><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                    <td style="text-align: center;"><?php echo number_format($result->avg_community_share, 2); ?></td>
                                    <th style="text-align: center;"><?php echo number_format($community_share_percentage, 2); ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <?php foreach ($was as $wa) {  ?>
                                    <th style="text-align: center;"><small><?php echo $wa['total']; ?></small></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['avg_actual_cost'] / $wa['total'], 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['avg_community_share'] / $wa['total'], 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['community_share_percentage'] / $wa['total'], 2); ?></th>
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
    $query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
    $categorys = $this->db->query($query)->result();
    ?>
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Regions and Categories Wise Private Investment in PKR (Community Share)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table_medium" id="table_4">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo (1 + count($categorys)) ?>" class="text-center">Regions and Categories Wise Private Investment in PKR (Community Share)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Regions</th>
                                <?php foreach ($categorys as $category) {  ?>
                                    <th colspan="4">Category <?php echo $category->category; ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <?php foreach ($categorys as $category) {  ?>
                                    <th><small>Total</small></th>
                                    <th>Cost <br />
                                        <small>Per Scheme (AVG)</small>
                                    </th>
                                    <th>Community Share <br />
                                        <small>Per Scheme (AVG)</small>
                                    </th>
                                    <th>Community Share %</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                            $regions = $this->db->query($query)->result();
                            $chartData = array();
                            $was = array();
                            ?>
                            <?php foreach ($regions as $region) { ?>

                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php foreach ($categorys as $category) {  ?>
                                        <?php
                                        $query = "SELECT COUNT(*) as total, 
                                    AVG(actual_cost) as avg_actual_cost,
                                    AVG(community_share) as avg_community_share
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND category  = '" . $category->category . "'";
                                        $result = $this->db->query($query)->row();

                                        // Calculate community share percentage
                                        $community_share_percentage = 0;
                                        if ($result->avg_actual_cost > 0) {
                                            $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                                        }
                                        $was[$category->category]['total'] += $result->total;
                                        $was[$category->category]['avg_actual_cost'] += $result->avg_actual_cost * $result->total;
                                        $was[$category->category]['avg_community_share'] += $result->avg_community_share * $result->total;
                                        $was[$category->category]['community_share_percentage'] += $community_share_percentage * $result->total;

                                        $chartData[ucfirst($region->region)][$category->category]['avg_actual_cost'] = (float) $result->avg_actual_cost;
                                        $chartData[ucfirst($region->region)][$category->category]['avg_community_share'] = (float) $result->avg_community_share;
                                        $chartData[ucfirst($region->region)][$category->category]['community_share_percentage'] = (float) $community_share_percentage;
                                        ?>
                                        <td style="text-align: center;"><small><?php echo $result->total; ?></small></td>
                                        <td style="text-align: center;"><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                        <td style="text-align: center;"><?php echo number_format($result->avg_community_share, 2); ?></td>
                                        <th style="text-align: center;"><?php echo number_format($community_share_percentage, 2); ?></th>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($categorys as $category) {  ?>
                                    <?php
                                    $query = "SELECT COUNT(*) as total, 
                                    AVG(actual_cost) as avg_actual_cost,
                                    AVG(community_share) as avg_community_share
                                    FROM `impact_surveys`
                                    WHERE category  = '" . $category->category . "'";
                                    $result = $this->db->query($query)->row();

                                    // Calculate community share percentage
                                    $community_share_percentage = 0;
                                    if ($result->avg_actual_cost > 0) {
                                        $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                                    }
                                    ?>
                                    <td style="text-align: center;"><small><?php echo $result->total; ?></small></td>
                                    <td style="text-align: center;"><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                                    <td style="text-align: center;"><?php echo number_format($result->avg_community_share, 2); ?></td>
                                    <th style="text-align: center;"><?php echo number_format($community_share_percentage, 2); ?></th>
                                <?php } ?>
                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <?php foreach ($was as $wa) {  ?>
                                    <th style="text-align: center;"><small><?php echo $wa['total']; ?></small></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['avg_actual_cost'] / $wa['total'], 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['avg_community_share'] / $wa['total'], 2); ?></th>
                                    <th style="text-align: center;"><?php echo number_format($wa['community_share_percentage'] / $wa['total'], 2); ?></th>
                                <?php } ?>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>