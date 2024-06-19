<!DOCTYPE html>
<html lang="fa" dir="rtl">
	<head>

		<!-- Meta data -->
		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta content="DayOne - It is one of the Major Dashboard Template which includes - HR, Employee and Job Dashboard. This template has multipurpose HTML template and also deals with Task, Project, Client and Support System Dashboard." name="description">
		<meta content="Spruko Technologies Private Limited" name="author">
		<meta name="keywords" content="admin dashboard, admin panel template, html admin template, dashboard html template, bootstrap 4 dashboard, template admin bootstrap 4, simple admin panel template, simple dashboard html template,  bootstrap admin panel, task dashboard, job dashboard, bootstrap admin panel, dashboards html, panel in html, bootstrap 4 dashboard"/>

		<!-- Title -->
		<title> ورود به پنل </title>
		<link href="{{asset('assets/font/font.css')}}" rel="stylesheet"/>
		<!--Favicon -->
		<link href="{{asset('assets/images/brand/favicon.ico')}}" rel="icon" type="image/x-icon"/>
		<!-- Bootstrap css -->
		<link href="{{asset('assets/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/css-rtl/style.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css-rtl/style.css')}}" rel="stylesheet">
		<link href="{{asset('assets/css-rtl/dark.css')}}" rel="stylesheet" />
		<link href="{{asset('assets/css-rtl/skin-modes.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/css/main.css')}}" rel="stylesheet" >
		<!-- Animate css -->
		<link href="{{asset('assets/css-rtl/animated.cs')}}s" rel="stylesheet" />
		<!---Icons css-->
		<link href="{{asset('assets/css-rtl/icons.css')}}" rel="stylesheet" />
		<!-- Select2 css -->
		<link href="{{asset('assets/plugins/select2/select2.min.cs')}}s" rel="stylesheet" />
		<!-- P-scroll bar css-->
		<link href="{{asset('assets/plugins/p-scrollbar/p-scrollbar.css')}}" rel="stylesheet" />
	</head>

	<body>

		<div class="page login-bg">
			<div class="page-single">
				<div class="container">
					<div class="row">
						<div class="col mx-auto">
							<div class="row justify-content-center">
								<div class="col-md-7 col-lg-5">
									<div class="card">
										<div class="p-4 pt-6 text-center">
											<p class="mb-2 fs-24">ورود به پنل</p>
                      @if ($errors->any())
                        <div class="alert alert-danger ">
                          <span>اطلاعات وارد شده صحیح نمیباشد.</span>
                        </div>
                      @endif
										</div>
										<form class="card-body pt-3" id="login" action="{{route("login")}}" name="login" method="POST">
                      @csrf
											<div class="form-group">
												<label for="mobile" class="form-label">تلفن همراه:</label>
												<input id="mobile" name="mobile" class="form-control" placeholder="شماره همراه خود را وارد کنید" type="text">
											</div>
											<div class="form-group">
												<label for="password" class="form-label">کلمه عبور:</label>
												<input id="password" name="password" class="form-control" placeholder="کلمه عبور خود را وارد کنید" type="password">
											</div>
                      <div class="form-group">
                        <label class="custom-control custom-checkbox">
                          <input type="checkbox" class="custom-control-input" name="example-checkbox1" value="option1">
                          <span class="custom-control-label">مرا به خاطر بسپر</span>
                        </label>
                      </div>
											<div class="submit">
												<button class="btn btn-primary btn-block">ورود</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Jquery js-->
		<script src="{{ asset('assets/plugins/jquery/jquery.min.js')}}"></script>
		<!-- Bootstrap4 js-->
		<script src="{{ asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
		<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
		<!-- Select2 js -->
		<script src="{{ asset('assets/plugins/select2/select2.full.min.js')}}"></script>
		<!-- P-scroll js-->
		<script src="{{ asset('assets/plugins/p-scrollbar/p-scrollbar.js')}}"></script>
		<!-- Custom js-->
		<script src="{{ asset('assets/js/custom.js')}}"></script>

	</body>
</html>
