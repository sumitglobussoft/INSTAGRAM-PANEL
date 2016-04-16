<!DOCTYPE html>
<html>

<head>
    @include('Supplier/Layouts/supplierheadscripts')
</head>

<body>
<div class="login-content">
    <div class="branding text-center">
        <a href="" class="logo"> I N S T A P A N E L </a>
    </div>
</div>


<div class="login">


    <form method="post" id="resetPasswordForm" style="margin-bottom: 3%;">
        @if (count($errors) > 0)
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li style="color: red; font-size: 14px; text-align: center">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="password" name="newPassword" id="newPassword" placeholder="Enter New Password" required><br>
        <input type="password" name="conformNewPassword" id="conformNewPassword"
               placeholder="Enter your conform Password" required>
        <button type="submit" class="btn btn-primary btn-block btn-large">Reset Password</button>
    </form>
</div>

@include('Supplier/Layouts/suppliercommonfooterscripts')
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
//
//        $('#resetPasswordForm').validate({
//            rules: {
//                newPassword: {
//                    required: true
//                },
//                conformNewPassword: {
//                    required: true,
//                    equalTo: "#newPassword"
//                }
//            }
//        });
    });

</script>
</body>

</html>




