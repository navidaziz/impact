<div class="row">
    <?php
    $query = "SELECT `region` FROM `impact_surveys` 
        GROUP BY `region` ASC;";
    $regions = $this->db->query($query)->result();
    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();
    $crops = array("wheat", "maize", "" . $crops . "", "sugarcane", "fodder", "vegetable", "fruit_orchard"); ?>
    <? foreach ($crops as $crops) { ?>
        <div class="col-md-6">
            <?php

            ?>

            <table class="table table-bordered table_medium" id="<?php echo $crops . "_y_table" ?>">
                <thead>
                    <tr>
                        <th colspan="<?php echo (count($components) * 3) + 4; ?>">Increase in <?php echo ucwords($crops); ?> Yield (ton/ha)</th>
                    </tr>
                    <tr>
                        <th rowspan="2">Regions</th>
                        <?php foreach ($components as $component) { ?>
                            <th colspan="3"><?php echo $component->component; ?></th>
                        <?php } ?>
                        <th colspan="3">Cumulative</th>
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
                                $query = "SELECT ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ?
                            AND region = ? ";
                                $result = $this->db->query($query, [$component->component, $region->region])->row();
                            ?>
                                <td><?php echo $result->before; ?></td>
                                <td><?php echo $result->after; ?></td>
                                <td><?php echo $result->per_increase; ?></td>
                            <?php } ?>

                            <?php $query = "SELECT ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
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
                            $query = "SELECT ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys`  
                            WHERE component = ? ";
                            $result = $this->db->query($query, [$component->component])->row();
                        ?>
                            <td><?php echo $result->before; ?></td>
                            <td><?php echo $result->after; ?></td>
                            <td><?php echo $result->per_increase; ?></td>
                        <?php } ?>
                        <?php $query = "SELECT ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2) AS `before`, 
                            ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) AS `after`,
                            ROUND(((ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) - 
                            ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                            FROM `impact_surveys` ";
                        $result = $this->db->query($query)->row();
                        ?>
                        <td><?php echo $result->before; ?></td>
                        <td><?php echo $result->after; ?></td>
                        <td><?php echo $result->per_increase; ?></td>

                    </tr>
                </tfoot>
            </table>

            <div id="<?php echo $crop . "_y_chart" ?>" style="width:100%;"></div>
            <script>
                Highcharts.chart('<?php echo $crop . "_y_chart" ?>', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Increase in <?php echo ucwords($crops); ?> Yield (ton/ha)'
                    },
                    xAxis: {
                        categories: [
                            <?php foreach ($components as $component) {
                                echo "'" . $component->component . "',";
                            } ?> 'Cumulative'
                        ],
                        title: {
                            text: 'Components'
                        }
                    },
                    yAxis: {
                        title: {
                            text: 'Weighted Avg.'
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
                            name: 'Before Avg(Ha)',
                            data: [
                                <?php
                                foreach ($components as $component) {
                                    $query = "SELECT ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2) AS `before` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                    $result = $this->db->query($query, [$component->component])->row();
                                    echo $result->before . ",";
                                }
                                $query = "SELECT ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2) AS `before` 
                                  FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();
                                echo $result->before . ",";
                                ?>
                            ]
                        },
                        {
                            name: 'After Avg(Ha)',
                            data: [
                                <?php
                                foreach ($components as $component) {
                                    $query = "SELECT ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) AS `after` 
                                  FROM `impact_surveys` WHERE component = ? ";
                                    $result = $this->db->query($query, [$component->component])->row();
                                    echo $result->after . ",";
                                }
                                $query = "SELECT ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) AS `after` 
                                  FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();
                                echo $result->after . ",";
                                ?>
                            ]
                        },
                        {
                            name: 'Increase Avg(%)',
                            data: [
                                <?php
                                foreach ($components as $component) {
                                    $query = "SELECT ROUND(((ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys` WHERE component = ? ";
                                    $result = $this->db->query($query, [$component->component])->row();
                                    echo $result->per_increase . ",";
                                }
                                $query = "SELECT ROUND(((ROUND((AVG(" . $crops . "_cp_after)  / 2.714), 2) - 
                                ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2))/ROUND((AVG(" . $crops . "_cp_before)  / 2.714), 2)) * 100, 2) AS per_increase
                                  FROM `impact_surveys`";
                                $result = $this->db->query($query)->row();
                                echo $result->per_increase . ",";
                                ?>
                            ]
                        }
                    ]
                });
            </script>
        </div>
    <?php } ?>
</div>