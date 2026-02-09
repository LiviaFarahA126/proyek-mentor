<!DOCTYPE html>
<html>
<head>
    <title>Sistem Absensi</title>

    <style>

        body{
            margin:0;
            font-family:Arial;
            display:flex;
        }

        /* SIDEBAR */
        .sidebar{
            width:250px;
            background:#2c3e50;
            color:white;
            height:100vh;
            padding:20px;
        }

        .logo{
            font-size:20px;
            font-weight:bold;
            margin-bottom:30px;
        }

        .menu a{
            display:block;
            color:white;
            text-decoration:none;
            margin:10px 0;
        }

        .menu a:hover{
            color:#1abc9c;
        }

        /* CONTENT */
        .content{
            flex:1;
            padding:20px;
            background:#f4f6f7;
        }

    </style>

</head>

<body>

<div class="sidebar">

    <div class="logo">
        LOGO ABSENSI
    </div>

    <div class="menu">
        <a href="/dashboard">Dashboard</a>
        <a href="/mata-kuliah">Mata Kuliah</a>
        <a href="#">Kelas</a>
        <a href="#">Absensi</a>
        <a href="/mahasiswa">Mahasiswa</a>
        <a href="#">Rekap Absensi</a>
        <a href="#">Logout</a>
    </div>

</div>


<div class="content">

    @yield('content')

</div>

</body>
</html>
