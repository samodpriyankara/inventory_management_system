        <!-------Event Start------------>
        <div class="alert alert-success solid alert-dismissible fade" role="alert" id="success_alert" style="position:fixed;top:20px;left: 50%;">
          <i class="fa fa-check"></i> <strong>Success!</strong> <span id="success_msg"></span>
        </div>
        <!--------Event End----------->
                            
        <!-------Waiting  Upload Event Start------------>
        <div class="alert alert-warning solid alert-dismissible fade" role="alert" id="progress_upload_alert" style="position:fixed;top:20px;left: 50%;">
          <i class="fa fa-circle-o-notch fa-spin fa-fw"></i><span class="sr-only">Loading...</span> <strong>Please Wait...</strong>
            <div class="progress" style="height:20px">
                <div class="progress-bar bg-success" style="width:0%;" id="upload-bar"><span id="upload-bar-label">0%</span></div>
            </div>
        </div>
        <!--------Waiting Upload  Event End----------->                   
                            
        <!-------Waiting Event Start------------>
        <div class="alert alert-warning solid alert-dismissible fade" role="alert" id="progress_alert" style="position:fixed;top:20px;left: 50%;">
          <i class="fa fa-circle-o-notch fa-spin fa-fw"></i><span class="sr-only">Loading...</span> <strong>Please Wait...</strong>
        </div>
        <!--------Waiting Event End----------->
                            
        <!-------Error Event Start------------>
        <div class="alert alert-danger solid alert-dismissible fade" role="alert" id="danger_alert" style="position:fixed;top:20px;left: 50%;">
          <i class="fa fa-times"></i> <strong>Error!</strong> <span>Something went wrong...</span>
        </div>
        <!--------Error Event End----------->

        <!-------Error Msg Event Start------------>
        <div class="alert alert-danger solid alert-dismissible fade" role="alert" id="danger_alert_msg" style="position:fixed;top:20px;left: 50%;">
          <i class="fa fa-times"></i> <strong>Error!</strong> <span id="error_msg"></span>
        </div>
        <!--------Error Msg Event End----------->