<h4>Impact Analysis on Water Losses</h4>

<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($components);
        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Components Wise Water Losses</th>
                </tr>
                <tr>
                    <th rowspan="2">Components</th>
                    <th colspan="3">Water Losses</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Reduction / Saving <small>Avg (%)</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($components as $component) {
                    $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.component = '" . $component->component . "'";
                    $water_losses = $this->db->query($query)->row();

                    // Store values for Highcharts
                    $categories[] = $component->component;
                    $beforeLosses[] = $water_losses->imp_before_losses;
                    $afterLosses[] = $water_losses->imp_after_losses;
                    $reductionPercent[] = $water_losses->reduction_water_losses;
                ?>
                    <tr>
                        <th><?php echo $component->component; ?></th>
                        <td><?php echo $water_losses->imp_before_losses; ?></td>
                        <td><?php echo $water_losses->imp_after_losses; ?></td>
                        <td><?php echo $water_losses->reduction_water_losses; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                FROM `impact_surveys_water_losses` as wl";
                $waterlosses = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total AVG.</th>
                    <td><?php echo $waterlosses->imp_before_losses; ?></td>
                    <td><?php echo $waterlosses->imp_after_losses; ?></td>
                    <td><?php echo $waterlosses->reduction_water_losses; ?></td>
                </tr>
            </tfoot>
        </table>
        <div id="ComponentwaterLossChart" style="width:100%; height:400px;"></div>

        <script>
            Highcharts.chart('ComponentwaterLossChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Component Wise Water Losses Comparison'
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
                        text: 'Water Losses (AVG)'
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#FF5733'
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#33B5E5'
                }, {
                    name: 'Reduction (%)',
                    data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                    color: '#2ECC71'
                }]
            });
        </script>

    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT `sub_component` as component FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($components);
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Sub Components Wise Water Losses</th>
                </tr>
                <tr>
                    <th rowspan="2">Components</th>
                    <th colspan="3">Water Losses</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Reduction / Saving <small>Avg (%)</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($components as $component) {
                    $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.sub_component = '" . $component->component . "'";
                    $water_losses = $this->db->query($query)->row();
                    // Store values for Highcharts
                    $categories[] = $component->component;
                    $beforeLosses[] = $water_losses->imp_before_losses;
                    $afterLosses[] = $water_losses->imp_after_losses;
                    $reductionPercent[] = $water_losses->reduction_water_losses;
                ?>
                    <tr>
                        <th><?php echo $component->component; ?></th>
                        <td><?php echo $water_losses->imp_before_losses; ?></td>
                        <td><?php echo $water_losses->imp_after_losses; ?></td>
                        <td><?php echo $water_losses->reduction_water_losses; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                FROM `impact_surveys_water_losses` as wl";
                $waterlosses = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total AVG.</th>
                    <td><?php echo $waterlosses->imp_before_losses; ?></td>
                    <td><?php echo $waterlosses->imp_after_losses; ?></td>
                    <td><?php echo $waterlosses->reduction_water_losses; ?></td>
                </tr>
            </tfoot>
        </table>
        <div id="SubComponentwaterLossChart" style="width:100%; height:400px;"></div>

        <script>
            Highcharts.chart('SubComponentwaterLossChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Component Wise Water Losses Comparison'
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
                        text: 'Water Losses (AVG)'
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#FF5733'
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#33B5E5'
                }, {
                    name: 'Reduction (%)',
                    data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                    color: '#2ECC71'
                }]
            });
        </script>
    </div>

    <div class="col-md-6">
        <?php
        $query = "SELECT `category` as component FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($components);
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Category Wise Water Losses</th>
                </tr>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="3">Water Losses</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Reduction / Saving <small>Avg (%)</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($components as $component) {
                    $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.category = '" . $component->component . "'";
                    $water_losses = $this->db->query($query)->row();
                    // Store values for Highcharts
                    $categories[] = $component->component;
                    $beforeLosses[] = $water_losses->imp_before_losses;
                    $afterLosses[] = $water_losses->imp_after_losses;
                    $reductionPercent[] = $water_losses->reduction_water_losses;
                ?>
                    <tr>
                        <th><?php echo $component->component; ?></th>
                        <td><?php echo $water_losses->imp_before_losses; ?></td>
                        <td><?php echo $water_losses->imp_after_losses; ?></td>
                        <td><?php echo $water_losses->reduction_water_losses; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                FROM `impact_surveys_water_losses` as wl";
                $waterlosses = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total AVG.</th>
                    <td><?php echo $waterlosses->imp_before_losses; ?></td>
                    <td><?php echo $waterlosses->imp_after_losses; ?></td>
                    <td><?php echo $waterlosses->reduction_water_losses; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6">
        <div id="CategoryComponentwaterLossChart" style="width:100%; height:400px;"></div>

        <script>
            Highcharts.chart('CategoryComponentwaterLossChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Category Wise Water Losses Comparison'
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
                        text: 'Water Losses (AVG)'
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#FF5733'
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#33B5E5'
                }, {
                    name: 'Reduction (%)',
                    data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                    color: '#2ECC71'
                }]
            });
        </script>
    </div>


    <div class="col-md-6">
        <?php
        $query = "SELECT `region` as region FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
        $regions = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $beforeLosses = [];
        $afterLosses = [];
        $reductionPercent = [];

        // Initialize totals
        $total_before_losses = 0;
        $total_after_losses = 0;
        $total_reduction = 0;
        $count = count($regions);
        ?>

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Region Wise Water Losses</th>
                </tr>
                <tr>
                    <th rowspan="2">Region</th>
                    <th colspan="3">Water Losses</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Reduction / Saving <small>Avg (%)</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) {
                    $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses, 
                                      round(AVG(wl.imp_after_losses),2) as imp_after_losses, 
                                      round(AVG(wl.reduction_water_losses),2) as reduction_water_losses 
                                      FROM `impact_surveys_water_losses` as wl
                                      WHERE wl.region = '" . $region->region . "'";
                    $water_losses = $this->db->query($query)->row();
                    // Store values for Highcharts
                    $categories[] = $region->region;
                    $beforeLosses[] = $water_losses->imp_before_losses;
                    $afterLosses[] = $water_losses->imp_after_losses;
                    $reductionPercent[] = $water_losses->reduction_water_losses;
                ?>
                    <tr>
                        <th><?php echo $region->region; ?></th>
                        <td><?php echo $water_losses->imp_before_losses; ?></td>
                        <td><?php echo $water_losses->imp_after_losses; ?></td>
                        <td><?php echo $water_losses->reduction_water_losses; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query = "SELECT round(AVG(wl.imp_before_losses),2) as imp_before_losses,
                round(AVG(wl.imp_after_losses),2) as imp_after_losses,
                round(AVG(wl.reduction_water_losses),2) as reduction_water_losses
                FROM `impact_surveys_water_losses` as wl";
                $waterlosses = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total AVG.</th>
                    <td><?php echo $waterlosses->imp_before_losses; ?></td>
                    <td><?php echo $waterlosses->imp_after_losses; ?></td>
                    <td><?php echo $waterlosses->reduction_water_losses; ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6">
        <div id="regionregionwaterLossChart" style="width:100%; height:400px;"></div>

        <script>
            Highcharts.chart('regionregionwaterLossChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'region Wise Water Losses Comparison'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'regions'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Water Losses (AVG)'
                    }
                },
                legend: {
                    align: 'center',
                    verticalAlign: 'bottom',
                    layout: 'horizontal'
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($beforeLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#FF5733'
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($afterLosses, JSON_NUMERIC_CHECK); ?>,
                    color: '#33B5E5'
                }, {
                    name: 'Reduction (%)',
                    data: <?php echo json_encode($reductionPercent, JSON_NUMERIC_CHECK); ?>,
                    color: '#2ECC71'
                }]
            });
        </script>
    </div>

</div>