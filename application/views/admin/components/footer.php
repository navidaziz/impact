<div class="separator"></div>
</div>
</div>
</div>
</div>
</section>
<style>
    .dt-buttons {
        display: inline;
    }

    .dt-button-collection {
        margin-top: 5px !important;
    }
</style>

<div class="modal" id="g_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" id="g_modal_body">

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"
    src="<?php echo site_url("assets/" . ADMIN_DIR); ?>/js/magic-suggest/magicsuggest-1.3.1-min.js"></script>
<script
    src="<?php echo site_url("assets/" . ADMIN_DIR . "js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"); ?>">
</script>
<script src="<?php echo site_url("assets/" . ADMIN_DIR . "js/script.js"); ?>"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        App.setPage("widgets_box");
        App.init();
    });
</script>
<link href="<?php echo site_url("assets/" . ADMIN_DIR . "font-awesome/css/font-awesome.min.css"); ?>"
    rel="stylesheet" />
<?php
if ($this->router->fetch_method() == 'add_order_new') { ?>
    <script src="<?php echo site_url("assets/" . ADMIN_DIR . "js/jquery-1.min.js"); ?>"></script>
<?php } else { ?>

    <script src="<?php echo site_url("assets/" . ADMIN_DIR . "js/jquery-0.min.js"); ?>"></script>
<?php } ?>

<script>
    function convertNumberToWords(inputId) {

        const numberInput = $('#' + inputId).val();
        const resultElement = document.getElementById('resultWords');
        const inwords = numberToWords(numberInput);
        resultElement.innerHTML = '<span style="color:green">' + inwords + '<span>';

    }

    function numberToWords(num) {
        const ones = [
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
            'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];
        const tens = [
            '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
        ];
        const thousands = ['', 'thousand', 'million', 'billion'];

        if (num === 0) return 'zero';

        let words = '';
        let thousandIndex = 0;

        while (num > 0) {
            const remainder = num % 1000;
            if (remainder > 0) {
                const currentWords = convertThreeDigitNumberToWords(remainder);
                words = currentWords + (thousands[thousandIndex] ? ' ' + thousands[thousandIndex] : '') + ' ' + words;
            }
            num = Math.floor(num / 1000);
            thousandIndex++;
        }

        return words.trim();
    }

    function convertThreeDigitNumberToWords(num) {
        const ones = [
            '', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
            'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];
        const tens = [
            '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
        ];

        let words = '';

        if (num >= 100) {
            words += ones[Math.floor(num / 100)] + ' hundred';
            num %= 100;
            if (num > 0) {
                words += ', ';
            }
        }

        if (num >= 20) {
            words += tens[Math.floor(num / 10)];
            num %= 10;
            if (num > 0) {
                words += '-' + ones[num];
            }
        } else if (num > 0) {
            words += ones[num];
        }

        return words;
    }
</script>

<link rel="stylesheet" type="text/css"
    href="<?php echo site_url("assets/" . ADMIN_DIR . "js/magic-suggest/magicsuggest-1.3.1-min.css"); ?>" />
<style>
    .table-responsive {
        overflow-x: auto !important;
    }
</style>





</body>

</html>