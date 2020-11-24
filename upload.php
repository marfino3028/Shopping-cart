<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();
if(isset($_POST['tombol']))
{
    if(!isset($_FILES['image']['tmp_name'])){
        echo '<span style="color:red"><b><u><i>Pilih file gambar</i></u></b></span>';
    }
    else
    {        
            $image   = addslashes(file_get_contents($_FILES['image']['tmp_name']));
            $name = $_POST['name'];
            $code = $_POST['code'];
            $price = $_POST['price'];
            mysqli_query($koneksi,"insert into tblproduct (image,code,price,name) values ('$image','$code','$price','$name')");
            header("location:index.php");
        }
}
?>
<html>
    <head>
        <title></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
<link href="style.css" type="text/css" rel="stylesheet" />
    </head>
    <body>
    <div class="card border-info ml-3" style="max-width: 50rem;">
        <form method="post" action="" enctype="multipart/form-data">
        <table>
        <div class="form-group mt-5 mr-5 ">
            <tr>
            
            <td class="col-5"><label>Upload Gambar Produk</label><br><br></td>
            <td><input type="file" class="form-control-file" id="image" name="image"><br></td>
        
            </tr>
            <tr>
                <td class="col-5"><label>Nama Produk</label><br><br></td>
                <td><input class="form-control" id="nama" name="nama" type="text" placeholder="Nama Produk"><br></td>
            </tr>

            <tr>
                <td class="col-5"><label>Kode Produk</label><br><br></td>
                <td><input class="form-control" id="code" name="code" type="text" placeholder="Nama Produk"><br></td>
            </tr>


            <tr>
                <td class="col-5"><label>Harga Produk</label><br><br></td>
                <td><input class="form-control" id="price" name="price" type="text" placeholder="Harga Produk"><br></td>
            </tr>
            <tr>
                <td></td>
                <td><br>
                <input type="submit" value="Tambah Produk" name="tombol" class="btn btn-primary btn-block waves-effect waves-light btn-block" />
                
            </tr>
            </div>
        </table>
        </form>
        </div>
    </body>
</html>