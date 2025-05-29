<div class="row">
    <div class="col-md-12">
        <h4>Impact Analysis: Environmnetal & Social Management Compliance</h4>
        <small>Analysis of total <strong><?php
                                            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
                                            echo number_format($this->db->query($query)->row()->total);

                                            ?></strong> Impact Surveys</small>
    </div>
    <!-- <div class="col-md-4" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Env_And_Social'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Irrigated_CCA',['table_1', 'table_2', 'wheat', 'maize' , 'sugarcane' , 'vegetable' , 'orchard'], ['Summary', 'Crop & Component Wise' , 'Wheat', 'Maize' , 'Sugarcane' , 'Vegetable' , 'Orchard' ])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div> -->
</div>
<hr />
<?php
$query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
$regions = $this->db->query($query)->result();
$query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
$components = $this->db->query($query)->result();

?>
<div class="row">
    <div class="col-md-8">
        <h4>Response on the Environmental Aspect of the KPIAIP Schemes</h4>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Environmental'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Environmental',['table_1', <?php foreach ($components as $component) {
                                                                                                                    echo "'table_1_" . $component->component . "', ";
                                                                                                                } ?>], ['Summary', <?php foreach ($components as $component) {
                                                                                                                                        echo "'Component " . $component->component . "', ";
                                                                                                                                    } ?>])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>

</div>


<hr />


<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <strong>Response on the Environmental Aspect of the KPIAIP Schemes</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table_medium   table-bordered table-striped" id="table_1">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="13" class="text-center">Response on the Environmental Aspect of the KPIAIP Schemes</th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th colspan="2">Solid wastes disposed off properly</th>
                                <th colspan="2">Extra soil properly disposed off</th>
                                <th colspan="2">Left-over material disposed off</th>
                                <th colspan="2">Environmental AI quality Monitored</th>
                                <th colspan="2">Standing water around lined WC</th>
                                <th colspan="2">Damages, Creacking, Settlement observed</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`solid_waste_disposed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`solid_waste_disposed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`solid_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`solid_waste_disposed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `solid_waste_disposed` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>
                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`soil_disposed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`soil_disposed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`soil_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`soil_disposed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `soil_disposed` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>
                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`construction_waste_disposed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`construction_waste_disposed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`construction_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`construction_waste_disposed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `construction_waste_disposed` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>
                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`env_quality_monitored` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`env_quality_monitored` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`env_quality_monitored` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`env_quality_monitored` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `env_quality_monitored` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>
                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`standing_water` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`standing_water` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`standing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`standing_water` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `standing_water` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>
                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`distress_damage` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`distress_damage` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`distress_damage` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`distress_damage` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `distress_damage` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Over All</th>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`solid_waste_disposed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`solid_waste_disposed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`solid_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`solid_waste_disposed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `solid_waste_disposed` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`soil_disposed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`soil_disposed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`soil_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`soil_disposed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `soil_disposed` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`construction_waste_disposed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`construction_waste_disposed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`construction_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`construction_waste_disposed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `construction_waste_disposed` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`env_quality_monitored` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`env_quality_monitored` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`env_quality_monitored` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`env_quality_monitored` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `env_quality_monitored` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`standing_water` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`standing_water` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`standing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`standing_water` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `standing_water` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`distress_damage` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`distress_damage` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`distress_damage` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`distress_damage` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `distress_damage` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <?php

    foreach ($components as $component) {
    ?>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>Response on the Environmental Aspect of the KPIAIP Schemes <br />
                        Component <?php echo $component->component; ?></strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table_medium   table-bordered table-striped" id="table_1_<?php echo $component->component; ?>">
                            <thead>
                                <tr style="display: none;">
                                    <th colspan="<?php echo count($regions) + 2; ?>" class="text-center">
                                        Response on the Environmental Aspect of the KPIAIP Schemes <br />
                                        Component <?php echo $component->component; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Environmental Aspect</th>
                                    <?php foreach ($regions as $region) { ?>
                                        <th><?php echo ucfirst($region->region); ?></th>
                                    <?php } ?>
                                    <th>Overall</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Solid wastes disposed off properly</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`solid_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `solid_waste_disposed` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`solid_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `solid_waste_disposed` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Extra soil properly disposed off</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`soil_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `soil_disposed` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`soil_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `soil_disposed` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Left-over material disposed off</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`construction_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `construction_waste_disposed` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`construction_waste_disposed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `construction_waste_disposed` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Environmental AI quality Monitored</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`env_quality_monitored` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `env_quality_monitored` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`env_quality_monitored` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `env_quality_monitored` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Standing water around lined WC</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`standing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `standing_water` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`standing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `standing_water` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Damages, Cracking, Settlement observed</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`distress_damage` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `distress_damage` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`distress_damage` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `distress_damage` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div style="margin-bottom: 10px;"></div>
<div class="row">
    <div class="col-md-8">
        <h4>Detail of Tree Cutting and Plantation</h4>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Trees'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Trees',['table_2', <?php foreach ($components as $component) {
                                                                                                            echo "'table_2_" . $component->component . "', ";
                                                                                                        } ?>], ['Summary', <?php foreach ($components as $component) {
                                                                                                                                echo "'Component " . $component->component . "', ";
                                                                                                                            } ?>])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>

</div>
<hr />
<div class="row">

    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <strong>Detail of Tree Cutting and Plantation</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table_medium   table-bordered table-striped" id="table_2">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="13" class="text-center">Detail of Tree Cutting and Plantation</th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th colspan="2">Response on Tree cutting during execuation of schemes</th>
                                <th>Total Number of Trees cut during execuation of schemes</th>
                                <th>Total Number of Trees planted</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Number</th>
                                <th>Number</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php
                                    $query = "SELECT 
                                            COUNT(*) AS total,
                                            SUM(IF(`trees_cut` = 'Yes', 1, 0)) AS yes_count,
                                            SUM(IF(`trees_cut` = 'No', 1, 0)) AS no_count,
                                            ROUND(SUM(IF(`trees_cut` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                                            ROUND(SUM(IF(`trees_cut` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                                            FROM `impact_surveys`
                                            WHERE region = '" . $region->region . "'
                                            AND `trees_cut` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                                            SUM(trees_cut_count) AS total
                                            FROM `impact_surveys`
                                            WHERE region = '" . $region->region . "'
                                            AND `trees_cut` IN ('Yes')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->total; ?> </td>

                                    <?php
                                    $query = "SELECT 
                                        SUM(trees_planted_count) AS total
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND `trees_cut` IN ('Yes')";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->total; ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Over All</th>
                                <?php
                                $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`trees_cut` = 'Yes', 1, 0)) AS yes_count,
                                    SUM(IF(`trees_cut` = 'No', 1, 0)) AS no_count,
                                    ROUND(SUM(IF(`trees_cut` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                                    ROUND(SUM(IF(`trees_cut` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                                    FROM `impact_surveys`
                                    WHERE  `trees_cut` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <th><?php echo $result->yes_percentage; ?> </th>
                                <th><?php echo $result->no_percentage; ?> </th>

                                <?php
                                $query = "SELECT 
                                    SUM(trees_cut_count) AS total
                                    FROM `impact_surveys`
                                    WHERE `trees_cut` IN ('Yes')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <th><?php echo $result->total; ?> </th>

                                <?php
                                $query = "SELECT 
                                    SUM(trees_planted_count) AS total
                                    FROM `impact_surveys`
                                    WHERE `trees_cut` IN ('Yes')";
                                $result = $this->db->query($query)->row();
                                ?>
                                <th><?php echo $result->total; ?> </th>
                            </tr>

                        </tfoot>


                    </table>

                </div>
            </div>
        </div>
    </div>
    <?php
    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();
    foreach ($components as $component) {
    ?>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <strong>Detail of Tree Cutting and Plantation<br />
                        Component <?php echo $component->component; ?></strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table_medium   table-bordered table-striped id=" id="table_2_<?php echo $component->component; ?>">
                            <thead>
                                <tr style="display: none;">
                                    <th colspan="<?php echo count($regions) + 2; ?>" class="text-center">Detail of Tree Cutting and Plantation<br />
                                        Component <?php echo $component->component; ?>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Categories</th>
                                    <?php foreach ($regions as $region) { ?>
                                        <th><?php echo ucfirst($region->region); ?></th>
                                    <?php } ?>
                                    <th>Over All</th>
                                </tr>

                                <tr>
                                    <td>Response on Tree cutting during execution of schemes - Yes (%)</td>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`trees_cut` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `trees_cut` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`trees_cut` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `trees_cut` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>


                                <tr>
                                    <td>Total Number of Trees cut during execution of schemes</td>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    SUM(trees_cut_count) AS total
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `trees_cut_count` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->total; ?></td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    SUM(trees_cut_count) AS total
                    FROM `impact_surveys`
                    WHERE `trees_cut_count` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->total; ?></td>
                                </tr>

                                <tr>
                                    <td>Total Number of Trees planted</td>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    SUM(trees_planted_count) AS total
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `trees_planted_count` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row();
                                    ?>
                                        <td><?php echo $result->total; ?></td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    SUM(trees_planted_count) AS total
                    FROM `impact_surveys`
                    WHERE `trees_planted_count` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row();
                                    ?>
                                    <td><?php echo $result->total; ?></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<div style="margin-bottom: 10px;"></div>
<div class="row">
    <div class="col-md-8">
        <h4>Analysis of IPM use by the forming community</h4>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/IPM_Forming_Community'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('IPM_Forming_Community',['table_3', <?php foreach ($components as $component) {
                                                                                                                            echo "'table_3_" . $component->component . "', ";
                                                                                                                        } ?>], ['Summary', <?php foreach ($components as $component) {
                                                                                                                                                echo "'Component " . $component->component . "', ";
                                                                                                                                            } ?>])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>

</div>
<hr />

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <strong>Analysis of IPM use by the forming community</strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table_medium   table-bordered table-striped" id="table_3">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="15" class="text-center">Analysis of IPM use by the forming community</th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th colspan="2">Have knowledge of pest management</th>
                                <th colspan="2">Pesticide storage handling</th>
                                <th colspan="2">Understanding in managing unwanted surplus pesticide wast</th>
                                <th colspan="2">Practing green farm manure, use of organic conpost</th>
                                <th colspan="2">Observed any adverse impact due to the use of pesticides, chemical</th>
                                <th colspan="2">Environmental quality being monitored</th>
                                <th colspan="2">Understanding in applying a suitable timeframe for pesticides</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>No <span>%</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pest_mgmt_knowledge` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pest_mgmt_knowledge` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pest_mgmt_knowledge` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pest_mgmt_knowledge` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `pest_mgmt_knowledge` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pesticide_storage_handling` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pesticide_storage_handling` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pesticide_storage_handling` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pesticide_storage_handling` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `pesticide_storage_handling` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`surplus_pesticide_mgmt` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`surplus_pesticide_mgmt` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`surplus_pesticide_mgmt` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`surplus_pesticide_mgmt` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `surplus_pesticide_mgmt` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`green_manure_soil_practice` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`green_manure_soil_practice` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`green_manure_soil_practice` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`green_manure_soil_practice` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `green_manure_soil_practice` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pesticide_impact_observed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pesticide_impact_observed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pesticide_impact_observed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pesticide_impact_observed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `pesticide_impact_observed` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`env_quality_monitoring` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`env_quality_monitoring` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`env_quality_monitoring` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`env_quality_monitoring` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `env_quality_monitoring` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pesticide_timeframe_understanding` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pesticide_timeframe_understanding` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pesticide_timeframe_understanding` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pesticide_timeframe_understanding` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE region = '" . $region->region . "'
                        AND `pesticide_timeframe_understanding` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                    <td><?php echo $result->no_percentage; ?> </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Overall</th>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pest_mgmt_knowledge` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pest_mgmt_knowledge` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pest_mgmt_knowledge` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pest_mgmt_knowledge` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `pest_mgmt_knowledge` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pesticide_storage_handling` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pesticide_storage_handling` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pesticide_storage_handling` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pesticide_storage_handling` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `pesticide_storage_handling` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`surplus_pesticide_mgmt` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`surplus_pesticide_mgmt` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`surplus_pesticide_mgmt` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`surplus_pesticide_mgmt` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `surplus_pesticide_mgmt` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`green_manure_soil_practice` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`green_manure_soil_practice` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`green_manure_soil_practice` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`green_manure_soil_practice` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `green_manure_soil_practice` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pesticide_impact_observed` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pesticide_impact_observed` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pesticide_impact_observed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pesticide_impact_observed` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `pesticide_impact_observed` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`env_quality_monitoring` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`env_quality_monitoring` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`env_quality_monitoring` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`env_quality_monitoring` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `env_quality_monitoring` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`pesticide_timeframe_understanding` = 'Yes', 1, 0)) AS yes_count,
                        SUM(IF(`pesticide_timeframe_understanding` = 'No', 1, 0)) AS no_count,
                        ROUND(SUM(IF(`pesticide_timeframe_understanding` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage,
                        ROUND(SUM(IF(`pesticide_timeframe_understanding` = 'No', 1, 0)) / COUNT(*) * 100, 2) AS no_percentage
                        FROM `impact_surveys`
                        WHERE `pesticide_timeframe_understanding` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>
                                <td><?php echo $result->no_percentage; ?> </td>
                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <?php
    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();
    foreach ($components as $component) {
    ?>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <strong>Analysis of IPM use by the farming community <br />
                        Component <?php echo $component->component; ?></strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table_medium table-bordered table-striped" id="table_3_<?php echo $component->component; ?>">
                            <thead>
                                <tr style="display: none;">
                                    <th colspan="<?php echo count($regions) + 2; ?>" class="text-center">Analysis of IPM use by the farming community <br />
                                        Component <?php echo $component->component; ?></th>
                                </tr>
                                <tr>
                                    <th>IPM Practice</th>
                                    <?php foreach ($regions as $region) { ?>
                                        <th><?php echo ucfirst($region->region); ?></th>
                                    <?php } ?>
                                    <th>Overall</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Have knowledge of pest management -->
                                <tr>
                                    <th>Have knowledge of pest management</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                                        ROUND(SUM(IF(`pest_mgmt_knowledge` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND component = '" . $component->component . "'
                                        AND `pest_mgmt_knowledge` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                                        ROUND(SUM(IF(`pest_mgmt_knowledge` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE `pest_mgmt_knowledge` IN ('Yes', 'No')
                                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <!-- Pesticide storage handling -->
                                <tr>
                                    <th>Pesticide storage handling</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                                        ROUND(SUM(IF(`pesticide_storage_handling` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND component = '" . $component->component . "'
                                        AND `pesticide_storage_handling` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                                    ROUND(SUM(IF(`pesticide_storage_handling` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE `pesticide_storage_handling` IN ('Yes', 'No')
                                    AND component = '" . $component->component . "'
                                    ";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <!-- Understanding in managing unwanted surplus pesticide waste -->
                                <tr>
                                    <th>Understanding in managing unwanted surplus pesticide waste</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                                        ROUND(SUM(IF(`surplus_pesticide_mgmt` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND component = '" . $component->component . "'
                                        AND `surplus_pesticide_mgmt` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                                    ROUND(SUM(IF(`surplus_pesticide_mgmt` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE `surplus_pesticide_mgmt` IN ('Yes', 'No')
                                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <!-- Practicing green farm manure, use of organic compost -->
                                <tr>
                                    <th>Practicing green farm manure, use of organic compost</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                                        ROUND(SUM(IF(`green_manure_soil_practice` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND component = '" . $component->component . "'
                                        AND `green_manure_soil_practice` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                                    ROUND(SUM(IF(`green_manure_soil_practice` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE `green_manure_soil_practice` IN ('Yes', 'No')
                                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <!-- Observed any adverse impact due to the use of pesticides, chemical -->
                                <tr>
                                    <th>Observed any adverse impact due to the use of pesticides, chemical</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                                        ROUND(SUM(IF(`pesticide_impact_observed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND component = '" . $component->component . "'
                                        AND `pesticide_impact_observed` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                                    ROUND(SUM(IF(`pesticide_impact_observed` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE `pesticide_impact_observed` IN ('Yes', 'No')
                                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <!-- Environmental quality being monitored -->
                                <tr>
                                    <th>Environmental quality being monitored</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                                        ROUND(SUM(IF(`env_quality_monitoring` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND component = '" . $component->component . "'
                                        AND `env_quality_monitoring` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                                    ROUND(SUM(IF(`env_quality_monitoring` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE `env_quality_monitoring` IN ('Yes', 'No')
                                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <!-- Understanding in applying a suitable timeframe for pesticides -->
                                <tr>
                                    <th>Understanding in applying a suitable timeframe for pesticides</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                                        ROUND(SUM(IF(`pesticide_timeframe_understanding` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                        FROM `impact_surveys`
                                        WHERE region = '" . $region->region . "'
                                        AND component = '" . $component->component . "'
                                        AND `pesticide_timeframe_understanding` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                                    ROUND(SUM(IF(`pesticide_timeframe_understanding` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE `pesticide_timeframe_understanding` IN ('Yes', 'No')
                                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>


</div>
<style>
    .bg-pink {
        background-color: rgb(240, 61, 187) !important;
    }
</style>


<div style="margin-bottom: 10px;"></div>
<div class="row">
    <div class="col-md-8">
        <h4>Region Wise Female beneficiaries Analysis of KPIAIP</h4>
    </div>
    <div class="col-md-4" style="text-align: right;">
        <a target="new" href="<?php echo base_url('admin/impact_analysis/export_data/Female_Beneficiaries'); ?>" class="btn btn-success btn-sm"><i class="fa fa-download" aria-hidden="true"></i> Raw Data</a>
        <button class="btn btn-danger btn-sm" onclick="exportMultipleTablesToExcel('Female_Beneficiaries',['table_4', <?php foreach ($components as $component) {
                                                                                                                            echo "'table_4_" . $component->component . "', ";
                                                                                                                        } ?>], ['Summary', <?php foreach ($components as $component) {
                                                                                                                                                echo "'Component " . $component->component . "', ";
                                                                                                                                            } ?>])"><i class="fa fa-download" aria-hidden="true"></i> Export Data in Excel</button>
    </div>

</div>
<hr />

<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-header bg-pink text-white">
                <strong>Region Wise Female beneficiaries Analysis of KPIAIP </strong>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table_medium   table-bordered table-striped" id="table_4">
                        <thead>
                            <tr style="display: none;">
                                <th colspan="9" class="text-center">Region Wise Female beneficiaries Analysis of KPIAIP </th>
                            </tr>
                            <tr>
                                <th>Regions</th>
                                <th>Women Benefited</th>
                                <th>Easy access to clean water</th>
                                <th>Kitchen Gardening</th>
                                <th>Clean drinking water</th>
                                <th>Clean water for bathing and washing</th>
                                <th>Clean water for cloth washing place</th>
                                <th>More time for social and economic activities</th>
                                <th>Easy irrigation by women</th>
                                <th>Accessible road</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>
                                <th>Yes <span>%</span></th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($regions as $region) { ?>
                                <tr>
                                    <th><?php echo ucfirst($region->region); ?></th>
                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `watercourse_women_benefit` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?>
                                    </td>

                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`clean_water_access` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`clean_water_access` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `clean_water_access` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`kitchen_gardening` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`kitchen_gardening` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `kitchen_gardening` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`drinking_water` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`drinking_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `drinking_water` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`bathing_clean_water` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`bathing_clean_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `bathing_clean_water` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`clothes_washing_water` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`clothes_washing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `clothes_washing_water` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>


                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`women_time_social_economic` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`women_time_social_economic` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `women_time_social_economic` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>


                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`women_easy_irrigation` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`women_easy_irrigation` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `women_easy_irrigation` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>

                                    <?php
                                    $query = "SELECT 
                                    COUNT(*) AS total,
                                    SUM(IF(`road_accessible` = 'Yes', 1, 0)) AS yes_count,
                                    ROUND(SUM(IF(`road_accessible` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                                    FROM `impact_surveys`
                                    WHERE region = '" . $region->region . "'
                                    AND `road_accessible` IN ('Yes', 'No')";
                                    $result = $this->db->query($query)->row();  ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>


                                </tr>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Over All</th>
                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE `watercourse_women_benefit` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?>

                                </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`clean_water_access` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`clean_water_access` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE  `clean_water_access` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`kitchen_gardening` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`kitchen_gardening` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE  `kitchen_gardening` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`drinking_water` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`drinking_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE  `drinking_water` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`bathing_clean_water` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`bathing_clean_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE  `bathing_clean_water` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`clothes_washing_water` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`clothes_washing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE  `clothes_washing_water` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>


                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`women_time_social_economic` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`women_time_social_economic` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE  `women_time_social_economic` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>


                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`women_easy_irrigation` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`women_easy_irrigation` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE  `women_easy_irrigation` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>

                                <?php
                                $query = "SELECT 
                        COUNT(*) AS total,
                        SUM(IF(`road_accessible` = 'Yes', 1, 0)) AS yes_count,
                        ROUND(SUM(IF(`road_accessible` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                        FROM `impact_surveys`
                        WHERE `road_accessible` IN ('Yes', 'No')";
                                $result = $this->db->query($query)->row();  ?>
                                <td><?php echo $result->yes_percentage; ?> </td>


                            </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <?php
    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();
    foreach ($components as $component) {
    ?>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-pink text-white">
                    <strong>Region Wise Female beneficiaries Analysis of KPIAIP <br />
                        Component <?php echo $component->component; ?></strong>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table_medium   table-bordered table-striped" id="table_4_<?php echo $component->component; ?>">
                            <thead>
                                <tr style="display: none;">
                                    <th colspan="<?php echo count($regions) + 2; ?>" class="text-center">
                                        Region Wise Female beneficiaries Analysis of KPIAIP <br />
                                        Component <?php echo $component->component; ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th>Benefits</th>
                                    <?php foreach ($regions as $region) { ?>
                                        <th><?php echo ucfirst($region->region); ?></th>
                                    <?php } ?>
                                    <th>Over All</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>Women Benefited (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    COUNT(*) AS total,
                    SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) AS yes_count,
                    ROUND(SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `watercourse_women_benefit` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?></td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    COUNT(*) AS total,
                    SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) AS yes_count,
                    ROUND(SUM(IF(`watercourse_women_benefit` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `watercourse_women_benefit` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?></td>
                                </tr>

                                <tr>
                                    <th>Easy access to clean water (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`clean_water_access` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `clean_water_access` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`clean_water_access` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `clean_water_access` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Kitchen Gardening (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`kitchen_gardening` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `kitchen_gardening` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`kitchen_gardening` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `kitchen_gardening` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Clean drinking water (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`drinking_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `drinking_water` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`drinking_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `drinking_water` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Clean water for bathing and washing (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`bathing_clean_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `bathing_clean_water` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`bathing_clean_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `bathing_clean_water` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Clean water for cloth washing place (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`clothes_washing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `clothes_washing_water` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`clothes_washing_water` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `clothes_washing_water` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>More time for social and economic activities (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`women_time_social_economic` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `women_time_social_economic` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`women_time_social_economic` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `women_time_social_economic` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Easy irrigation by women (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`women_easy_irrigation` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `women_easy_irrigation` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`women_easy_irrigation` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `women_easy_irrigation` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>

                                <tr>
                                    <th>Accessible road (Yes %)</th>
                                    <?php foreach ($regions as $region) {
                                        $query = "SELECT 
                    ROUND(SUM(IF(`road_accessible` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE region = '" . $region->region . "'
                    AND component = '" . $component->component . "'
                    AND `road_accessible` IN ('Yes', 'No')";
                                        $result = $this->db->query($query)->row(); ?>
                                        <td><?php echo $result->yes_percentage; ?> </td>
                                    <?php } ?>
                                    <?php
                                    $query = "SELECT 
                    ROUND(SUM(IF(`road_accessible` = 'Yes', 1, 0)) / COUNT(*) * 100, 2) AS yes_percentage
                    FROM `impact_surveys`
                    WHERE `road_accessible` IN ('Yes', 'No')
                    AND component = '" . $component->component . "'";
                                    $result = $this->db->query($query)->row(); ?>
                                    <td><?php echo $result->yes_percentage; ?> </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>