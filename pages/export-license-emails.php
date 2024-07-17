<?php

function export_license_emails()
{

    global $wpdb;
    $results = $wpdb->get_results("SELECT email FROM wp_license_management;");

    $emails = [];
    foreach ($results as $result) {
        array_push($emails, $result->email);
    }

    $string_emails = implode(PHP_EOL, $emails);
    file_put_contents(WP_CONTENT_DIR . '/license_emails.txt', $string_emails);

?>
    <div class="wrap">
        <h1 class="text-bold my-3 h4">License management emails!</h1>
        <div>
            <p>Every time you visited this page, the text file is updates. Just click on the download button to download license emails list.</p>
            <a download href="<?php echo home_url() . '/wp-content/license_emails.txt'; ?>" id="generate-text-file" class="button button-primary">
                Download text file
            </a>
        </div>
    </div>
<?php
}
