<h4>Private Investment in PKR (Community Share)</h4>
<hr />
<div class="row">
    <div class="col-md-6">
        <table class="table    table-bordered table-striped">
            <thead>
                <tr>
                    <th colspan="13" class="text-center">Private Investment in PKR (Community Share)</th>
                </tr>
                <tr>
                    <th>Regions</th>
                    <th>KPIAIP Share Per Scheme (Average)</th>
                    <th>Community Share Per Scheme (Average)</th>
                    <th>Community Share %</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                $regions = $this->db->query($query)->result();

                ?>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo $region->region; ?></th>
                        <?php
                        $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`
                            WHERE region = '" . $region->region . "'";
                        $result = $this->db->query($query)->row();

                        // Calculate community share percentage
                        $community_share_percentage = 0;
                        if ($result->avg_actual_cost > 0) {
                            $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                        }
                        ?>
                        <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                        <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                        <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Overall</th>
                    <?php
                    $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`";
                    $result = $this->db->query($query)->row();

                    // Calculate community share percentage
                    $community_share_percentage = 0;
                    if ($result->avg_actual_cost > 0) {
                        $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                    }
                    ?>
                    <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                    <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                    <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <!-- Main Chart Container -->
    <div class="col-md-6">
        <div id="mainInvestmentChart" style="width:100%; height:500px; margin-bottom:30px;"></div>
    </div>
    <?php
    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();
    foreach ($components as $component) {  ?>
        <div class="col-md-6">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="13" class="text-center">Private Investment in PKR (Community Share) <br />
                            Component <?php echo $component->component; ?>
                        </th>
                    </tr>
                    <tr>
                        <th>Regions</th>
                        <th>KPIAIP Share Per Scheme (Average)</th>
                        <th>Community Share Per Scheme (Average)</th>
                        <th>Community Share %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                    $regions = $this->db->query($query)->result();

                    ?>
                    <?php foreach ($regions as $region) { ?>
                        <tr>
                            <th><?php echo $region->region; ?></th>
                            <?php
                            $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`
                            WHERE region = '" . $region->region . "'
                            AND component = '" . $component->component . "'";
                            $result = $this->db->query($query)->row();

                            // Calculate community share percentage
                            $community_share_percentage = 0;
                            if ($result->avg_actual_cost > 0) {
                                $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                            }
                            ?>
                            <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                            <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                            <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Overall</th>
                        <?php
                        $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`
                            WHERE component = '" . $component->component . "'";
                        $result = $this->db->query($query)->row();

                        // Calculate community share percentage
                        $community_share_percentage = 0;
                        if ($result->avg_actual_cost > 0) {
                            $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                        }
                        ?>
                        <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                        <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                        <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>
</div>
<div class="row">


    <!-- Component Charts Containers -->
    <?php foreach ($components as $component) { ?>
        <div class="col-md-6">
            <div id="componentChart_<?php echo $component->component; ?>" style="width:100%; height:400px; margin-bottom:20px;"></div>
        </div>
    <?php } ?>
</div>

<script>
    $(document).ready(function() {
        // 1. Extract data for the main chart (all regions)
        var mainChartData = {
            regions: [],
            kpiaipShares: [],
            communityShares: [],
            sharePercentages: []
        };

        <?php
        $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();

        foreach ($regions as $region) {
            $query = "SELECT 
            AVG(actual_cost) as avg_actual_cost,
            AVG(community_share) as avg_community_share
            FROM `impact_surveys`
            WHERE region = '" . $region->region . "'";
            $result = $this->db->query($query)->row();
            $community_share_percentage = ($result->avg_actual_cost > 0) ?
                ($result->avg_community_share / $result->avg_actual_cost) * 100 : 0;
        ?>
            mainChartData.regions.push("<?php echo $region->region; ?>");
            mainChartData.kpiaipShares.push(<?php echo number_format($result->avg_actual_cost, 2, '.', ''); ?>);
            mainChartData.communityShares.push(<?php echo number_format($result->avg_community_share, 2, '.', ''); ?>);
            mainChartData.sharePercentages.push(<?php echo number_format($community_share_percentage, 2, '.', ''); ?>);
        <?php } ?>

        <?php
        // Overall data for the main chart
        $query = "SELECT 
        AVG(actual_cost) as avg_actual_cost,
        AVG(community_share) as avg_community_share
        FROM `impact_surveys`";
        $result = $this->db->query($query)->row();
        $community_share_percentage = ($result->avg_actual_cost > 0) ?
            ($result->avg_community_share / $result->avg_actual_cost) * 100 : 0;
        ?>
        mainChartData.overall = {
            kpiaip: <?php echo number_format($result->avg_actual_cost, 2, '.', ''); ?>,
            community: <?php echo number_format($result->avg_community_share, 2, '.', ''); ?>,
            percentage: <?php echo number_format($community_share_percentage, 2, '.', ''); ?>
        };

        // 2. Create the main chart
        Highcharts.chart('mainInvestmentChart', {
            chart: {
                type: 'column',
                backgroundColor: '#f9f9f9',
                borderWidth: 1,
                borderColor: '#ddd'
            },
            title: {
                text: 'Private Investment in PKR (Community Share) - All Regions',
                style: {
                    fontSize: '16px',
                    fontWeight: 'bold'
                }
            },
            subtitle: {
                text: 'Overall Average: KPIAIP: PKR ' + mainChartData.overall.kpiaip.toLocaleString() +
                    ' | Community: PKR ' + mainChartData.overall.community.toLocaleString() +
                    ' | Share: ' + mainChartData.overall.percentage.toFixed(2) + '%'
            },
            xAxis: {
                categories: mainChartData.regions,
                crosshair: true,
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '12px'
                    }
                }
            },
            yAxis: [{
                title: {
                    text: 'Amount (PKR)',
                    style: {
                        fontWeight: 'bold'
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
                        fontWeight: 'bold'
                    }
                },
                opposite: true,
                max: 100
            }],
            tooltip: {
                shared: true,
                formatter: function() {
                    var s = '<b>' + this.x + '</b>';
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
                        enabled: false
                    }
                },
                spline: {
                    marker: {
                        radius: 4,
                        lineColor: '#666666',
                        lineWidth: 1
                    }
                }
            },
            series: [{
                name: 'KPIAIP Share',
                data: mainChartData.kpiaipShares,
                color: '#2f7ed8',
                pointPadding: 0.1,
                groupPadding: 0.2
            }, {
                name: 'Community Share',
                data: mainChartData.communityShares,
                color: '#0d233a',
                pointPadding: 0.3,
                groupPadding: 0.2
            }, {
                name: 'Community Share %',
                data: mainChartData.sharePercentages,
                type: 'spline',
                yAxis: 1,
                color: '#8bbc21',
                marker: {
                    lineWidth: 2,
                    lineColor: '#8bbc21',
                    fillColor: 'white'
                },
                dataLabels: {
                    enabled: true,
                    formatter: function() {
                        return this.y.toFixed(1) + '%';
                    },
                    style: {
                        fontWeight: 'bold'
                    }
                }
            }],
            credits: {
                enabled: false
            }
        });

        // 3. Extract data for component charts
        var componentChartsData = {};
        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        foreach ($components as $component) {
        ?>
            componentChartsData['component_<?php echo $component->component; ?>'] = {
                regions: [],
                kpiaipShares: [],
                communityShares: [],
                sharePercentages: []
            };

            <?php
            $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
            $regions = $this->db->query($query)->result();

            foreach ($regions as $region) {
                $query = "SELECT 
                AVG(actual_cost) as avg_actual_cost,
                AVG(community_share) as avg_community_share
                FROM `impact_surveys`
                WHERE region = '" . $region->region . "'
                AND component = '" . $component->component . "'";
                $result = $this->db->query($query)->row();
                $community_share_percentage = ($result->avg_actual_cost > 0) ?
                    ($result->avg_community_share / $result->avg_actual_cost) * 100 : 0;
            ?>
                componentChartsData['component_<?php echo $component->component; ?>'].regions.push("<?php echo $region->region; ?>");
                componentChartsData['component_<?php echo $component->component; ?>'].kpiaipShares.push(<?php echo number_format($result->avg_actual_cost, 2, '.', ''); ?>);
                componentChartsData['component_<?php echo $component->component; ?>'].communityShares.push(<?php echo number_format($result->avg_community_share, 2, '.', ''); ?>);
                componentChartsData['component_<?php echo $component->component; ?>'].sharePercentages.push(<?php echo number_format($community_share_percentage, 2, '.', ''); ?>);
            <?php } ?>

            <?php
            // Overall for component
            $query = "SELECT 
            AVG(actual_cost) as avg_actual_cost,
            AVG(community_share) as avg_community_share
            FROM `impact_surveys`
            WHERE component = '" . $component->component . "'";
            $result = $this->db->query($query)->row();
            $community_share_percentage = ($result->avg_actual_cost > 0) ?
                ($result->avg_community_share / $result->avg_actual_cost) * 100 : 0;
            ?>
            componentChartsData['component_<?php echo $component->component; ?>'].overall = {
                kpiaip: <?php echo number_format($result->avg_actual_cost, 2, '.', ''); ?>,
                community: <?php echo number_format($result->avg_community_share, 2, '.', ''); ?>,
                percentage: <?php echo number_format($community_share_percentage, 2, '.', ''); ?>
            };
        <?php } ?>

        // 4. Create charts for each component
        for (var componentKey in componentChartsData) {
            if (componentChartsData.hasOwnProperty(componentKey)) {
                var componentNum = componentKey.split('_')[1];
                var componentData = componentChartsData[componentKey];

                Highcharts.chart('componentChart_' + componentNum, {
                    chart: {
                        type: 'column',
                        backgroundColor: '#f9f9f9',
                        borderWidth: 1,
                        borderColor: '#ddd'
                    },
                    title: {
                        text: 'Private Investment - Component ' + componentNum,
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold'
                        }
                    },
                    subtitle: {
                        text: 'Overall: KPIAIP: PKR ' + componentData.overall.kpiaip.toLocaleString() +
                            ' | Community: PKR ' + componentData.overall.community.toLocaleString() +
                            ' | Share: ' + componentData.overall.percentage.toFixed(2) + '%'
                    },
                    xAxis: {
                        categories: componentData.regions,
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
                            var s = '<b>' + this.x + '</b>';
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
                            borderWidth: 0
                        },
                        spline: {
                            marker: {
                                radius: 3,
                                lineColor: '#666666',
                                lineWidth: 1
                            }
                        }
                    },
                    series: [{
                        name: 'KPIAIP Share',
                        data: componentData.kpiaipShares,
                        color: '#2f7ed8',
                        pointPadding: 0.1,
                        groupPadding: 0.2
                    }, {
                        name: 'Community Share',
                        data: componentData.communityShares,
                        color: '#0d233a',
                        pointPadding: 0.3,
                        groupPadding: 0.2
                    }, {
                        name: 'Community Share %',
                        data: componentData.sharePercentages,
                        type: 'spline',
                        yAxis: 1,
                        color: '#8bbc21',
                        marker: {
                            lineWidth: 2,
                            lineColor: '#8bbc21',
                            fillColor: 'white'
                        }
                    }],
                    credits: {
                        enabled: false
                    }
                });
            }
        }
    });
</script>