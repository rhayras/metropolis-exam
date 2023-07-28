@extends('layout')

@section('content')
    <div class="d-flex justify-content-center align-items-center">
      <div class="container">
        <div class="row d-flex justify-content-center">
          <div class="col-12 col-md-8 col-lg-6">
            <div class="card bg-white">
              <div class="card-body p-5">
                <div class="alert alert-danger print-error-msg" style="display:none">
                    <ul></ul>
                </div>
                <form class="mb-3 mt-md-4" method="POST" id="loginForm">
                  @csrf
                  <div class="mb-3">
                    <label for="email" class="form-label ">Email address</label>
                    <input type="text" class="form-control" name="email" id="email" placeholder="name@example.com" required>
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label ">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="*******" required>
                  </div>
                  <div class="d-grid">
                    <button class="btn btn-outline-dark" type="submit">Login</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection

@section('scripts')

<script>
  

  $(document).ready(function(){
    $("#loginForm").on('submit', (e) => {
      e.preventDefault();
      var errFlag = 0;

      var email = $('#email').val();
      var password = $('#password').val();

      if(email == "")  { errFlag = 1; }
      if(password == "")  { errFlag = 1; }
      
      if(errFlag == 1){
        alert("All fields are required!");
      }else{
        $.ajax({
          url: "{{ url('login') }}",
          type:"POST",
          data:{
            "_token": "{{ csrf_token() }}",
            "email":email,
            "password":password,
          },
          success: function(data) {
            $(".print-error-msg").find("ul").html('');
            $(".print-error-msg").css('display','none');
              if($.isEmptyObject(data.error)){
                  if(!data.success){
                    alert(data.msg);
                  }else{
                    window.location.href = "{{ url('dashboard') }}";
                  }
              }else{
                  printError(data.error);
              }
          }
        });
      }
    });
  });
</script>
@endsection
