<?php
$query = "SELECT * FROM impact_quarters";
$impact_quarters = $this->db->query($query)->result();
$query = "SELECT `district`, `region` FROM `impact_surveys` GROUP BY `region`, `district` ASC;";
$districts = $this->db->query($query)->result();
?>
<table class="table table-bordered table_small ">
    <thead>
        <tr>
            <th colspan="<?php echo count($impact_quarters) + 2; ?>"><?php echo $title; ?></th>
        </tr>
        <tr>
            <th rowspan="3">Regions</th>
            <th rowspan="3">districts</th>
            <th colspan="<?php echo count($impact_quarters); ?>"><?php echo $description; ?></th>
            <th rowspan="3">Cumulative</th>
        </tr>
        <tr>
            <?php foreach ($impact_quarters as $impact_quarter) { ?>
                <th> <?php echo $impact_quarter->impact_quarter; ?> <?php if ($impact_quarter->status == 1) { ?> * <?php } ?>

                </th>
                </a>
            <?php } ?>
        </tr>
        <tr>
            <?php foreach ($impact_quarters as $impact_quarter) { ?>
                <th><?php echo date('M', strtotime($impact_quarter->quarter_start_date)); ?>
                    -
                    <?php echo date('M y', strtotime($impact_quarter->quarter_end_date)); ?>
                <?php } ?>
                </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($districts as $district) { ?>
            <tr>
                <th><?php echo ucfirst($district->region) ?></th>
                <th><?php echo ucfirst($district->district) ?></th>
                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                    <?php
                    $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE district = ? AND impact_quarter_id = ?";
                    $survey_count = $this->db->query($query, [$district->district, $impact_quarter->impact_quarter_id])->row();
                    ?>
                    <td><?php echo $survey_count->total;  ?></td>
                <?php } ?>
                <?php
                $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE district = ?";
                $survey_count = $this->db->query($query, [$district->district])->row();
                ?>
                <th><?php echo $survey_count->total;  ?></th>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Total</th>
            <?php foreach ($impact_quarters as $impact_quarter) { ?>
                <?php
                $query = "SELECT COUNT(*) as total FROM `impact_surveys` WHERE  impact_quarter_id = ?";
                $survey_count = $this->db->query($query, [$impact_quarter->impact_quarter_id])->row();
                ?>
                <th><?php echo $survey_count->total;  ?></th>
            <?php } ?>
            <?php
            $query = "SELECT COUNT(*) as total FROM `impact_surveys`";
            $survey_count = $this->db->query($query)->row();
            ?>
            <td><?php echo $survey_count->total;  ?></td>
        </tr>
    </tfoot>
</table>