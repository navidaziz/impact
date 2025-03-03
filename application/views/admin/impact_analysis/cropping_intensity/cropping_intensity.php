<h4>Impact Analysis on Cropping Intensity</h4>
<hr />
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        $categories = [];
        $before = [];
        $after = [];
        $avg_change = [];
        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Components Wise Cropping Intensity</th>
                </tr>
                <tr>
                    <th rowspan="2">Components</th>
                    <th colspan="3">Cropping Intensity</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Change <small>AVG</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($components as $component) {
                    $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `before`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci
                              WHERE ci.component = '" . $component->component . "'";
                    $crop_intensity = $this->db->query($query)->row();

                    // Store values for Highcharts
                    $categories[] = $component->component;
                    $before[] = $crop_intensity->before;
                    $after[] = $crop_intensity->after;
                    $avg_change[] = $crop_intensity->avg_change;
                ?>
                    <tr>
                        <th><?php echo $component->component; ?></th>
                        <td><?php echo $crop_intensity->before; ?></td>
                        <td><?php echo $crop_intensity->after; ?></td>
                        <td><?php echo $crop_intensity->avg_change; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `befor`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci";
                $crop_intensity = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $crop_intensity->befor; ?></td>
                    <td><?php echo $crop_intensity->after; ?></td>
                    <td><?php echo $crop_intensity->avg_change; ?></td>
                </tr>
            </tfoot>

        </table>

        <div id="component_cropping_intensity"></div>
        <script>
            Highcharts.chart('component_cropping_intensity', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Components Wise Cropping Intensity'
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
                        text: 'Cropping Intensity (AVG)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' AVG'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($before, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($after, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Change AVG',
                    data: <?php echo json_encode($avg_change, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>
    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $sub_components = $this->db->query($query)->result();
        $categories = [];
        $before = [];
        $after = [];
        $avg_change = [];
        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Sub Components Wise Cropping Intensity</th>
                </tr>
                <tr>
                    <th rowspan="2">Sub Component</th>
                    <th colspan="3">Cropping Intensity</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Change <small>AVG</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sub_components as $sub_component) {
                    $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `before`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci
                              WHERE ci.sub_component = '" . $sub_component->sub_component . "'";
                    $crop_intensity = $this->db->query($query)->row();

                    // Store values for Highcharts
                    $categories[] = $sub_component->sub_component;
                    $before[] = $crop_intensity->before;
                    $after[] = $crop_intensity->after;
                    $avg_change[] = $crop_intensity->avg_change;
                ?>
                    <tr>
                        <th><?php echo $sub_component->sub_component; ?></th>
                        <td><?php echo $crop_intensity->before; ?></td>
                        <td><?php echo $crop_intensity->after; ?></td>
                        <td><?php echo $crop_intensity->avg_change; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `befor`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci";
                $crop_intensity = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $crop_intensity->befor; ?></td>
                    <td><?php echo $crop_intensity->after; ?></td>
                    <td><?php echo $crop_intensity->avg_change; ?></td>
                </tr>
            </tfoot>

        </table>

        <div id="sub_component_cropping_intensity"></div>
        <script>
            Highcharts.chart('sub_component_cropping_intensity', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Sub Components Wise Cropping Intensity'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'Sub Components'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cropping Intensity (AVG)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' AVG'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($before, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($after, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Change AVG',
                    data: <?php echo json_encode($avg_change, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>
    </div>


</div>

<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $categories = $this->db->query($query)->result();
        $categories_list = [];
        $before = [];
        $after = [];
        $avg_change = [];
        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Categories Cropping Intensity</th>
                </tr>
                <tr>
                    <th rowspan="2">Categories</th>
                    <th colspan="3">Cropping Intensity</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Change <small>AVG</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) {
                    $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `before`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci
                              WHERE ci.category = '" . $category->category . "'";
                    $crop_intensity = $this->db->query($query)->row();

                    // Store values for Highcharts
                    $categories_list[] = $category->category;
                    $before[] = $crop_intensity->before;
                    $after[] = $crop_intensity->after;
                    $avg_change[] = $crop_intensity->avg_change;
                ?>
                    <tr>
                        <th><?php echo $category->category; ?></th>
                        <td><?php echo $crop_intensity->before; ?></td>
                        <td><?php echo $crop_intensity->after; ?></td>
                        <td><?php echo $crop_intensity->avg_change; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `befor`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci";
                $crop_intensity = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $crop_intensity->befor; ?></td>
                    <td><?php echo $crop_intensity->after; ?></td>
                    <td><?php echo $crop_intensity->avg_change; ?></td>
                </tr>
            </tfoot>

        </table>
    </div>
    <div class="col-md-6">
        <div id="category_cropping_intensity"></div>
        <script>
            Highcharts.chart('category_cropping_intensity', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Categories Wise Cropping Intensity'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories_list); ?>,
                    title: {
                        text: 'Categories'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cropping Intensity (AVG)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' AVG'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($before, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($after, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Change AVG',
                    data: <?php echo json_encode($avg_change, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
        $regions = $this->db->query($query)->result();
        $regions_list = [];
        $before = [];
        $after = [];
        $avg_change = [];
        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">regions Cropping Intensity</th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <th colspan="3">Cropping Intensity</th>
                </tr>
                <tr>
                    <th>Before <small>AVG</small></th>
                    <th>After <small>AVG</small></th>
                    <th>Change <small>AVG</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) {
                    $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `before`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci
                              WHERE ci.region = '" . $region->region . "'";
                    $crop_intensity = $this->db->query($query)->row();

                    // Store values for Highcharts
                    $regions_list[] = ucwords($region->region);
                    $before[] = $crop_intensity->before;
                    $after[] = $crop_intensity->after;
                    $avg_change[] = $crop_intensity->avg_change;
                ?>
                    <tr>
                        <th><?php echo ucwords($region->region); ?></th>
                        <td><?php echo $crop_intensity->before; ?></td>
                        <td><?php echo $crop_intensity->after; ?></td>
                        <td><?php echo $crop_intensity->avg_change; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php $query = "SELECT ci.region, ROUND(AVG(ci.crop_intensity_before),2) as `befor`, 
                              ROUND(AVG(ci.crop_intensity_after),2) as `after`, 
                              ROUND(((AVG(ci.crop_intensity_before)+AVG(ci.crop_intensity_after))/2),2) as avg_change
                              FROM `impact_crop_intensity`  as ci";
                $crop_intensity = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $crop_intensity->befor; ?></td>
                    <td><?php echo $crop_intensity->after; ?></td>
                    <td><?php echo $crop_intensity->avg_change; ?></td>
                </tr>
            </tfoot>

        </table>
    </div>
    <div class="col-md-6">
        <div id="region_cropping_intensity"></div>
        <script>
            Highcharts.chart('region_cropping_intensity', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'regions Wise Cropping Intensity'
                },
                xAxis: {
                    categories: <?php echo json_encode($regions_list); ?>,
                    title: {
                        text: 'regions'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Cropping Intensity (AVG)'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' AVG'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Before AVG',
                    data: <?php echo json_encode($before, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'After AVG',
                    data: <?php echo json_encode($after, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Change AVG',
                    data: <?php echo json_encode($avg_change, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>
    </div>
</div>