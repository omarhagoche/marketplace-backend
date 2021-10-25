<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-gXt9imSW0VcJVHezoNQsP+TNrjYXoGcrqBZJpry9zJt8PCQjobwmhMGaDHTASo9N" crossorigin="anonymous">

    <title>تسجيل مطعم</title>
</head>

<body class="bg-light">


    <div class="container py-5">

        <h1 class="text-center mb-5">تسجيل مطعم</h1>

        <form action="{{ url('register-restaurant') }}" method="post" class="needs-validation" novalidate>

            @csrf


            <input type="hidden" name="latitude" id="latitude" />
            <input type="hidden" name="longitude" id="longitude" />


            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            @endif


            @if( Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <p>{{ Session::get('success') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif




            <div class=" row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">اسم المطعم</label>
                    <input type="text" class="form-control" name="name" value="{{ request()->old('name') }}"
                        minlength="3" maxlength="100" required>
                    <div class="invalid-feedback">يجب أن يكون الاسم بين 3 إلى 100 حرف</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">عنوان المطعم</label>
                    <input type="text" class="form-control" name="address" value="{{ request()->old('address') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">رقم تليفون المطعم</label>
                    <input type="text" class="form-control" name="phone" value="{{ request()->old('phone') }}"
                        placeholder="9XXXXXXXX" pattern="^+0[9][12345][0-9]{7}$" required maxlength="9">
                    <div class="invalid-feedback">يجب أن يكون رقم التليفون بالتنسيق (9100000000)</div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">رقم تليفون المطعم2</label>
                    <input type="text" class="form-control" name="mobile" value="{{ request()->old('mobile') }}"
                        placeholder="9XXXXXXXX" pattern="^+0[9][12345][0-9]{7}$"   maxlength="9">
                    <div class="invalid-feedback">يجب أن يكون رقم التليفون بالتنسيق (9100000000)</div>
                </div>
            </div>

            <hr />

            <div class="row">
                {{-- <div class="col-md-6 mb-3">
                    <label class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control" name="user_name" value="{{ request()->old('user_name') }}"
                        minlength="3" maxlength="32" required>
                    <div class="invalid-feedback">يجب أن يكون الاسم بين 3 إلى 100 حرف</div>
                </div> --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">رقم تليفون المستخدم</label>
                    <input type="text" class="form-control" name="user_phone" value="{{ request()->old('user_phone') }}"
                        placeholder="9XXXXXXXX" pattern="^+0[9][12345][0-9]{7}$" required maxlength="9">
                    <div class="invalid-feedback">يجب أن يكون رقم التليفون بالتنسيق (9100000000)</div>
                </div>
                <!-- <div class="col-md-6 mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control" name="user_email">
            </div> -->
            </div>

            <hr />

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">أضيف بواسطة</label>
                    <input type="text" class="form-control" name="added_by" value="{{ request()->old('added_by') }}"
                        minlength="3" maxlength="32" required>
                    <div class="invalid-feedback">يجب أن يكون الاسم بين 3 إلى 100 حرف</div>
                </div> 
            </div>


            <div class="col-12 mt-3">
                <button class="btn btn-primary" style="width:100%;" type="submit">حفظ</button>
            </div>

        </form>

    </div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()




        let latText = document.getElementById("latitude");
        let longText = document.getElementById("longitude");


        if (navigator.geolocation) {
            // Call getCurrentPosition with success and failure callbacks
            navigator.geolocation.getCurrentPosition(function (position) {
                latText.value = position.coords.latitude;
                longText.value = position.coords.longitude;
            }, function error(err) {
                alert(`ERROR(${err.code}): ${err.message}`);
            })
        }
        else {
            alert("عذرا ، متصفحك لا يدعم خدمات تحديد الموقع الجغرافي");
        }


    </script>
</body>

</html>