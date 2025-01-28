<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Custom styles for this template-->
<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('dashboard.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Header -->
                @include('dashboard.header')

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- User List Table -->
                    <div class="container mt-2">
                            <h1 class="card-body text-center mb-4">List of Users</h1>
                        </div>
                        
                        <a href="{{ route('register') }}" class="btn btn-success float-right ml-3">Add a new User</a>

                        <!-- Table -->
                        <table class=" card-body table table-bordered table-striped">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td> 
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm">Edit</a>

                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE') 
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        {{ $users->links() }}
                    </div>

                </div>
                <!-- End Page Content -->

            </div>
            <!-- End Main Content -->

        </div>
        <!-- End Content Wrapper -->

    </div>
    <!-- End Page Wrapper -->

    <!-- Common JS files -->
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script>
    @include('dashboard.footer')
</body>
</html>
