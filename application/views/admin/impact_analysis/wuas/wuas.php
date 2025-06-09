<h4></h4>


<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis: Water Users Assosiations</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">

        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/WUA_Members'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data WUA Members</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard' ], ['Summary', 'Crop & Component Wise' , 'wheat', 'maize' , 'maize_hybrid' , 'sugarcane' , 'fodder' , 'vegetable' , 'fruit_orchard'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>WUA AVG. Members <small>Per Scheme</small></strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">

                    <table class="table table-bordered">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="2">WUA AVG. Members <small>Per Scheme</small></th>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "
                                SELECT 
                                    ROUND(AVG(wua_members), 2) AS wua_members,
                                    ROUND(AVG(male_members), 2) AS male_members,
                                    ROUND(AVG(female_members), 2) AS female_members,
                                    ROUND(AVG(male_members) / (AVG(male_members) + AVG(female_members)) * 100, 2) AS male_percentage,
                                    ROUND(AVG(female_members) / (AVG(male_members) + AVG(female_members)) * 100, 2) AS female_percentage
                                FROM impact_surveys AS s;
                            ";
                            $wua = $this->db->query($query)->row();

                            // Store values for Highcharts
                            $wua_members[] = $wua->wua_members;
                            $male_members[] = $wua->male_members;
                            $female_members[] = $wua->female_members;
                            ?>
                            <tr>
                                <td>Average Members</td>
                                <td><?php echo $wua->wua_members; ?></td>
                            </tr>
                            <tr>
                                <td>Male Members (Avg.)</td>
                                <td><?php echo $wua->male_members; ?></td>
                            </tr>
                            <tr>
                                <td>Female Members (Avg.)</td>
                                <td><?php echo $wua->female_members; ?></td>
                            </tr>
                            <tr>
                                <td>Male Members (%)</td>
                                <td><?php echo round($wua->male_percentage, 2) . "%"; ?></td>
                            </tr>
                            <tr>
                                <td>Female Members (%)</td>
                                <td><?php echo round($wua->female_percentage, 2) . "%"; ?></td>
                            </tr>
                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="wua_members_chart" style="width: 100%;  height: 330px; margin: 0 auto;"></div>
            </div>
        </div>
        <script>
            Highcharts.chart('wua_members_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'WUA Members'
                },
                xAxis: {
                    categories: 'WUA Average Members',
                    title: {
                        text: 'Average Members'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '(Avg.)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ''
                },
                plotOptions: {
                    column: {
                        shadow: false,
                        borderWidth: 0,
                        dataLabels: {
                            enabled: true,
                            //format: '{y:,.000f}',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            //rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size

                            }

                        }
                    }
                },
                series: [{
                        name: 'AVG. Members',
                        data: <?php echo json_encode($wua_members, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        name: 'Males',
                        color: '#00A2D6',
                        data: <?php echo json_encode($male_members, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        name: 'Females',
                        color: '#EF3DBB',
                        data: <?php echo json_encode($female_members, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
        </script>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="members-pie" style="width: 100%;  height: 330px; margin: 0 auto;"></div>
            </div>
        </div>
        <script type="text/javascript">
            Highcharts.chart('members-pie', {
                chart: {
                    type: 'pie'
                },
                title: {
                    text: 'WUA Member Gender Distribution'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.2f}%</b>'
                },
                accessibility: {
                    point: {
                        valueSuffix: '%'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.percentage:.2f} %'
                        }
                    }
                },
                series: [{
                    name: 'Members',
                    colorByPoint: true,
                    data: [{
                        name: 'Male',
                        color: '#00A2D6',
                        y: <?php echo $wua->male_percentage; ?>
                    }, {
                        name: 'Female',
                        color: '#EF3DBB',
                        y: <?php echo $wua->female_percentage; ?>
                    }]
                }]
            });
        </script>

    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region Wise Water User Assosiation Members Summary <small>Per Scheme</small></strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr style="display:none">
                                <th colspan="7">
                                    Region Wise Water User Assosiation Members Summary <small>Per Scheme</small>
                                </th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th><small>Total</small></th>
                                <th>Average Members</th>
                                <th>Male Members (Avg.)</th>
                                <th>Female Members (Avg.)</th>
                                <th>Male Members (%)</th>
                                <th>Female Members (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $wa = array();
                            $query = "SELECT `region` FROM `impact_surveys` 
                            GROUP BY `region` ORDER BY `region` ASC";
                            $regions = $this->db->query($query)->result();

                            foreach ($regions as $region) {

                                $query = "SELECT COUNT(*) as total, 
                                    ROUND(AVG(wua_members), 2) AS wua_members,
                                    ROUND(AVG(male_members), 2) AS male_members,
                                    ROUND(AVG(female_members), 2) AS female_members,
                                    ROUND(AVG(male_members) / (AVG(male_members) + AVG(female_members)) * 100, 2) AS male_percentage,
                                    ROUND(AVG(female_members) / (AVG(male_members) + AVG(female_members)) * 100, 2) AS female_percentage
                                FROM impact_surveys AS s
                                WHERE region = ?;";
                                $wua = $this->db->query($query, [$region->region])->row();
                                $wa['total'] += $wua->total;
                                $wa['wua_members'] += $wua->wua_members * $wua->total;
                                $wa['male_members'] += $wua->male_members * $wua->total;
                                $wa['female_members'] += $wua->female_members * $wua->total;
                                $wa['male_percentage'] += $wua->male_percentage * $wua->total;
                                $wa['female_percentage'] += $wua->female_percentage * $wua->total;


                            ?>
                                <tr>
                                    <td><?php echo ucfirst($region->region); ?></td>
                                    <td><small><?php echo $wua->total; ?></small></td>
                                    <td><?php echo $wua->wua_members; ?></td>
                                    <td><?php echo $wua->male_members; ?></td>
                                    <td><?php echo $wua->female_members; ?></td>
                                    <td><?php echo round($wua->male_percentage, 2) . "%"; ?></td>
                                    <td><?php echo round($wua->female_percentage, 2) . "%"; ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>Weighted Average</td>
                                <td><small><?php echo $wa['total']; ?></small></td>
                                <td><?php echo round($wa['wua_members'] / $wa['total'], 2); ?></td>
                                <td><?php echo round($wa['male_members'] / $wa['total'], 2); ?></td>
                                <td><?php echo round($wa['female_members'] / $wa['total'], 2); ?></td>
                                <td><?php echo round($wa['male_percentage'] / $wa['total'], 2); ?></td>
                                <td><?php echo round($wa['female_percentage'] / $wa['total'], 2); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>