<h4>Beneficiaries</h4>
<hr />
<div class="row">
    <div class="col-md-4">

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="6">WUA Members</th>
                </tr>
                <tr>
                    <th colspan="4">WUA Members</th>
                </tr>
                <tr>
                    <th>Total <small>Avg.</small></th>
                    <th>Male <small>Avg.</small></th>
                    <th>Female <small>Avg.</small></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query =
                    "SELECT SUM(wua_members) as wua_members,
                    SUM(male_members) as male_members,
                    SUM(female_members) as female_members
                FROM `impact_surveys` as s";
                $wua = $this->db->query($query)->row();


                // Store values for Highcharts
                $wua_members[] = $wua->wua_members;
                $male_members[] = $wua->male_members;
                $female_members[] = $wua->female_members;
                ?>
                <tr>
                    <td><?php echo $wua->wua_members; ?></td>
                    <td><?php echo $wua->male_members; ?></td>
                    <td><?php echo $wua->female_members; ?></td>
                </tr>
                <tr>
                    <td>Percentage</td>
                    <td><?php echo round(($wua->male_members * 100) / $wua->wua_members, 2) . "%"; ?></td>
                    <td><?php echo round(($wua->female_members * 100) / $wua->wua_members, 2) . "%"; ?></td>
                </tr>

            </tbody>


        </table>

        <div id="wua_members_chart"></div>
        <script>
            Highcharts.chart('wua_members_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'WUA Members'
                },
                xAxis: {
                    categories: 'WUA Members',
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
                        name: 'WUA Members',
                        data: <?php echo json_encode($wua_members, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        name: 'Male Members',
                        data: <?php echo json_encode($male_members, JSON_NUMERIC_CHECK); ?>
                    },
                    {
                        name: 'Female Members',
                        data: <?php echo json_encode($female_members, JSON_NUMERIC_CHECK); ?>
                    }
                ]
            });
        </script>





    </div>

    <div class="col-md-4">

        <?php
        $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
        $components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $house_holds = [];
        $total_beneficiaries = [];
        $male_beneficiaries = [];
        $female_beneficiaries = [];

        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="6">Beneficiaries Components Wise</th>
                </tr>
                <tr>
                    <th rowspan="2">Components</th>
                    <th colspan="4">Beneficiaries</th>
                </tr>
                <tr>
                    <th>Households <small>Avg.</small></th>
                    <th>Total <small>Avg.</small></th>
                    <th>Male <small>Avg.</small></th>
                    <th>Female <small>Avg.</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($components as $component) {
                    $query =
                        "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.component = '" . $component->component . "'";
                    $beneficiary = $this->db->query($query)->row();


                    // Store values for Highcharts
                    $categories[] = $component->component;
                    $house_holds[] = $beneficiary->house_holds;
                    $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                    $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                    $female_beneficiaries[] = $beneficiary->female_beneficiaries;
                ?>
                    <tr>
                        <th><?php echo $component->component; ?></th>
                        <td><?php echo $beneficiary->house_holds; ?></td>
                        <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query =
                    "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s";
                $beneficiary = $this->db->query($query)->row(); ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $beneficiary->house_holds; ?></td>
                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                </tr>
            </tfoot>

        </table>

        <div id="components_beneficiaries_chart"></div>
        <script>
            Highcharts.chart('components_beneficiaries_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Beneficiaries Components Wise'
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
                    name: 'House Holds',
                    data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Total Beneficiaries',
                    data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Male Beneficiaries',
                    data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Female Beneficiaries',
                    data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>





    </div>

    <div class="col-md-4">

        <?php
        $query = "SELECT `sub_component` FROM `impact_surveys` GROUP BY `sub_component` ORDER BY `sub_component` ASC";
        $sub_components = $this->db->query($query)->result();

        // Initialize arrays for Highcharts data
        $categories = [];
        $house_holds = [];
        $total_beneficiaries = [];
        $male_beneficiaries = [];
        $female_beneficiaries = [];

        ?>
        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="6">Beneficiaries Sub Components Wise</th>
                </tr>
                <tr>
                    <th rowspan="2">Sub Componets</th>
                    <th colspan="4">Beneficiaries</th>
                </tr>
                <tr>
                    <th>Households <small>Avg.</small></th>
                    <th>Total <small>Avg.</small></th>
                    <th>Male <small>Avg.</small></th>
                    <th>Female <small>Avg.</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sub_components as $sub_component) {
                    $query =
                        "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.sub_component = '" . $sub_component->sub_component . "'";
                    $beneficiary = $this->db->query($query)->row();


                    // Store values for Highcharts
                    $categories[] = $sub_component->sub_component;
                    $house_holds[] = $beneficiary->house_holds;
                    $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                    $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                    $female_beneficiaries[] = $beneficiary->female_beneficiaries;
                ?>
                    <tr>
                        <th><?php echo $sub_component->sub_component; ?></th>
                        <td><?php echo $beneficiary->house_holds; ?></td>
                        <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query =
                    "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s";
                $beneficiary = $this->db->query($query)->row(); ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $beneficiary->house_holds; ?></td>
                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                </tr>
            </tfoot>

        </table>

        <div id="sub_components_beneficiaries_chart"></div>
        <script>
            Highcharts.chart('sub_components_beneficiaries_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Beneficiaries Sub Components Wise'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories); ?>,
                    title: {
                        text: 'Sub Component'
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
                    name: 'House Holds',
                    data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Total Beneficiaries',
                    data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Male Beneficiaries',
                    data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Female Beneficiaries',
                    data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>





    </div>
</div>

<?php
$query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
$categories = $this->db->query($query)->result();

// Initialize arrays for Highcharts data
$categories_list = [];
$house_holds = [];
$total_beneficiaries = [];
$male_beneficiaries = [];
$female_beneficiaries = [];

?>

<div class="row">
    <div class="col-md-6">

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="6">Beneficiaries Category Wise</th>
                </tr>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="4">Beneficiaries</th>
                </tr>
                <tr>
                    <th>Households <small>Avg.</small></th>
                    <th>Total <small>Avg.</small></th>
                    <th>Male <small>Avg.</small></th>
                    <th>Female <small>Avg.</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category) {
                    $query =
                        "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.category = '" . $category->category . "'";
                    $beneficiary = $this->db->query($query)->row();


                    // Store values for Highcharts
                    $categories_list[] = $category->category;
                    $house_holds[] = $beneficiary->house_holds;
                    $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                    $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                    $female_beneficiaries[] = $beneficiary->female_beneficiaries;
                ?>
                    <tr>
                        <th><?php echo $category->category; ?></th>
                        <td><?php echo $beneficiary->house_holds; ?></td>
                        <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query =
                    "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s";
                $beneficiary = $this->db->query($query)->row(); ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $beneficiary->house_holds; ?></td>
                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                </tr>
            </tfoot>

        </table>
    </div>
    <div class="col-md-6">
        <div id="categories_beneficiaries_chart"></div>
        <script>
            Highcharts.chart('categories_beneficiaries_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Beneficiaries categories wise'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories_list); ?>,
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
                    name: 'House Holds',
                    data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Total Beneficiaries',
                    data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Male Beneficiaries',
                    data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Female Beneficiaries',
                    data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>

    </div>



</div>


<?php
$query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ORDER BY `region` ASC";
$regions = $this->db->query($query)->result();

// Initialize arrays for Highcharts data
$categories_list = [];
$house_holds = [];
$total_beneficiaries = [];
$male_beneficiaries = [];
$female_beneficiaries = [];

?>

<div class="row">
    <div class="col-md-6">

        <table class="table table-bordered table_medium">
            <thead>
                <tr>
                    <th colspan="6">Beneficiaries Region Wise</th>
                </tr>
                <tr>
                    <th rowspan="2">Category</th>
                    <th colspan="4">Beneficiaries</th>
                </tr>
                <tr>
                    <th>Households <small>Avg.</small></th>
                    <th>Total <small>Avg.</small></th>
                    <th>Male <small>Avg.</small></th>
                    <th>Female <small>Avg.</small></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($regions as $region) {
                    $query =
                        "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s
                            WHERE s.region = '" . $region->region . "'";
                    $beneficiary = $this->db->query($query)->row();


                    // Store values for Highcharts
                    $categories_list[] = ucwords($region->region);
                    $house_holds[] = $beneficiary->house_holds;
                    $total_beneficiaries[] = $beneficiary->total_beneficiaries;
                    $male_beneficiaries[]  = $beneficiary->male_beneficiaries;
                    $female_beneficiaries[] = $beneficiary->female_beneficiaries;
                ?>
                    <tr>
                        <th><?php echo ucwords($region->region); ?></th>
                        <td><?php echo $beneficiary->house_holds; ?></td>
                        <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                        <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <?php
                $query =
                    "SELECT ROUND(AVG(s.`total_beneficiary_households`),2) as house_holds,
                            ROUND(AVG(s.`total_beneficiary_households`*5.7),2) as total_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.49),2) as male_beneficiaries,
                            ROUND((AVG(s.`total_beneficiary_households`*5.7)*0.51),2) as female_beneficiaries
                            FROM `impact_surveys` as s";
                $beneficiary = $this->db->query($query)->row(); ?>
                <tr>
                    <th>Total</th>
                    <td><?php echo $beneficiary->house_holds; ?></td>
                    <td><?php echo $beneficiary->total_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->male_beneficiaries; ?></td>
                    <td><?php echo $beneficiary->female_beneficiaries; ?></td>
                </tr>
            </tfoot>

        </table>
    </div>
    <div class="col-md-6">
        <div id="categories_region_chart"></div>
        <script>
            Highcharts.chart('categories_region_chart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Beneficiaries Region wise'
                },
                xAxis: {
                    categories: <?php echo json_encode($categories_list); ?>,
                    title: {
                        text: 'Region'
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
                    name: 'House Holds',
                    data: <?php echo json_encode($house_holds, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Total Beneficiaries',
                    data: <?php echo json_encode($total_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Male Beneficiaries',
                    data: <?php echo json_encode($male_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }, {
                    name: 'Female Beneficiaries',
                    data: <?php echo json_encode($female_beneficiaries, JSON_NUMERIC_CHECK); ?>
                }]
            });
        </script>

    </div>



</div>