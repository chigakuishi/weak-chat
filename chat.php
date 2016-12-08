<html>
	<head>
    <?php
      header("X-XSS-Protection: 0");     
      $dsn = 'mysql:dbname=weakchat;host=localhost';
      $user = 'weakchat';
      $password = 'weakpa33w0rd';

      try{
        $dbh = new PDO($dsn, $user, $password);
      }catch (PDOException $e){
        print('Error:'.$e->getMessage());
        die();
      }
      
      if(isset($_POST["content"],$_POST["name"],$_COOKIE["key"])){
        $sth = $dbh->query(
          "INSERT INTO data(name,hash,content) VALUES('".
          $_POST["name"].
          "','".
          substr( hash("sha256",$_COOKIE["key"]) , 0, 10).
          "','".
          $_POST["content"].
          "')"
        );
      }


    ?>
		<meta charset="utf-8">
		<title>チャットルーム</title>
    <script src="sha256.js"></script>
		<script>
      window.onload = function(){
        var flag = false;
        if (document.cookie){ //Cookieの確認
          var cookies = document.cookie.split("; ");
          for (var i = 0; i < cookies.length; i++){
            var str = cookies[i].split("=");
            if (str[0] == "key"){
              flag=true;
              break;
            }
          }
        }
        if(!flag){ //Cookieがなかった
          document.cookie = "key="+String(new Date().getTime()) +decodeURIComponent(location.hash);
        }

        //Hiddenの設定
        document.getElementById("name").value = decodeURIComponent(location.hash).substr(1);
        document.getElementById("hname").innerText = decodeURIComponent(location.hash).substr(1);
        document.getElementById("post").action = "chat.php"+location.hash;
      }
		</script>
	</head>
	<body>
		<center>
			<h1>チャットルーム</h1>
      <h2>ようこそ<span id="hname"></span></h2>
			
			<hr>
			
			<table border="1">
        <?php
          $sth = $dbh->query('SELECT content,name,hash FROM data');
          while($row = $sth -> fetch(PDO::FETCH_ASSOC)) {
            $name=$row{"name"};
            $hash=$row{"hash"};
            $content=$row{"content"};
            ?>
              <tr>
                <td><?php echo htmlspecialchars($name) ?></td>
                <td><?php echo htmlspecialchars($hash) ?></td>
                <td><?php echo htmlspecialchars($content) ?></td>
              </tr>
            <?php
          }

          $sth = null;
          $dbh = null;
        ?>
			</table>
			<hr>
			<form id="post" method="POST">
				<input type="text" name="content"><br>
				<input type="hidden" name="name" id="name">
				<input type="submit" value="送信">
			</form>
		</center>
	</body>
</html>
