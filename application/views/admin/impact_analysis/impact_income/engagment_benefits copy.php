<h4>Impact Analysis on Citizen Engagement and Benefits in Income and Employment</h4>
<hr />
<div class="row">
    <div class="col-md-6">
        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();
        $categories = [];
        $avg_increase = [];
        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Components Wise Percentage Increase (per Scheme) in Income</th>
                </tr>
                <tr>
                    <th rowspan="2">Components</th>
                    <th colspan="3">Percentage Increase (per Scheme) in Income</th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($components as $component) {
                    $query = "SELECT imp_sur.region,
                    ROUND(AVG(imp_sur.income_improved_per),2) as avg_increase
                              FROM `impact_surveys`  as imp_sur
                              WHERE imp_sur.component = '" . $component->component . "'";
                    $crop_intensity = $this->db->query($query)->row();

                    // Store values for Highcharts
                    $categories[] = $component->component;
                    $avg_increase[] = $crop_intensity->avg_increase;
                ?>
                    <tr>
                        <th><?php echo $component->component; ?></th>
                        <td><?php echo $crop_intensity->avg_increase; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php $query = "SELECT 
                ROUND(AVG(imp_sur.income_improved_per),2) as avg_increase
                FROM `impact_surveys`  as imp_sur";
                $crop_intensity = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $crop_intensity->avg_increase; ?></td>
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
                    text: 'Components Wise Percentage Increase (per Scheme) in Income'
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
                        text: 'Percentage Increase (per Scheme) in Income (AVG)'
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
                    name: 'Change AVG',
                    data: <?php echo json_encode($avg_increase, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>
    </div>
    <div class="col-md-6">
        <?php
        $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $sub_components = $this->db->query($query)->result();
        $categories = [];
        $avg_increase = [];
        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="4">Sub Components Wise Percentage Increase (per Scheme) in Income</th>
                </tr>
                <tr>
                    <th rowspan="2">Sub Component</th>
                    <th colspan="3">Percentage Increase (per Scheme) in Income</th>
                </tr>

            </thead>
            <tbody>
                <?php foreach ($sub_components as $sub_component) {
                    $query = "SELECT imp_sur.region, 
                    ROUND(AVG(imp_sur.income_improved_per),2) as avg_increase
                    FROM `impact_surveys`  as imp_sur
                              WHERE imp_sur.sub_component = '" . $sub_component->sub_component . "'";
                    $crop_intensity = $this->db->query($query)->row();

                    // Store values for Highcharts
                    $categories[] = $sub_component->sub_component;
                    $avg_increase[] = $crop_intensity->avg_increase;
                ?>
                    <tr>
                        <th><?php echo $sub_component->sub_component; ?>
                        <td><?php echo $crop_intensity->avg_increase; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php $query = "SELECT 
                ROUND(AVG(imp_sur.income_improved_per),2) as avg_increase
                FROM `impact_surveys`  as imp_sur";
                $crop_intensity = $this->db->query($query)->row();
                ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $crop_intensity->avg_increase; ?></td>
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
                    text: 'Sub Components Wise Percentage Increase (per Scheme) in Income'
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
                        text: 'Percentage Increase (per Scheme) in Income (AVG)'
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
                    name: 'Change AVG',
                    data: <?php echo json_encode($avg_increase, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>
    </div>


</div>