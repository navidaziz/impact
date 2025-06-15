<div class="row">
    <div class="col-md-6">
        <h4>Impact Analysis on Water Productivity of (Wheat and Maize)</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Water_Productivity_Raw'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Water_Productivity_Compiled'); ?>" class="btn btn-warning btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Complied Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Water_Productivity',['table_1', 'table_2', 'table_3', 'table_4' , 'table_5' , 'table_6' , 'table_7' ], ['Component Summary', 'Sub Component Summary' , 'Category Summary', 'Region Wise Summary' , 'Region and Component' , 'Region and Sub Component' , 'Region and Category'])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>
</div>
<hr />

<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $wheat_wp = [];
        $maize_wp = [];

        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Components Wise Water Productivity (Wheat & Maize)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_1">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="4">Components Wise Water Productivity (Wheat & Maize)</th>
                            </tr>
                            <tr>
                                <th>Components</th>
                                <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($components as $component) {
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                        ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                        FROM `impact_survery_water_productivity` as wp
                                        WHERE wp.component = '" . $component->component . "'";
                                $water_productivity = $this->db->query($query)->row();

                                // Store values for Highcharts
                                $categories[] = $component->component;
                                $wheat_wp[] = $water_productivity->wheat_wp;
                                $maize_wp[] = $water_productivity->maize_wp;
                            ?>
                                <tr>
                                    <th><?php echo $component->component; ?></th>
                                    <td><?php echo $water_productivity->wheat_wp; ?></td>
                                    <td><?php echo $water_productivity->maize_wp; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <?php
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                        ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                        FROM `impact_survery_water_productivity` as wp";
                                $wp_total = $this->db->query($query)->row();
                                ?>
                            <tr>
                                <th>Average</th>
                                <td><?php echo $wp_total->wheat_wp; ?></td>
                                <td><?php echo $wp_total->maize_wp; ?></td>
                            </tr>

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
                <div id="component_wp_chart" style="height: 300px;"></div>
            </div>
        </div>
        <script>
            Highcharts.chart('component_wp_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Components Wise Water Productivity (Wheat & Maize)'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'WP Kg/m<sup>3</sup> (Avg.)'
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
                            format: '{y} %',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size
                            }

                        }
                    }

                },
                series: [{
                    name: 'Wheat',
                    data: <?php echo json_encode($wheat_wp, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Maize',
                    data: <?php echo json_encode($maize_wp, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="sub_component_wp_chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Sub Components Wise Water Productivity (Wheat & Maize)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">

                    <?php
                    $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
                    $sub_components = $this->db->query($query)->result();

                    // Initialize arrays for Highcharts data
                    $categories = [];
                    $wheat_wp = [];
                    $maize_wp = [];

                    ?>
                    <table class="table table-bordered table-striped" id="table_2">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="4">Sub Components Wise Water Productivity (Wheat & Maize)</th>
                            </tr>

                            <tr>
                                <th>Sub Components</th>
                                <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sub_components as $sub_component) {
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp
                                    WHERE wp.sub_component = '" . $sub_component->sub_component . "'";
                                $water_productivity = $this->db->query($query)->row();

                                // Store values for Highcharts
                                $categories[] = $sub_component->sub_component;
                                $wheat_wp[] = $water_productivity->wheat_wp;
                                $maize_wp[] = $water_productivity->maize_wp;
                            ?>
                                <tr>
                                    <th><?php echo $sub_component->sub_component; ?></th>
                                    <td><?php echo $water_productivity->wheat_wp; ?></td>
                                    <td><?php echo $water_productivity->maize_wp; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <?php
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp";
                                $wp_total = $this->db->query($query)->row();
                                ?>
                            <tr>
                                <th>Average</th>
                                <td><?php echo $wp_total->wheat_wp; ?></td>
                                <td><?php echo $wp_total->maize_wp; ?></td>
                            </tr>

                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        Highcharts.chart('sub_component_wp_chart', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Sub Components Wise Water Productivity (Wheat & Maize)'
            },
            xAxis: {
                categories: <?php echo json_encode($categories); ?>,
                title: {
                    text: 'sub_components'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'WP Kg/m<sup>3</sup> (Avg.)'
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
                        format: '{y} %',
                        crop: false, // Don't hide labels outside the plot area
                        overflow: 'none', // Prevent hiding when overflowing
                        allowOverlap: true,
                        rotation: -90,
                        style: {
                            fontSize: '9px' // Change to your desired font size
                        }

                    }
                }

            },
            series: [{
                name: 'Wheat',
                data: <?php echo json_encode($wheat_wp, JSON_NUMERIC_CHECK); ?>
            }, {
                name: 'Maize',
                data: <?php echo json_encode($maize_wp, JSON_NUMERIC_CHECK); ?>
            }]
        });
    </script>

</div>


<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $com_categories = $this->db->query($query)->result();
        // Initialize arrays for Highcharts data
        $categories = [];
        $wheat_wp = [];
        $maize_wp = [];

        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Categories Wise Water Productivity (Wheat & Maize)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_3">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="4">Categories Wise Water Productivity (Wheat & Maize)</th>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($com_categories as $category) {
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                            ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                            FROM `impact_survery_water_productivity` as wp
                                            WHERE wp.category = '" . $category->category . "'";
                                $water_productivity = $this->db->query($query)->row();

                                // Store values for Highcharts
                                $categories[] = $category->category;
                                $wheat_wp[] = $water_productivity->wheat_wp;
                                $maize_wp[] = $water_productivity->maize_wp;
                            ?>
                                <tr>
                                    <th><?php echo $category->category; ?></th>
                                    <td><?php echo $water_productivity->wheat_wp; ?></td>
                                    <td><?php echo $water_productivity->maize_wp; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <?php
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                FROM `impact_survery_water_productivity` as wp";
                                $wp_total = $this->db->query($query)->row();
                                ?>
                            <tr>
                                <th>Average</th>
                                <td><?php echo $wp_total->wheat_wp; ?></td>
                                <td><?php echo $wp_total->maize_wp; ?></td>
                            </tr>

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
                <div id="category_wp_chart"></div>
            </div>
        </div>
        <script>
            Highcharts.chart('category_wp_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Category Wise Water Productivity (Wheat & Maize)'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'categories'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'WP Kg/m<sup>3</sup> (Avg.)'
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
                            format: '{y} %',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size
                            }

                        }
                    }

                },
                series: [{
                    name: 'Wheat',
                    data: <?php echo json_encode($wheat_wp, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Maize',
                    data: <?php echo json_encode($maize_wp, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>

    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <div id="region_wp_chart"></div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
        $all_regions = $this->db->query($query)->result();
        // Initialize arrays for Highcharts data
        $categories = [];
        $wheat_wp = [];
        $maize_wp = [];

        ?>
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Regions Wise Water Productivity (Wheat & Maize)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table_4">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="4">Regions Wise Water Productivity (Wheat & Maize)</th>
                            </tr>
                            <tr>
                                <th>Region</th>
                                <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_regions as $region) {
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                FROM `impact_survery_water_productivity` as wp
                                WHERE wp.region = '" . $region->region . "'";
                                $water_productivity = $this->db->query($query)->row();

                                // Store values for Highcharts
                                $categories[] = ucwords($region->region);
                                $wheat_wp[] = round($water_productivity->wheat_wp, 2);
                                $maize_wp[] = round($water_productivity->maize_wp, 2);
                            ?>
                                <tr>
                                    <th><?php echo ucwords($region->region); ?></th>
                                    <td><?php echo $water_productivity->wheat_wp; ?></td>
                                    <td><?php echo $water_productivity->maize_wp; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <?php
                                $query = "SELECT ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp";
                                $wp_total = $this->db->query($query)->row();
                                ?>
                            <tr>
                                <th>Average</th>
                                <td><?php echo $wp_total->wheat_wp; ?></td>
                                <td><?php echo $wp_total->maize_wp; ?></td>
                            </tr>

                            </tr>
                        </tfoot>

                    </table>
                </div>
            </div>
        </div>

        <script>
            Highcharts.chart('region_wp_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Region Wise Water Productivity (Wheat & Maize)'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'Regions'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'WP Kg/m<sup>3</sup> (Avg.)'
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
                            format: '{y} %',
                            crop: false, // Don't hide labels outside the plot area
                            overflow: 'none', // Prevent hiding when overflowing
                            allowOverlap: true,
                            rotation: -90,
                            style: {
                                fontSize: '9px' // Change to your desired font size
                            }

                        }
                    }

                },
                series: [{
                    name: 'Wheat',
                    data: <?php echo json_encode($wheat_wp, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Maize',
                    data: <?php echo json_encode($maize_wp, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>

    </div>
</div>
<?php
$query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
$regions = $this->db->query($query)->result();
?>
<?php
$query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
$components = $this->db->query($query)->result();

?>
<div class="row">
    <div class="col-md-12" style="margin-bottom: 10px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region and Component Wise Water Productivity (Wheat & Maize) </strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table_medium" id="table_5">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo ((1 + (count($components) * 5)) + 5); ?>">Region and Component Wise Water Productivity (Wheat & Maize)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Regions</th>
                                <?php foreach ($components as $component) { ?>
                                    <th colspan="3">Component <?php echo $component->component; ?></th>
                                <?php } ?>
                                <th colspan="3">Cumulative</th>
                            </tr>
                            <tr>

                                <?php foreach ($components as $component) { ?>
                                    <th>Total </th>
                                    <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                    <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <?php } ?>
                                <th>Total </th>
                                <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $weighted_average = array();
                            foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php foreach ($components as $component) {


                                        $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE wp.component = ?
                                    AND wp.region = ? ";
                                        $result = $this->db->query($query, [$component->component, $region->region])->row();
                                    ?>
                                        <td style="text-align: center;"><?php echo $result->total; ?></td>
                                        <td style="text-align: center;"><?php echo $result->wheat_wp; ?></td>
                                        <td style="text-align: center;"><?php echo $result->maize_wp; ?></td>
                                    <?php
                                        $weighted_average[$component->component]['total'] += $result->total;
                                        $weighted_average[$component->component]['wheat_wp'] += $result->wheat_wp * $result->total;
                                        $weighted_average[$component->component]['maize_wp'] += $result->maize_wp * $result->total;
                                        $total += $result->total;
                                    } ?>

                                    <?php $query = "SELECT  COUNT(*) as total,
                                    ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE  wp.region = ? ";
                                    $result = $this->db->query($query, [$region->region])->row();
                                    $weighted_average['commulative']['total'] += $result->total;
                                    $weighted_average['commulative']['wheat_wp'] += $result->wheat_wp * $result->total;
                                    $weighted_average['commulative']['maize_wp'] += $result->maize_wp * $result->total;
                                    $total += $result->total;
                                    ?>
                                    <td style="text-align: center;"><?php echo $result->total; ?></td>
                                    <td style="text-align: center;"><?php echo $result->wheat_wp; ?></td>
                                    <td style="text-align: center;"><?php echo $result->maize_wp; ?></td>
                                </tr>
                            <?php

                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($components as $component) {
                                    $query = "SELECT  COUNT(*) as total,
                                     ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE wp.component = ? ";
                                    $result = $this->db->query($query, [$component->component])->row();
                                ?>
                                    <th style="text-align: center;"><?php echo $result->total; ?></th>
                                    <th style="text-align: center;"><?php echo $result->wheat_wp; ?></th>
                                    <th style="text-align: center;"><?php echo $result->maize_wp; ?></th>
                                <?php } ?>
                                <?php $query = "SELECT  COUNT(*) as total,
                                     ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp ";
                                $result = $this->db->query($query)->row();
                                ?>
                                <th style="text-align: center;"><?php echo $result->total; ?></th>
                                <th style="text-align: center;"><?php echo $result->wheat_wp; ?></th>
                                <th style="text-align: center;"><?php echo $result->maize_wp; ?></th>

                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <?php
                                foreach ($weighted_average as $key => $value) { ?>
                                    <th style="text-align: center;"><?php echo $value['total']; ?></th>
                                    <th style="text-align: center;"><?php echo round($value['wheat_wp'] / ($value['total']), 2); ?></th>
                                    <th style="text-align: center;"><?php echo round($value['maize_wp'] / ($value['total']), 2); ?></th>
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
$query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
$sub_components = $this->db->query($query)->result();
?>
<div class="row">
    <div class="col-md-12" style="margin-bottom: 10px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region and Sub Component Wise Water Productivity (Wheat & Maize)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table_medium" id="table_6">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo ((1 + (count($sub_components) * 5)) + 5); ?>">Region and Sub Components WiseWater Productivity (Wheat & Maize)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Regions</th>
                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th colspan="3">Sub Component <?php echo $sub_component->sub_component; ?></th>
                                <?php } ?>
                                <th colspan="3">Cumulative</th>
                            </tr>
                            <tr>

                                <?php foreach ($sub_components as $sub_component) { ?>
                                    <th>Total </th>
                                    <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                    <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <?php } ?>
                                <th>Total </th>
                                <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $weighted_average = array();
                            foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php foreach ($sub_components as $sub_component) {


                                        $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE wp.sub_component = ?
                                    AND wp.region = ? ";
                                        $result = $this->db->query($query, [$sub_component->sub_component, $region->region])->row();
                                    ?>
                                        <td style="text-align: center;"><?php echo $result->total; ?></td>
                                        <td style="text-align: center;"><?php echo $result->wheat_wp; ?></td>
                                        <td style="text-align: center;"><?php echo $result->maize_wp; ?></td>
                                    <?php
                                        $weighted_average[$sub_component->sub_component]['total'] += $result->total;
                                        $weighted_average[$sub_component->sub_component]['wheat_wp'] += $result->wheat_wp * $result->total;
                                        $weighted_average[$sub_component->sub_component]['maize_wp'] += $result->maize_wp * $result->total;
                                        $total += $result->total;
                                    } ?>

                                    <?php $query = "SELECT  COUNT(*) as total,
                                    ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE  wp.region = ? ";
                                    $result = $this->db->query($query, [$region->region])->row();
                                    $weighted_average['commulative']['total'] += $result->total;
                                    $weighted_average['commulative']['wheat_wp'] += $result->wheat_wp * $result->total;
                                    $weighted_average['commulative']['maize_wp'] += $result->maize_wp * $result->total;
                                    $total += $result->total;
                                    ?>
                                    <td style="text-align: center;"><?php echo $result->total; ?></td>
                                    <td style="text-align: center;"><?php echo $result->wheat_wp; ?></td>
                                    <td style="text-align: center;"><?php echo $result->maize_wp; ?></td>
                                </tr>
                            <?php

                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($sub_components as $sub_component) {
                                    $query = "SELECT  COUNT(*) as total,
                                     ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE wp.sub_component = ? ";
                                    $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                ?>
                                    <th style="text-align: center;"><?php echo $result->total; ?></th>
                                    <th style="text-align: center;"><?php echo $result->wheat_wp; ?></th>
                                    <th style="text-align: center;"><?php echo $result->maize_wp; ?></th>
                                <?php } ?>
                                <?php $query = "SELECT  COUNT(*) as total,
                                     ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp ";
                                $result = $this->db->query($query)->row();
                                ?>
                                <th style="text-align: center;"><?php echo $result->total; ?></th>
                                <th style="text-align: center;"><?php echo $result->wheat_wp; ?></th>
                                <th style="text-align: center;"><?php echo $result->maize_wp; ?></th>

                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <?php
                                foreach ($weighted_average as $key => $value) { ?>
                                    <th style="text-align: center;"><?php echo $value['total']; ?></th>
                                    <th style="text-align: center;"><?php echo round($value['wheat_wp'] / ($value['total']), 2); ?></th>
                                    <th style="text-align: center;"><?php echo round($value['maize_wp'] / ($value['total']), 2); ?></th>
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
$query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
$categorys = $this->db->query($query)->result();
?>
<div class="row">
    <div class="col-md-12" style="margin-bottom: 10px;">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Region and Category Wise Water Productivity (Wheat & Maize)</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table_medium" id="table_7">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="<?php echo ((1 + (count($categorys) * 5)) + 5); ?>">Region and Category WiseWater Productivity (Wheat & Maize)</th>
                            </tr>
                            <tr>
                                <th rowspan="2">Regions</th>
                                <?php foreach ($categorys as $category) { ?>
                                    <th colspan="3">Category <?php echo $category->category; ?></th>
                                <?php } ?>
                                <th colspan="3">Cumulative</th>
                            </tr>
                            <tr>

                                <?php foreach ($categorys as $category) { ?>
                                    <th>Total </th>
                                    <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                    <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <?php } ?>
                                <th>Total </th>
                                <th>Wheat <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                                <th>Maize <small>Kg/m<sup>3</sup> (Avg.)</small></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $weighted_average = array();
                            foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region) ?></th>
                                    <?php foreach ($categorys as $category) {


                                        $query = "SELECT 
                                    COUNT(*) as total,
                                    ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE wp.category = ?
                                    AND wp.region = ? ";
                                        $result = $this->db->query($query, [$category->category, $region->region])->row();
                                    ?>
                                        <td style="text-align: center;"><?php echo $result->total; ?></td>
                                        <td style="text-align: center;"><?php echo $result->wheat_wp; ?></td>
                                        <td style="text-align: center;"><?php echo $result->maize_wp; ?></td>
                                    <?php
                                        $weighted_average[$category->category]['total'] += $result->total;
                                        $weighted_average[$category->category]['wheat_wp'] += $result->wheat_wp * $result->total;
                                        $weighted_average[$category->category]['maize_wp'] += $result->maize_wp * $result->total;
                                        $total += $result->total;
                                    } ?>

                                    <?php $query = "SELECT  COUNT(*) as total,
                                    ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE  wp.region = ? ";
                                    $result = $this->db->query($query, [$region->region])->row();
                                    $weighted_average['commulative']['total'] += $result->total;
                                    $weighted_average['commulative']['wheat_wp'] += $result->wheat_wp * $result->total;
                                    $weighted_average['commulative']['maize_wp'] += $result->maize_wp * $result->total;
                                    $total += $result->total;
                                    ?>
                                    <td style="text-align: center;"><?php echo $result->total; ?></td>
                                    <td style="text-align: center;"><?php echo $result->wheat_wp; ?></td>
                                    <td style="text-align: center;"><?php echo $result->maize_wp; ?></td>
                                </tr>
                            <?php

                            } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Average</th>
                                <?php foreach ($categorys as $category) {
                                    $query = "SELECT  COUNT(*) as total,
                                     ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp 
                                    WHERE wp.category = ? ";
                                    $result = $this->db->query($query, [$category->category])->row();
                                ?>
                                    <th style="text-align: center;"><?php echo $result->total; ?></th>
                                    <th style="text-align: center;"><?php echo $result->wheat_wp; ?></th>
                                    <th style="text-align: center;"><?php echo $result->maize_wp; ?></th>
                                <?php } ?>
                                <?php $query = "SELECT  COUNT(*) as total,
                                     ROUND(AVG(wp.wheat_water_productivity),2) as wheat_wp,
                                    ROUND(AVG(wp.maize_water_productivity),2) as maize_wp
                                    FROM `impact_survery_water_productivity` as wp ";
                                $result = $this->db->query($query)->row();
                                ?>
                                <th style="text-align: center;"><?php echo $result->total; ?></th>
                                <th style="text-align: center;"><?php echo $result->wheat_wp; ?></th>
                                <th style="text-align: center;"><?php echo $result->maize_wp; ?></th>

                            </tr>
                            <tr>
                                <th>Weighted Average</th>
                                <?php
                                foreach ($weighted_average as $key => $value) { ?>
                                    <th style="text-align: center;"><?php echo $value['total']; ?></th>
                                    <th style="text-align: center;"><?php echo round($value['wheat_wp'] / ($value['total']), 2); ?></th>
                                    <th style="text-align: center;"><?php echo round($value['maize_wp'] / ($value['total']), 2); ?></th>
                                <?php } ?>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>