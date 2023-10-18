<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SAKA SASMITRA - Login</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('public/sbadmin/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('public/sbadmin/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('public/plugins/sweetalert/sweetalert2.min.css') }}" rel="stylesheet">
    
    <style>
        form.user .form-control-user{
            font-size: .9rem;
        }
    </style>
</head>

<body style="background: #f6f9ff">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
    
            <!-- Outer Row -->
            <div class="row justify-content-center">
    
                <div class="col-xl-6 col-lg-6 col-md-6">

                    <div class="d-flex justify-content-center align-items-center py-4" style="gap: 13px">
                        <img src="{{ asset('public\sbadmin\img\logo-sasmitra.png') }}" width="50" alt="logo_sasimitra">
                        <a style="font-weight: 700; font-size: 30px; color: #012970">Akuntansi Apotek</a>
                    </div>
                    <div class="card o-hidden border-0 shadow-lg" style="box-shadow: 0px 0 30px rgba(1, 41, 112, 0.1)">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <div class="text-center mb-5">
                                            <h1 class="h4 text-center mt-4" style="color: #012970; font-weight: 500; font-family: 'Poppins', sans-serif">Login</h1>
                                            <p>Enter your email & password to login</p>
                                        </div>
                                        <form class="user" action="{{ route('post.login') }}" method="POST">
                                            @csrf
                                            <div class="form-group">
                                                <label for="email" class="ml-2">Email</label>
                                                <input type="email" class="form-control form-control-user"
                                                    id="email" aria-describedby="emailHelp"
                                                    name="username"
                                                    placeholder="Enter Email Address...">
                                            </div>
                                            <div class="form-group">
                                                <label for="password" class="ml-2">Password</label>
                                                <div class="position-relative">
                                                    <input type="password" class="form-control form-control-user"
                                                    name="password"
                                                        id="password" placeholder="Password">
                                                    <i class="fas fa-eye-slash" id="hide_password" style="position: absolute; right: 25px; top: 16px; cursor: pointer"></i>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                            
                                            
                                        </form>
                                        <hr>
                                        <div class="text-center mt-2">
                                            © <a href="#">SAKA SASMITRA 2023</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
    
                </div>
    
            </div>
    
        </div>
    </section>


    <!-- Bootstrap core JavaScript-->
    <script src="{{ "public/sbadmin/vendor/jquery/jquery.min.js" }}"></script>
    <script src="{{ "public/sbadmin/vendor/bootstrap/js/bootstrap.bundle.min.js" }}"></script>
    <script src="{{ "public/plugins/sweetalert/sweetalert.min.js" }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ "public/sbadmin/vendor/jquery-easing/jquery.easing.min.js" }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ "public/sbadmin/js/sb-admin-2.min.js" }}"></script>

    <script>
  $(function(){
		@if(\Illuminate\Support\Facades\Session::has('message'))
	    swal({
	      icon: '{{ \Illuminate\Support\Facades\Session::get('message_type') }}',
	      text: '{{ \Illuminate\Support\Facades\Session::get('message') }}',
	      button: false,
	      timer: 1500
	    });
    @endif
  });

$('#hide_password').on('click', function(){
    if($(this).hasClass('fa-eye-slash')){
        $(this).removeClass('fa-eye-slash');
        $(this).addClass('fa-eye');
        $(this).siblings().attr('type', 'text');
    } else{
        $(this).removeClass('fa-eye');
        $(this).addClass('fa-eye-slash');
        $(this).siblings().attr('type', 'password');
    }
})
	</script>

</body>

</html>