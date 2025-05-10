<h4>Private Investment in PKR (Community Share)</h4>
<hr />
<div class="row">
    <div class="col-md-12">
        <table class="table    table-bordered table-striped">
            <thead>
                <tr>
                    <th colspan="13" class="text-center">Private Investment in PKR (Community Share)</th>
                </tr>
                <tr>
                    <th>Regions</th>
                    <th>KPIAIP Share Per Scheme (Average)</th>
                    <th>Community Share Per Scheme (Average)</th>
                    <th>Community Share %</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                $regions = $this->db->query($query)->result();

                ?>
                <?php foreach ($regions as $region) { ?>
                    <tr>
                        <th><?php echo $region->region; ?></th>
                        <?php
                        $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`
                            WHERE region = '" . $region->region . "'";
                        $result = $this->db->query($query)->row();

                        // Calculate community share percentage
                        $community_share_percentage = 0;
                        if ($result->avg_actual_cost > 0) {
                            $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                        }
                        ?>
                        <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                        <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                        <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                    </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Overall</th>
                    <?php
                    $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`";
                    $result = $this->db->query($query)->row();

                    // Calculate community share percentage
                    $community_share_percentage = 0;
                    if ($result->avg_actual_cost > 0) {
                        $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                    }
                    ?>
                    <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                    <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                    <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php
    $query = "SELECT `component` FROM `impact_surveys` GROUP BY `component` ORDER BY `component` ASC";
    $components = $this->db->query($query)->result();
    foreach ($components as $component) {  ?>
        <div class="col-md-6">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th colspan="13" class="text-center">Private Investment in PKR (Community Share) <br />
                            Component <?php echo $component->component; ?>
                        </th>
                    </tr>
                    <tr>
                        <th>Regions</th>
                        <th>KPIAIP Share Per Scheme (Average)</th>
                        <th>Community Share Per Scheme (Average)</th>
                        <th>Community Share %</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT `region` FROM `impact_surveys` GROUP BY `region` ASC;";
                    $regions = $this->db->query($query)->result();

                    ?>
                    <?php foreach ($regions as $region) { ?>
                        <tr>
                            <th><?php echo $region->region; ?></th>
                            <?php
                            $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`
                            WHERE region = '" . $region->region . "'
                            AND component = '" . $component->component . "'";
                            $result = $this->db->query($query)->row();

                            // Calculate community share percentage
                            $community_share_percentage = 0;
                            if ($result->avg_actual_cost > 0) {
                                $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                            }
                            ?>
                            <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                            <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                            <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th>Overall</th>
                        <?php
                        $query = "SELECT 
                            AVG(actual_cost) as avg_actual_cost,
                            AVG(community_share) as avg_community_share
                            FROM `impact_surveys`
                            WHERE component = '" . $component->component . "'";
                        $result = $this->db->query($query)->row();

                        // Calculate community share percentage
                        $community_share_percentage = 0;
                        if ($result->avg_actual_cost > 0) {
                            $community_share_percentage = ($result->avg_community_share / $result->avg_actual_cost) * 100;
                        }
                        ?>
                        <td><?php echo number_format($result->avg_actual_cost, 2); ?></td>
                        <td><?php echo number_format($result->avg_community_share, 2); ?></td>
                        <td><?php echo number_format($community_share_percentage, 2); ?>%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php } ?>
</div>