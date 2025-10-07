<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Fulcrum Link LTD - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this page -->
    <link 
      href="{{asset('vendor/datatables/dataTables.bootstrap4.min.css')}}"
      rel="stylesheet"
    />

    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bs-default-stylesheet">

    <link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" id="app-default-stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">

    <!-- Notification css (Toastr) -->
    <link href="{{asset('libs/toastr/build/toastr.min.css')}}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert-->
    <link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
/* Mobile Styles: Sidebar is hidden by default and toggles on mobile */
#accordionSidebar {
    display: none; /* Initially hidden on mobile */
    position: fixed;
    top: 10.5%;
    left: 0;
    width: 150px; 
    background-color: #f8f9fa;
    z-index: 1000;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
}

/* Add a class for showing the sidebar */
.show-sidebar {
  display: block !important; /* Show sidebar */
  position: fixed;
}

/* Desktop Styles: Sidebar is always visible */
@media (min-width: 768px) {
    #accordionSidebar {
        display: block !important; /* Always visible on larger screens */
        position: relative
    }

    /* Hide the toggle button on larger screens */
    #sidebarToggleTop {
        display: none;
    }
}
  </style>

    <style>
/* Styles for mobile devices */
@media only screen and (max-width: 768px) {
  #accordionSidebar.toggled + #accordionSidebar {
    padding: 1px;
  }
}

.no-hover-style {
    text-decoration: none;
    color: inherit;
    background-color: inherit;
}

.no-hover-style:hover {
    text-decoration: none;
    color: white;
    background-color: inherit;
}

    </style>


<script src="{{asset('libs/jquery/jquery.min.js')}}"></script>

</head>
