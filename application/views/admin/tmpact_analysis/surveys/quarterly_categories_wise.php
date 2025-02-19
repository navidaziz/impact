<?php
if ($impact_quarter_id) {
    $query = "SELECT * FROM impact_quarters
    WHERE impact_quarter_id = ?";
    $impact_quarters = $this->db->query($query, [$impact_quarter_id])->result();
} else {
    $query = "SELECT * FROM impact_quarters";
    $impact_quarters = $this->db->query($query)->result();
}
if ($region) {
    $query = "SELECT `district`, `region` FROM `impact_surveys` 
WHERE region = ? 
GROUP BY `region`, `district` ASC;";
    $districts = $this->db->query($query, [$region])->result();
} else {
    $query = "SELECT `district`, `region` FROM `impact_surveys` 
    GROUP BY `region`, `district` ASC;";
    $districts = $this->db->query($query)->result();
}
$query = "SELECT `category` FROM `impact_surveys` GROUP BY `category` ORDER BY `category` ASC";
$sub_categorys = $this->db->query($query)->result();
?>
<table class="table table-bordered table_small ">
    <thead>
        <tr>
            <th colspan="<?php echo count($sub_categorys); ?>"><?php echo $title; ?></th>
        </tr>
        <tr>
            <th rowspan="4">Regions</th>
            <th rowspan="4">districts</th>
            <th colspan="<?php echo count($sub_categorys); ?>"><?php echo $description; ?></th>
            <th rowspan="4">Cumulative</th>
        </tr>
        <tr>
            <?php foreach ($impact_quarters as $impact_quarter) { ?>
                <th colspan="<?php echo count($sub_categorys) ?>"> <?php echo $impact_quarter->impact_quarter; ?> <?php if ($impact_quarter->status == 1) { ?> <span style="color: green !important; font-weight:bold">*</span> <?php } ?>

                </th>
                </a>
            <?php } ?>
        </tr>
        <tr>
            <?php foreach ($impact_quarters as $impact_quarter) { ?>
                <th colspan="<?php echo count($sub_categorys) ?>"><?php echo date('M', strtotime($impact_quarter->quarter_start_date)); ?>
                    -
                    <?php echo date('M y', strtotime($impact_quarter->quarter_end_date)); ?>
                </th>
            <?php } ?>

        </tr>
        <tr>
            <?php foreach ($impact_quarters as $impact_quarter) { ?>
                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                    <?php foreach ($sub_categorys as $sub_category) { ?>
                        <th><?php echo $sub_category->category; ?></th>
                    <?php } ?>
                <?php } ?>
            <?php  } ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($districts as $district) { ?>
            <tr>
                <th><?php echo ucfirst($district->region) ?></th>
                <th><?php echo ucfirst($district->district) ?></th>
                <?php
                $cumulative = 0;
                foreach ($impact_quarters as $impact_quarter) { ?>

                    <?php foreach ($impact_quarters as $impact_quarter) { ?>
                        <?php foreach ($sub_categorys as $sub_category) { ?>
                            <?php
                            $query = "SELECT COUNT(*) as total FROM `impact_surveys` 
                              WHERE district = ? 
                              AND impact_quarter_id = ?
                              AND category = ? ";

                            $survey_count = $this->db->query($query, [$district->district, $impact_quarter->impact_quarter_id, $sub_category->category])->row();
                            ?>
                            <td><?php
                                $cumulative += $survey_count->total;
                                echo $survey_count->total;  ?></td>
                        <?php } ?>
                    <?php } ?>

                <?php } ?>
                <th><?php echo $cumulative;  ?></th>


            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th></th>
            <th>Total</th>
            <?php
            $cumulative = 0;
            foreach ($impact_quarters as $impact_quarter) { ?>

                <?php foreach ($impact_quarters as $impact_quarter) { ?>
                    <?php foreach ($sub_categorys as $sub_category) { ?>
                        <?php
                        $query = "SELECT COUNT(*) as total FROM `impact_surveys` 
                              WHERE impact_quarter_id = ?
                              AND category = ? ";

                        $survey_count = $this->db->query($query, [$impact_quarter->impact_quarter_id, $sub_category->category])->row();
                        ?>
                        <td><?php
                            $cumulative += $survey_count->total;
                            echo $survey_count->total;  ?></td>
                    <?php } ?>
                <?php } ?>

            <?php } ?>
            <th><?php echo $cumulative;  ?></th>


        </tr>
    </tfoot>

</table>