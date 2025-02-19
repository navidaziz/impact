<?php
$g_component_title = 'Average Increase in Irrigated CCA by Components';
$g_sub_component_title = 'Average Increase in Irrigated CCA by Sub-Components';
$g_category_title = 'Average Increase in Irrigated CCA by Categories';

$component_title = 'Average Increase in Irrigated CCA by Region and Components Wise';
$sub_component_title = 'Average Increase in Irrigated CCA by Region and Sub-Components Wise';
$category_title = 'Average Increase in Irrigated CCA by Region and Categories Wise';
?>
<div class="row">
    <div class="col-md-4">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_small">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($components) * 3) + 4; ?>"><?php echo $component_title; ?></th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($components as $component) { ?>
                        <th colspan="3"><?php echo $component->component; ?></th>
                    <?php } ?>
                    <th colspan="3">AVG</th>
                </tr>
                <tr>

                    <?php foreach ($components as $component) { ?>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($components as $component) {
                            $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$component->component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys`
                    WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($components as $component) {
                        $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                        $result = $this->db->query($query, [$component->component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>

        <div id="irr_components" style="width:100%; height:500px;"></div>
        <script>
            Highcharts.chart('irr_components', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo $g_component_title; ?>'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($components as $component) {
                            echo "'" . $component->component . "',";
                        } ?>
                    ],
                    title: {
                        text: 'Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG -  %'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' '
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: [{
                        name: 'Before (Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->before . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'After (Ha)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->after . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'AVG (%)',
                        data: [
                            <?php
                            foreach ($components as $component) {
                                $query = "SELECT ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                $result = $this->db->query($query, [$component->component])->row();
                                echo $result->per_increase . ",";
                            }
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>

    <div class="col-md-8">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $sub_components = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_small">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($sub_components) * 3) + 4; ?>"><?php echo $sub_component_title; ?></th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($sub_components as $sub_component) { ?>
                        <th colspan="3"><?php echo $sub_component->sub_component; ?></th>
                    <?php } ?>
                    <th colspan="3">AVG</th>
                </tr>
                <tr>

                    <?php foreach ($sub_components as $sub_component) { ?>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($sub_components as $sub_component) {
                            $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE sub_component = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$sub_component->sub_component, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys`
                    WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($sub_components as $sub_component) {
                        $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE sub_component = ? ";
                        $result = $this->db->query($query, [$sub_component->sub_component])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>

        <div id="irr_sub_components" style="width:100%; height:500px;"></div>
        <script>
            Highcharts.chart('irr_sub_components', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo $g_sub_component_title; ?>'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($sub_components as $sub_component) {
                            echo "'" . $sub_component->sub_component . "',";
                        } ?>
                    ],
                    title: {
                        text: 'Sub Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG -  %'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ' '
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f} '
                        }
                    }
                },
                series: [{
                        name: 'Before (Ha)',
                        data: [
                            <?php
                            foreach ($sub_components as $sub_component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before` 
                                  FROM `impact_surveys` WHERE sub_component = ? ";
                                $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                echo $result->before . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'After (Ha)',
                        data: [
                            <?php
                            foreach ($sub_components as $sub_component) {
                                $query = "SELECT ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after` 
                                  FROM `impact_surveys` WHERE sub_component = ? ";
                                $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                echo $result->after . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'AVG (%)',
                        data: [
                            <?php
                            foreach ($sub_components as $sub_component) {
                                $query = "SELECT ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE sub_component = ? ";
                                $result = $this->db->query($query, [$sub_component->sub_component])->row();
                                echo $result->per_increase . ",";
                            }
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>

    <div class="col-md-12">
        <?php
        $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
        $regions = $this->db->query($query)->result();
        $query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
        $categories = $this->db->query($query)->result();
        ?>

        <table class="table table-bordered table_small">
            <thead>
                <tr>
                    <th colspan="<?php echo (count($categories) * 3) + 4; ?>"><?php echo $sub_component_title; ?></th>
                </tr>
                <tr>
                    <th rowspan="2">Regions</th>
                    <?php foreach ($categories as $category) { ?>
                        <th colspan="3"><?php echo $category->category; ?></th>
                    <?php } ?>
                    <th colspan="3">AVG</th>
                </tr>
                <tr>

                    <?php foreach ($categories as $category) { ?>
                        <th>Before <small>(Ha)</small></th>
                        <th>After <small>(Ha)</small></th>
                        <th>Increase <small>(%)</small></th>
                    <?php } ?>
                    <th>Before <small>(Ha)</small></th>
                    <th>After <small>(Ha)</small></th>
                    <th>Increase <small>(%)</small></th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo ucfirst($region->region) ?></th>
                        <?php foreach ($categories as $category) {
                            $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE category = ?
                            AND region = ? ";
                            $result = $this->db->query($query, [$category->category, $region->region])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>

                        <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys`
                    WHERE  region = ? ";
                        $result = $this->db->query($query, [$region->region])->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <?php foreach ($categories as $category) {
                        $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`, 
                            ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                            ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE category = ? ";
                        $result = $this->db->query($query, [$category->category])->row();
                    ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>
                    <?php } ?>
                    <?php $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before`,
                    ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after`,
                    ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) -
                    ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                    FROM `impact_surveys` ";
                    $result = $this->db->query($query)->row();
                    ?>
                    <td><?php echo $result->before; ?></td>
                    <td><?php echo $result->after; ?></td>
                    <td><?php echo $result->per_increase; ?></td>

                </tr>
            </tfoot>
        </table>

        <div id="irr_categories" style="width:100%; height:500px;"></div>
        <script>
            Highcharts.chart('irr_categories', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: '<?php echo $g_category_title; ?>'
                },
                xAxis: {
                    categories: [
                        <?php foreach ($categories as $category) {
                            echo "'" . $category->category . "',";
                        } ?>
                    ],
                    title: {
                        text: 'Sub Components'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Irrigated Area (Ha) AVG -  %'
                    }
                },
                tooltip: {
                    shared: true,
                    valueSuffix: ''
                },
                plotOptions: {
                    column: {
                        grouping: true,
                        dataLabels: {
                            enabled: true,
                            format: '{point.y:.2f}'
                        }
                    }
                },
                series: [{
                        name: 'Before (Ha)',
                        data: [
                            <?php
                            foreach ($categories as $category) {
                                $query = "SELECT ROUND(AVG(irrigated_area_before) / 2.714, 2) AS `before` 
                                  FROM `impact_surveys` WHERE category = ? ";
                                $result = $this->db->query($query, [$category->category])->row();
                                echo $result->before . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'After (Ha)',
                        data: [
                            <?php
                            foreach ($categories as $category) {
                                $query = "SELECT ROUND(AVG(irrigated_area_after) / 2.714, 2) AS `after` 
                                  FROM `impact_surveys` WHERE category = ? ";
                                $result = $this->db->query($query, [$category->category])->row();
                                echo $result->after . ",";
                            }
                            ?>
                        ]
                    },
                    {
                        name: 'AVG (%)',
                        data: [
                            <?php
                            foreach ($categories as $category) {
                                $query = "SELECT ROUND(((ROUND(AVG(irrigated_area_after) / 2.714, 2) - 
                            ROUND(AVG(irrigated_area_before) / 2.714, 2))/ROUND(AVG(irrigated_area_before) / 2.714, 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE category = ? ";
                                $result = $this->db->query($query, [$category->category])->row();
                                echo $result->per_increase . ",";
                            }
                            ?>
                        ]
                    }
                ]
            });
        </script>

    </div>

</div>