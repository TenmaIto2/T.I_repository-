<head>
<meta http-equiv="Content-Type" content="text/html" charset="utf-8">
<title>fifty-fiftyBBS</title>
</head>

<body bgcolor="#f8f8ff">
	<h1>fifty-fiftyBBS</h1>
	
	<hr>

<?php
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

$name = htmlspecialchars($_POST['name']);//名前
$comment = htmlspecialchars($_POST['comment']);//コメント
$postPass = htmlspecialchars($_POST['postPass']);//パスワード

$editMode = htmlspecialchars($_POST['editMode']);
$deleteNum = htmlspecialchars($_POST['deleteNum']);
$deletePass = htmlspecialchars($_POST['deletePass']);
$editNum = htmlspecialchars($_POST['editNum']);
$editPass = htmlspecialchars($_POST['editPass']);

$selectNum = "";
$selectName = "";
$selectCom = "";
$selectPass = "";



if($name!=null&&$comment!=null&&$postPass!=null){

//編集モード--------------------------------------------------------------------------------------
	if($editMode!=null){
		$sql = 'update bbs set name=:name, comment=:comment, pass=:pass where id = :id';
		$stmt = $pdo->prepare($sql);
		$stmt -> bindParam(':id',$editMode,PDO::PARAM_INT);
		$stmt -> bindParam(':name',$name,PDO::PARAM_STR);
		$stmt -> bindParam(':comment',$comment,PDO::PARAM_STR);
		$stmt -> bindParam(':pass',$postPass,PDO::PARAM_STR);
		
		$editFlag = $stmt -> execute();
		if ($editFlag){
					print('編集されました。<br>');
				}
				else{
					print('編集に失敗しました...<br>');
				}
	}
	
//普通の書き込み----------------------------------------------------------------------------------
	else{
		$postsql = $pdo -> prepare("INSERT INTO bbs(name,comment,pass) VALUES(:name,:comment,:pass)");
		$postsql -> bindParam(':name',$name,PDO::PARAM_STR);
		$postsql -> bindParam(':comment',$comment,PDO::PARAM_STR);
		$postsql -> bindParam(':pass',$postPass,PDO::PARAM_STR);
		
		$postFlag = $postsql -> execute();
		if ($postFlag){
					print('投稿されました。<br>');
				}
				else{
					print('投稿に失敗しました...<br>');
				}
	}
}

if($editNum!=null&&$editPass!=null){//編集対象番号が入力されたとき
	$sql = 'SELECT * FROM bbs';
	$result = $pdo->query($sql);
	
	foreach($result as $row){
		if($row['id']==$editNum){
			if($row['pass']==$editPass){
				$selectNum = $row['id'];
				$selectName = $row['name'];
				$selectCom = $row['comment'];
				$selectPass = $row['pass'];
			}
			else{
				echo "パスワードが違います!";
			}
		}
	}
}

//削除機能----------------------------------------------------------------------------------
if($deleteNum!=null&&$deletePass!=null){
	$sql = 'SELECT * FROM bbs';
	$result = $pdo -> query($sql);
	
	foreach($result as $row){
		if($row['id']==$deleteNum){
			if($row['pass']==$deletePass){
				$sql = "DELETE FROM bbs WHERE id = :id AND pass = :pass";
				$stmt = $pdo->prepare($sql);
				$stmt -> bindParam(':id',$deleteNum,PDO::PARAM_INT);
				$stmt -> bindParam(':pass',$deletePass,PDO::PARAM_STR);
				$deleteFlag = $stmt->execute();
				if ($deleteFlag){
					print('データの削除成功!!<br>');
				}
				else{
					print('データ削除失敗...<br>');
				}
			}
			else{
				echo "パスワードが違います!";
			}
		}
	}
}
?>

<form method="post" action="mission_4.php">
	
	
	<p>
	投稿フォーム<br>
	<input type="text" name="name" placeholder="名前" value="<?php echo $selectName ?>"><br>
	<input type="text" name="comment" placeholder="コメント" value="<?php echo $selectCom ?>"><br>
	<input type="password" name="postPass" placeholder="パスワード設定" value="<?php echo $selectPass; ?>">
	<input type="submit" value="送信"><br>
	<input type="hidden" name="editMode" value="<?php echo $selectNum ?>" placeholder="編集用"></p>
	
	<p>
	削除<br>
	<input type="text" name="deleteNum" placeholder="削除対象番号"><br>
	<input type="password" name="deletePass" placeholder="パスワード">
	<input type="submit" value="削除" name="delete"></p>
	
	<p>
	編集<br>
	<input type="text" name="editNum" placeholder="編集対象番号"><br>
	<input type="password" name="editPass" placeholder="パスワード">
	<input type="submit" value="編集" name="edit"></p>
	
</form>


<?php
//動作確認用--------------------------------------
//	echo "name=".$name.'<br>';
//	echo "comment=".$comment.'<br>';
//	echo "postPass=".$postPass.'<br>';
//	echo "deleteNum=".$deleteNum.'<br>';
//	echo "deletePass=".$editPass.'<br>';
//	echo "editNum=".$editNum.'<br>';
//	echo "editPass=".$editPass.'<br>';
//	echo "editMode=".$editMode.'<br>';
//------------------------------------------------
?>

<?php
//	$countsql="";
//	$resultc=null;
//	$countsql ='SELECT COUNT(id) FROM bbs';
//	$resultc = $pdo -> query($countsql);
//	echo "投稿件数：".$resultc."件";
?>

<hr>

<?php

$showsql = 'SELECT * FROM bbs order by id';
$result = $pdo -> query($showsql);
foreach($result as $row){
	echo $row['id'].'&nbsp'.'&nbsp';
	echo "名前：".'<span style="color:#008000">'.$row['name'].'</span>'.'&nbsp'.'&nbsp';
	echo $row['date'].'&nbsp'.'&nbsp';
	//echo "ID:".uniqid();
	//echo $row['pass'];
	echo '<br>'.'&nbsp'.'&nbsp'.$row['comment'];
	echo '<br>'.'<br>';
}

?>

</body>

</html>
