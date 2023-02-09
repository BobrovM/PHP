<?php
session_start();
if(!isset($_GET['add'])&&!isset($_GET['addreg'])
&&!isset($_GET['addsent'])&&!isset($_GET['redact'])
&&!isset($_GET['redactreg'])&&!isset($_GET['redactsent'])
&&!isset($_GET['searchsur'])&&!isset($_GET['searchsurres'])
&&!isset($_GET['searchtel'])&&!isset($_GET['searchtelres']))
{
    echo "<form>
    Выберите действие:<br>
    <input type=submit name=add value=Add>;
    <input type=submit name=redact value=Redact>;
    <input type=submit name=searchsur value=Searchsur>;
    <input type=submit name=searchtel value=Searchtel>;
    </form>";
}

else if(isset($_GET['add']))
{
    echo "<form>
    Login: <input type=text name=login>;<br>
    Password: <input type=password name=passwd>;<br>
    <input type=submit name=addreg value=addreg>;
    </form>";
}

else if(isset($_GET['addreg']))
{
    if($_GET['login']=='usr'&&$_GET['passwd']=='123')
    {
        echo "<form>
        Введите Фамилию<br><input type=text name=surname><br>
        Введите Имя<br><input type=text name=name><br>
        Введите Отчество<br><input type=text name=fathname><br>
        Введите Телефон<br><input type=text name=tele><br>
        Введите Адрес<br><input type=text name=address><br>
        <p><input type=submit name=addsent value=addsent>
        </form>";
    }
    else
    {
        echo "<form>
        Неправильный пароль или логин<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }
}

else if(isset($_GET['addsent']))
{
    $surname = $_GET['surname'];
    $name = $_GET['name'];
    $fathname = $_GET['fathname'];
    $tele = $_GET['tele'];
    $address = $_GET['address'];

    function stringCheck ($condition, $field_value)
    {
        $bool = false;
        $field_value = str_replace('+', '', $field_value);
        if (isset($field_value)) 
        {if (preg_match($condition, $field_value)) $bool = true;}
        return $bool;
    }

    if(!stringCheck('/^[А-ЯЁ][а-яё ^А-ЯЁ]*/u', $surname))
    {
        echo "<form>
        Неправильно введено - фамилия: только первая буква большая.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    } else if(!stringCheck('/^[А-ЯЁ][а-яё ^А-ЯЁ]*/u', $name))
    {
        echo "<form>
        Неправильно введено - имя: только первая буква большая.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }else if(!stringCheck('/^[А-ЯЁ][а-яё ^А-ЯЁ]*/u', $fathname))
    {
        echo "<form>
        Неправильно введено отчество: только первая буква большая.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }else if(!stringCheck('/^(([0-9]){11})$/', $tele))
    {
        echo "<form>
        Неправильно введено - телефон: только 11 цифр.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }else if(!stringCheck('/^[А-ЯЁ а-яё 0-9.]*/u', $address))
    {
        echo "<form>
        Неправильно введено - аддрес: только русские буквы и цифры.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    } else {
    $db = mysqli_connect("localhost", "root");
    mysqli_select_db($db, "z5t");
    $tele = str_replace('+', '', $tele);
    $tele = substr($tele, 1);
    $query = "insert into guesttable
    (surname, name, fathname, tele, address)
    values
    ('$surname', '$name', '$fathname', '$tele', '$address')";
    mysqli_query($db, $query);
    mysqli_close($db);}
    echo "<form>
    Успешно добавлено!.<br>
    <input type=submit name=prikol value=nazad>;
    </form>";
}

else if(isset($_GET['redact']))
{
    echo "<form>
    Login: <input type=text name=login>;<br>
    Password: <input type=password name=passwd>;<br>
    <input type=submit name=redactreg value=Redactreg>;
    </form>";
}

else if(isset($_GET['redactreg']))
{
    if($_GET['login']=='usr'&&$_GET['passwd']=='123')
    {
        echo "<form>
        Введите старый Телефон<br><input type=text name=oldtele><br>
        Введите Фамилию<br><input type=text name=surname><br>
        Введите Имя<br><input type=text name=name><br>
        Введите Отчество<br><input type=text name=fathname><br>
        Введите Телефон<br><input type=text name=tele><br>
        Введите Адрес<br><input type=text name=address><br>
        <p><input type=submit name=redactsent value=Redactsent>
        </form>";
    }
    else
    {
        echo "<form>
        Неправильный пароль или логин<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }
}

else if(isset($_GET['redactsent']))
{
    $oldtele = $_GET['oldtele'];
    $surname = $_GET['surname'];
    $name = $_GET['name'];
    $fathname = $_GET['fathname'];
    $tele = $_GET['tele'];
    $address = $_GET['address'];

    function stringCheck ($condition, $field_value)
    {
        $bool = false;
        $field_value = str_replace('+', '', $field_value);
        if (isset($field_value)) 
        {if (preg_match($condition, $field_value)) $bool = true;}
        return $bool;
    }

    if(!stringCheck('/^(([0-9]){11})$/', $oldtele))
    {
        echo "<form> ".$oldtele."
        Неправильно введено - телефон из баз: только 11 цифр.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }else if(!stringCheck('/^[А-ЯЁ][а-яё ^А-ЯЁ]*/u', $surname)&&strlen($surname)!=0)
    {
        echo "<form>
        Неправильно введено - фамилия: только первая буква большая.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    } else if(!stringCheck('/^[А-ЯЁ][а-яё ^А-ЯЁ]*/u', $name)&&strlen($name)!=0)
    {
        echo "<form>
        Неправильно введено - имя: только первая буква большая.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }else if(!stringCheck('/^[А-ЯЁ][а-яё ^А-ЯЁ]*/u', $fathname)&&strlen($fathname)!=0)
    {
        echo "<form>
        Неправильно введено отчество: только первая буква большая.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }else if(!stringCheck('/^(([0-9]){11})$/', $tele)&&strlen($tele)!=0)
    {
        echo "<form>
        Неправильно введено - новый телефон: только 11 цифр.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }else if(!stringCheck('/^[А-ЯЁ а-яё 0-9.]*/u', $address)&&strlen($address)!=0)
    {
        echo "<form>
        Неправильно введено - аддрес: только русские буквы и цифры.<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    } else {
        $db = mysqli_connect("localhost", "root");
        mysqli_select_db($db, "z5t");
        $oldtele = str_replace('+', '', $oldtele);
        $oldtele = substr($oldtele, 1);
        $tele = str_replace('+', '', $tele);
        $tele = substr($tele, 1);
        $query = "select * from guesttable where tele = '$oldtele'";
        $result = mysqli_query($db, $query);
        $row = $result->fetch_assoc();
        if($result->num_rows > 0)
        {
            if(empty($tele))
            {
                $tele = $oldtele;
            }
            if(empty($surname))
            {
                $surname = null;
                $surname = $row['surname'];
            }
            if(empty($name))
            {
                $name = null;
                $name = $row['name'];
            }
            if(empty($fathname)&&!empty($row['fathname']))
            {
                $fathname = null;
                $fathname = $row['fathname'];
            }
            if(empty($address)&&!empty($row['address']))
            {
                $address = null;
                $address = $row['address'];
            }
        $query = "update guesttable set 
        surname = '$surname',
        name = '$name',
        fathname = '$fathname',
        tele = '$tele',
        address = '$address'
        where tele = '$oldtele'";
        mysqli_query($db, $query);
        mysqli_close($db);
        echo"<form>
        Данные изменены!<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
        } else
        {
            echo"<form>
            Телефон не найден!<br>
            <input type=submit name=prikol value=nazad>;
            </form>";
        }
    }
}

else if(isset($_GET['searchsur']))
{
    echo "<form>
    Введите фамилию для поиска: <input type=text name=surname>;<br>
    <input type=submit name=searchsurres value=searchsurres>;
    </form>";
}

else if(isset($_GET['searchsurres']))
{
    $surname = $_GET['surname'];
    $db = mysqli_connect("localhost", "root");
    mysqli_select_db($db, "z5t");
    $query = "select * from guesttable where surname = '$surname'";
    $result = mysqli_query($db, $query);
    mysqli_close($db);
    $str = null;
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $str= $row["tele"]. " " . $row["surname"]. " " . $row["name"]. " " . $row["fathname"]. " " . $row["address"]. "<br>";
        }
    echo"<form>
    $str <br>
    <input type=submit name=prikol value=nazad>;
    </form>";
    } else
    {
        echo"<form>
        Записей с данной фамилией не найдено!<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }  
}

else if(isset($_GET['searchtel']))
{
    echo "<form>
    Введите номер для поиска: <input type=text name=tele>;<br>
    <input type=submit name=searchtelres value=searchtelres>;
    </form>";
}

else if(isset($_GET['searchtelres']))
{
    $tele = $_GET['tele'];
    $tele = str_replace('+', '', $tele);
    $tele = substr($tele, 1);
    $db = mysqli_connect("localhost", "root");
    mysqli_select_db($db, "z5t");
    $query = "select * from guesttable where tele = '$tele'";
    $result = mysqli_query($db, $query);
    mysqli_close($db);
    $str = null;
    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $str= $row["tele"]. " " . $row["surname"]. " " . $row["name"]. " " . $row["fathname"]. " " . $row["address"]. "<br>";
        }
    echo"<form>
    $str <br>
    <input type=submit name=prikol value=nazad>;
    </form>";
    } else
    {
        echo"<form>
        Записей с данным телефоном не найдено!<br>
        <input type=submit name=prikol value=nazad>;
        </form>";
    }  
}

else
{
    echo"<form>
    Как ты сюда попал?<br>
    <input type=submit name=prikol value=nazad>;
    </form>";
} 
?>