

<h4>Impact Analysis on Water Productivity of (Wheat and Maize)</h4>
<hr />

<div class="row">
    <div class="col-md-5">
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
                <table class="table table-bordered table_medium">
                    <thead>
                        <tr>
                            <th colspan="4">Components Wise Water Productivity (Wheat & Maize)</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Components</th>
                            <th colspan="3">Water Productivity</th>
                        </tr>
                        <tr>
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
                            <th>Total</th>
                            <td><?php echo $wp_total->wheat_wp; ?></td>
                            <td><?php echo $wp_total->maize_wp; ?></td>
                        </tr>

                        </tr>
                    </tfoot>

                </table>
            </div>
            <div class="col-md-6">
                <div id="component_wp_chart"></div>
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
                                borderWidth: 0
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



    </div>


    <div class="col-md-7">
        <div class="row">
            <div class="col-md-6">
                <?php
                $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
                $sub_components = $this->db->query($query)->result();

                // Initialize arrays for Highcharts data
                $categories = [];
                $wheat_wp = [];
                $maize_wp = [];

                ?>
                <table class="table table-bordered table_medium">
                    <thead>
                        <tr>
                            <th colspan="4">Sub Components Wise Water Productivity (Wheat & Maize)</th>
                        </tr>
                        <tr>
                            <th rowspan="2">Sub Components</th>
                            <th colspan="3">Water Productivity</th>
                        </tr>
                        <tr>
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
                            <th>Total</th>
                            <td><?php echo $wp_total->wheat_wp; ?></td>
                            <td><?php echo $wp_total->maize_wp; ?></td>
                        </tr>

                        </tr>
                    </tfoot>

                </table>
            </div>
            <div class="col-md-6">
                <div id="sub_component_wp_chart"></div>
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
                                borderWidth: 0
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
    </div>


    <div class="col-md-6">
        <?php
        $query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $com_categories = $this->db->query($query)->result();
        // Initialize arrays for Highcharts data
        $categories = [];
        $wheat_wp = [];
        $maize_wp = [];

        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Categories Wise Water Productivity (Wheat & Maize)</th>
                </tr>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="3">Water Productivity</th>
                </tr>
                <tr>
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
                    <th>Total</th>
                    <td><?php echo $wp_total->wheat_wp; ?></td>
                    <td><?php echo $wp_total->maize_wp; ?></td>
                </tr>

                </tr>
            </tfoot>

        </table>
    </div>
    <div class="col-md-6">
        <div id="category_wp_chart"></div>
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
                        borderWidth: 0
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


    <div class="col-md-6">
        <div id="region_wp_chart"></div>
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
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Regions Wise Water Productivity (Wheat & Maize)</th>
                </tr>
                <tr>
                    <th rowspan="2">Region</th>
                    <th colspan="3">Water Productivity</th>
                </tr>
                <tr>
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
                    <th>Total</th>
                    <td><?php echo $wp_total->wheat_wp; ?></td>
                    <td><?php echo $wp_total->maize_wp; ?></td>
                </tr>

                </tr>
            </tfoot>

        </table>

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
                        borderWidth: 0
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