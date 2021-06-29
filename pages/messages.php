<?php
//de scris codul pentru incarcarea datelor din $_POST
$user = User::findOneById($_SESSION['user']);
$userList = User::findAll();
$receivedMessages = Message::findAllByReceiver($user);
$sentMessages = Message::findAllBySender($user);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['receiverID']) && $_POST['receiverID'] !== '' &&
        isset($_POST['corp_mesaj']) && $_POST['corp_mesaj'] !== '')
    {
        $toUser = User::findOneById($_POST['receiverID']);
        $message = new Message();
        $message->setSenderId($user->getId())->setReceiverId($toUser->getId())->setDate(date('Y-m-d H:i:s', time()))->setText($_POST['corp_mesaj']);
        $message->save();
        $sentMessages = Message::findAllBySender($user);

        //se trimite e-mail destinatarului
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=utf-8';
        $headers[] = 'To: ' . $toUser->getEmail();
        $headers[] = 'From: ' . $user->getEmail();
        $to = $toUser->getEmail();
        $subject = 'Aveti un nou mesaj pe Mini-Tweet';
        $mailText = $message->getText();
        $mailText = wordwrap($mailText, 70);
        $mailResult = mail($to, $subject, $mailText, implode("\r\n", $headers));
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>My Messages</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" media="screen" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <?php include_once 'pages/header.php'; ?>
    <div class="row">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-top:10px; margin-bottom:10px;">
            <a class="btn btn-warning" href="index.php?page=logout" role="button">Logout</a>
            <a class="btn btn-info" href="index.php?page=homepage" role="button">Home</a>
        </div>
    </div>
    <div class="row" id="message_compose">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <form action="" method="post" role="form">
                <legend>Compunere mesaj</legend>
                <div class="form-group">
                    <label for="receiverID">Selecteaza destinatarul</label>
                    <select name="receiverID" id="receiverID" class="form-control">
                        <option value=""> -- Selecteaza un utilizator --</option>
                        <?php
                        foreach ($userList as $user) {
                            echo "<option value=\"" . $user->getId() . "\">" . $user->getName() . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="corp_mesaj">Mesaj</label>
                    <textarea required class="form-control" name="corp_mesaj" id="corp_mesaj" cols="30" rows="10"
                              placeholder="Scrieti un mesaj..."></textarea>
                </div>
                <button type="submit" value="send_message" class="btn btn-success">Trimite mesaj</button>
            </form>

        </div>
    </div>
    <div class="row" id="received_messages">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4" style="margin-bottom:10px;">
            <fieldset class="form-group">
                <legend>Mesaje Primite</legend>
                <table>
                <?php
                    foreach ($receivedMessages as $message){
                        echo "
                            <tr><th colspan='2'>Expeditor: " . $message->getSender()->getName() . " la " . $message->getDate() . "</th></tr>
                            <tr>
                                <td style=\"vertical-align:top; width: 5em\">Mesaj: </td>
                                <td style=\"vertical-align:top;\">". $message->getText() . "</td>
                            </tr>
                            ";
                    }
                ?>
                </table>
            </fieldset>
        </div>
    </div>
    <div class="row" id="sent_messages">
        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <fieldset class="form-group">
                <legend>Mesaje Trimise</legend>
                <table>
                <?php
                    foreach ($sentMessages as $message){
                        echo "
                            <tr><th colspan='2'>Destinatar: " . $message->getReceiver()->getName() . " la " . $message->getDate() . "</th></tr>
                            <tr>
                                <td style=\"vertical-align:top; width: 5em\">Mesaj: </td>
                                <td style=\"vertical-align:top;\">". $message->getText() . "</td>
                            </tr>
                            ";
                    }
                ?>
                </table>
            </fieldset>
        </div>
    </div>
</div>
</body>
</html>
