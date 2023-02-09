<?php
    session_start();
    if(!isset($_GET['go'])&&!isset($_GET['secretused'])&&!isset($_GET['resetpasswd']))
    {
        echo "<form>
        Login: <input type=text name=login>;<br>
        Password: <input type=password name=passwd>;<br>
        <input type=submit name=go value=Go>;
        </form>";
    }
    else if(isset($_GET['secretused']))
    {
        $login=$_SESSION["login"];
        $secret=$_GET["secret"];
        $passwd=null;
        $bsec=false;
        $filesec=file("z3info.txt");
        foreach($filesec as $line)
        {
            $arr =explode(":", $line);
            if($login==$arr[0]&&$secret==$arr[2])
            {
                $bsec=true;
                $passwd=$arr[1];
                break;
            }
        }
        if($bsec)
        {
            echo "<form>
            Login: <input type=text name=login value=".$login.">;<br>
            Password: <input type=password name=passwd value=".$passwd.">;<br>
            Секретное слово принято. Ваш пароль находится в поле для паролей.<br>
            <input type=submit name=go value=Go>;<br>
            Или, если хотите, поменяйте пароль! Он не должен быть короче 3-ч символов и не должен содержать -:-.!<br>
            New password: <input type=password name=newpasswd>;<br>
            <input type=submit name=resetpasswd value=Resetpasswd>;
            </form>";
        }
        else
        {
            echo "<form>
            Login: <input type=text name=login value=".$login.">;<br>
            Password: <input type=password name=passwd>;<br>
            Неправильное секретное слово! Обратитесь к поддержке (её нет), если забыли секретное слово.;<br>
            SecretWord: <input type=text name=secret>;<br>
            <input type=submit name=go value=Go>;<br>
            <input type=submit name=secretused value=Secretused>;<br>
            </form>";
        }
    }
    else if(isset($_GET['resetpasswd']))
    {
        $login=$_SESSION["login"];
        $newpasswd=$_GET["newpasswd"];
        if(strlen($newpasswd)<3||strpos($newpasswd, ":"))
        {
            echo "<form>
            Пароль не соответствует требваниям!<br>
            Он не должен быть короче 3-ч символов и не должен содержать -:-.!<br>
            New password: <input type=password name=newpasswd>;<br>
            <input type=submit name=resetpasswd value=Resetpasswd>;
            </form>";
        }
        else
        {
            $filesec=file("z3info.txt");
            foreach($filesec as $i => $line)
            {
                $arr =explode(":", $line);
                if($login==$arr[0])
                {
                    $arr[1]=$newpasswd;
                    $filesec[$i]=$arr[0].":".$arr[1].":".$arr[2].":\n";
                    break;
                }
            }
            file_put_contents("z3info.txt", $filesec);
            echo "<form>
            Отличный пароль!<br>
            <input type=submit name=bruh>;
            </form>";
        }
    }
    else
    {
        $login=$_GET["login"];
        $passwd=$_GET["passwd"];
        $blog=false;
        $bpass=false;
        $filesec=file("z3info.txt");
        foreach($filesec as $line)
        {
            $arr=explode(":", $line);
            if($login==$arr[0])
            {
                $blog=true;
                $_SESSION["login"]=$login;
                if($passwd==$arr[1])
                {
                    $bpass=true;
                }
                break;
            }
        }
        if($blog&&$bpass)
        {
            echo "AUTHORISED";
            session_destroy();
        }
        else if($blog&&!$bpass)
        {
            echo "<form>
            Login: <input type=text name=login value=".$_SESSION["login"].">;<br>
            Password: <input type=password name=passwd>;<br>
            Неправильный пароль! Воспользуйтесь секретным словом, если необходимо вспомнить пароль.;<br>
            SecretWord: <input type=text name=secret>;<br>
            <input type=submit name=go value=Go>;<br>
            <input type=submit name=secretused value=Secretused>;
            </form>";
        }
        else
        {
            echo "<form>
            Login: <input type=text name=login>;<br>
            Неверный логин! При неверном логине пароль не проверяется!<br>
            Password: <input type=password name=passwd>;<br>
            <input type=submit name=go value=Go>;
            </form>";
        }
    } 
?>