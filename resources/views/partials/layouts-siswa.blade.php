<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
    <style>
        @media (min-width: 769px) {
            .content {
                margin-left: 270px !important;
                margin-top: 20px;
                padding: 30px 50px;
                min-height: calc(100vh - 95px);
            }
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0 !important;
                margin-top: 15px;
                padding: 20px 20px;
                min-height: auto;
            }
        }
    </style>
</head>
<body>
    @include('partials.navbar-siswa')

    <div class="content">
        @yield('content')
    </div>
</body>
</html>