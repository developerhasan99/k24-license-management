<?php

function export_unsubscribers_emails()
{

    global $wpdb;
    $results = $wpdb->get_results("SELECT email FROM unsubscriber_emails;");

    $emails = [];
    foreach ($results as $result) {
        array_push($emails, $result->email);
    }

    $string_emails = implode(PHP_EOL, $emails);
    file_put_contents(WP_CONTENT_DIR . '/unsubscriber_emails.txt', $string_emails);

?>
    <div class="wrap">
        <h1 class="text-bold my-3 h4">Unsubscribers list!</h1>
        <div>
            <p>Every time you visited this page, the text file is updates. Just click on the download button to download unsubscribers list.</p>
            <a download href="<?php echo home_url() . '/wp-content/unsubscriber_emails.txt'; ?>" id="generate-text-file" class="button button-primary">
                Download text file
            </a>
        </div>
    </div>
<?php
}
