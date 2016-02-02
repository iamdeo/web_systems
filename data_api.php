<?php
/**
 * Coded while sipping tea at the midnight.
 * User: deoshao
 * Date: 02/02/16
 * Time: 02:20
 */


    include 'library/db.php';

    if($_SERVER['REQUEST_METHOD']=="POST")
    {
        //$data_key=isset($_POST['data_key'])?mysql_real_escape_string($_POST['data_key']):"";
        //$api_key=isset($_POST['api_key'])?mysql_real_escape_string($_POST['api_key']):"";

        $data_key=$_POST['data_key'];
        if(1234==1234){

            switch ($data_key)
            {
                case 1 : $data_result =  insert_data() ; break;
                case 2 : $data_result = update_data(); break;
                case 3 : $data_result = delete_data() ; break;
                case 4 : $data_result = read_data() ; break;
                case 5 : $data_result = read_all_data() ; break;
                default : $data_result =  read_all_data()  ; break;
            }
        }else{
            $data_result=array("status"=>0,"message"=>"Aunthentication failed");
        }
    }else{
        $data_result=array("status"=>1,"message"=>"Request method not accepted");
    }


//CRUD FUNCTIONS

    function insert_data(){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $status = $_POST['status'];

        if(empty($name)|| empty($email)){
            $msg = array("status" =>0 , "msg" => "Please fill all required fields");
        }else{

            $sql="INSERT INTO user ('name','email','status') values ('$name','$email','$status')";
            $query=mysql_query($sql);
            if($query){
                $msg = array("status" =>1 , "msg" => "Your record inserted successfully");
            }else{
                $msg = array("status" =>0 , "msg" => "Error on saving record");
            }
        }
        return $msg;
    }

    function update_data(){
        $name = $_POST['name'];
        $email = $_POST['email'];
        $status = $_POST['status'];
        $id = $_POST['id'];


        if(empty($name)|| empty($email)||(empty($id))){
            $msg = array("status" =>0 , "msg" => "Please fill all required fields");
        }else{

            $select=mysql_query("select * from user where id='$id'");
            if(mysql_num_rows($select)>0){
                $update = mysql_query("update user set name='$name', email='$email', status='$status' where id='$id'");
                if($update){
                    $msg = array("status" =>1 , "msg" => "Your record updated successfully");
                }else{
                    $msg = array("status" =>0 , "msg" => "Error on updating record");
                }
            }else{
                $msg = array("status" =>0 , "msg" => "Record Id doesn't exist");
            }
        }
        return $msg;
    }

    function delete_data(){
        $id = $_POST['id'];
        if(empty($id)){
            $msg = array("status" =>0 , "msg" => "Please specify the record Id");
        }else{
            $select=mysql_query("select * from user where id='$id'");
            if(mysql_num_rows($select)>0){
                $delete = mysql_query("delete from user where id='$id'");
                if($delete){
                    $msg = array("status" =>1 , "msg" => "Your record deleted successfully");
                }else{
                    $msg = array("status" =>0 , "msg" => "Error on deleting record");
                }
            }else{
                $msg = array("status" =>0 , "msg" => "Record Id doesn't exist");
            }
        }
     return $msg;
    }


    function read_data(){
        $id = $_POST['id'];
        if(empty($id)){
            $msg = array("status" =>0 , "msg" => "Please specify the record Id");
        }else{
            $select=mysql_query("select * from user where id='$id'");
            if(mysql_num_rows($select)>0){
                $read= mysql_fetch_array($select);
                @extract($read);
                $msg = array("status" =>1 , "id"=>$id, "name"=>$name,"email"=>$email,"status"=>$status);
            }else{
                $msg = array("status" =>0 , "msg" => "Record Id doesn't exist");
            }
        }
        return $msg;
    }

    function read_all_data(){
            $select=mysql_query("select * from user where id='$id'");
            $msg=array();
            if(mysql_num_rows($select)>0){
                $r=0;
                while($read= mysql_fetch_array($select)){
                    @extract($read);
                    $msg[$r]= array("status" =>1 , "id"=>$id, "name"=>$name,"email"=>$email,"status"=>$status);
                    $r++;
                }
            }else{
                $msg = array("status" =>0 , "msg" => "There is no record");
            }
        return $msg;
    }

//convert results into json

header('content-type: application/json');
echo json_encode($data_result);

?>