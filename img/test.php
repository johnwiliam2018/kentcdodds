<?php
$page_title = "Test APIs";
$page_slug = "test-apis";
require ('library/admin_application_top.php');

require ('views/header.php');
?>

<style>
    #testApiLabel {
        font-style: italic;
    }

    #testApiList button {
        text-align: left;
    }	
</style>

<!-- page content -->
<div class="right_col" role="main">
    <div class="">
        <div class="row">
            <div class="col-sm-2">
                <div class="x_panel">
                    <div class="x_title">
                        <h2>APIs</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div class="btn-group-vertical" id="testApiList">
                            <?php
                            $apis = array(
								"user_signup" => "USER SIGNUP",
                                "user_login" => "USER LOGIN",
                                "imageupload" => "UPLOAD IMAGE"
                            );
                            ?>
                            <?php foreach ($apis as $api => $label): ?>
                                <button class="btn btn-default" type="button" data-api="<?php echo $api; ?>" href="#testApiContent">
                                    <?php echo $label; ?>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-10" id="testApiContent">
                <div class="x_panel margin-bottom-50">
                    <div class="x_title">
                        <h2>Test Input Form</h2>
                        <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                        <div id="testApiForm">
                            <p>
                                Please select API
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Test Result</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <iframe name="testApiResult" id="testApiResult" style="width: 100%; height: 400px; border: 1px dotted #ccc;"></iframe>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="x_panel">
                            <div class="x_title">
                                <h2>Result Structure</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <strong>Success: {</strong>
                                <br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;errorcode: 0
                                <br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;message: (string)
                                <br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;result: (object)
                                <br/>
                                }
                                <br/>
                                <br/>
                                <br/>
                                <strong>Error: {</strong>
                                <br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;errorcode: (>0)
                                <br/>
                                &nbsp;&nbsp;&nbsp;&nbsp;errors: (array)
                                <br/>
                                }
                                <br/>
                                <br/>
                                <br/>
                                <strong>Error Codes:</strong>
                                <br/>
                                <ul style="list-style:decimal;">
                                    <li>
                                        ERRORCODE_INPUT_VALUES
                                    </li>
                                    <li>
                                        ERRORCODE_USERID
                                    </li>
                                    <li>
                                        ERRORCODE_PASSWORD
                                    </li>
                                    <li>
                                        ERRORCODE_SECURITY
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /page content -->

<script>
    $(function () {
        $("#testApiList button").click(function () {
            $("#testApiList button").removeClass("selected");
            $(this).addClass("selected");
            $("#testApiResult").attr('src', "testapiforms/empty.php");

            var api = $(this).attr("data-api");
            $.post("testapiforms/" + api + ".php", {
                "api": api
            }, function (data) {
                $("#testApiForm").html(data);
                $("#form-" + api).find("#em-api").attr("readonly", "readonly");
                $("#form-" + api).find("#em-device").attr("readonly", "readonly");
                initInputFileGroup();
            });
        });
    })
</script>

<?php
require ('views/footer.php');
