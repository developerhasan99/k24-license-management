<?php 

function email_sending_report() {
    ?> 
    <style>
        p {
            font-size: 16px;
        }
        div#view-full-report {
            top: 100px;
        }

        div#view-full-report .modal-body {
            max-height: 60vh;
            overflow-y: scroll;
        }

        .modal-loader {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .modal-loader .spinner-border {
            margin-right: 5px;
        }
    </style>

    <div class="wrap">
        <h1 class="text-bold my-3 h4">Email Sending Report!</h1>
        <div>
            <table id="email_sending_report" class="table table-striped table-bordered dt-responsive">
                <thead>
                    <tr>
                        <th>S/L</th>
                        <th>DateTime</th>
                        <th>Email Sent</th>
                        <th>Ignored</th>
                        <th>Script</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Modal -->
<div class="modal fade" id="view-full-report" tabindex="-1" role="dialog" aria-labelledby="view-full-report"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        </div>
    </div>
</div>

    <script type="text/javascript">
(function($) {

    $(document).ready(function() {

        var email_sending_report = $('#email_sending_report').DataTable({
            "autoWidth": false,
            "pageLength": 50,
            "responsive": true,
            "ordering": false,
            "processing": true,
            "serverSide": true,
            "scrollY": "650px",
            "scrollCollapse": true,
            "ajax": {
                "url": '<?php echo admin_url('admin-ajax.php'); ?>',
                "data": function(d) {
                    d.action = 'get_email_sending_report'
                }
            }
        });

        $(document).on('click', '.view_full_report', function(e) {

            e.preventDefault();
            $('#view-full-report').modal('toggle');

            $('#view-full-report .modal-content').html(
                '<div class="modal-loader"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  Loading...<div>'
            ).prop('disabled', true);

            $.ajax({
                type: "POST",
                dataType: "json",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                data: {
                    action: 'get_email_sending_full_report',
                    id: $(this).data('id'),
                    security: '<?php echo wp_create_nonce('security'); ?>'
                },
                success: function(response) {
                    $('#view-full-report .modal-content').html(response.data.html);
                },
                error: function(request, status, error) {
                    console.log(request.responseText);
                }
            });

        });

    });
})(jQuery);
</script>
<?php

}