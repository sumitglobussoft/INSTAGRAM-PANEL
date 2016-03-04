<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Conversation</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        .container-raise-t {
            padding: 60px;
        }
        .container-raise-t .col-panel{
            margin-left: 30%;
            margin-top: 3%;
        }
    </style>
</head>
<body>

<div class="container container-raise-t">
    <div class="row">
        <div class="col-md-4 col-panel">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <p class="panel-title">
                        User Name
                    </p>
                </div>
                <div class="panel-body">
                    <form method="post" action="" id="ticketsend">
                        <div class="alert alert-success alert-dismissible hide" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <div>Show Success </div>
                        </div>
                        <div class="alert alert-danger alert-dismissible hide" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <div>Show Failure</div>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="control-label">Subject</label>
                            <input type="text" class="form-control" name="subject" id="subjet">
                        </div>

                        <div class="form-group">
                            <label for="message" class="control-label">Message</label>
                            <textarea id="text" name="text" rows="5" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-default" id="send">Submit Tickets</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
<script>

</script>

</body>
</html>