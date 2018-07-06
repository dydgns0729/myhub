<?php
           session_start();
?>
<meta charset="utf-8">
<?php
if(isset($_POST['id'])){
    $id=$_POST['id'];
    $pass=$_POST['pass'];
}

   // 이전화면에서 이름이 입력되지 않았으면 "이름을 입력하세요"
   // 메시지 출력
if(empty($id)) {
     echo("
           <script>
             window.alert('아이디를 입력하세요.')
             history.go(-1)
           </script>
         ");
         exit;
   }

   if(empty($pass)) {
     echo("
           <script>
             window.alert('비밀번호를 입력하세요.')
             history.go(-1)
           </script>
         ");
         exit;
   }
   include "../common_lib/dbconn.php";
   //사용자로부터 입력받은 데이터는 mysql_real_escape_string($user),mysql_real_escape_string($password); 이렇게 방어 후 디비에 저장해야한다.
   //db에 영향을 줄수있는 특수문자에 반응해 \(역슬레쉬)로 구분해주는 역활이다.
   $id=mysqli_real_escape_string($con, $id);
   $pass=mysqli_real_escape_string($con, $pass);
   $sql = "select * from member where id='{$id}'";
   $result = mysqli_query($con, $sql);

   $num_match = mysqli_num_rows($result);

   if(empty($num_match))
   {
     echo("
           <script>
             window.alert('등록되지 않은 아이디입니다.')
             history.go(-1)
           </script>
         ");
    }
    else
    {
        //쿼리문을 실행한 결과값을 가지고있는 레코드셋에서 첫번째 레코드를 배열로 가져온다. 필드명과 인덱스값으로 참조하여 조회할수있습니다. 
        $row = mysqli_fetch_array($result);

        $db_pass = $row[pass];

        if($pass != $db_pass)
        {
           echo("
              <script>
                window.alert('비밀번호가 틀립니다.')
                history.go(-1)
              </script>
           ");

           return;
        }
        else
        {
           $userid = $row[id];
           if($userid!==$id){
               echo("
              <script>
                window.alert('아이디의 값이 이상합니다.')
                history.go(-1)
              </script>
           ");
               exit;
           }
           $userpass = $row[pass];
           if($userpass!==$pass){
               echo("
              <script>
                window.alert('올바른 비밀번호가 아닙니다.')
                history.go(-1)
              </script>
           ");
               exit;
           }
		   $username = $row[name];
		   $usernick = $row[nick];
		   $userlevel = $row[level];

           $_SESSION['userid'] = $userid;
           $_SESSION['username'] = $username;
           $_SESSION['usernick'] = $usernick;
           $_SESSION['userlevel'] = $userlevel;

           echo("
              <script>
                alert('{$usernick}님 환영합니다.');
                location.href = '../index.php';
              </script>
           ");
        }
   }          
?>
