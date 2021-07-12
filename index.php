<?php
include './function.php';

class Game {
    public $cardPlayer = [];
    public $cardOpp = [];
    public $ComCard = [];
    public $playerHand = [];
    public $oppHand = [];
    public function __construct() {
        list($this->cardPlayer, $this->cardOpp, $this->ComCard) = $this->setCard();
    }

    private function setCard() {
        //デッキの作成
        $suits = ['♠','♥','♦','♣'];

        $faces = [];

        for($i = 2; $i<11; $i++){
            $faces[]=$i;
        }
        $faces[]='J';
        $faces[]='Q';
        $faces[]='K';
        $faces[]='A';

        $deck = [];

        foreach($suits as $suit){
            foreach($faces as $key => $face){
                $deck[] = array("key"=>$key,"face"=>$face,"suit"=>$suit);
            }
        }

        //手札の決定
        shuffle($deck);
        $cardPlayer = []; //自分の手札
        $cardOpp = []; //相手の手札
        $ComCard = []; //コミュニティカード

        for($i = 0; $i < 2; $i++) {
            $cardPlayer[] = array_shift($deck);
        }

        for($i = 0; $i < 2; $i++) {
            $cardOpp[] = array_shift($deck);
        }

        for($i = 0; $i < 5; $i++) {
            $ComCard[] = array_shift($deck);
        }

        return [$cardPlayer, $cardOpp, $ComCard];
    }

    public function showDown() {
        $cardPlayer_target = array_merge($this->cardPlayer,$this->ComCard);
        $cardOpp_target = array_merge($this->cardOpp,$this->ComCard);

        function determine_hand($arr){
            $combi_array = [];
            $combi_array = combination($arr,5);
            $hand_combi = [];
            for ($i=0; $i < count($combi_array); $i++) { 
                $hand_combi[] = show_hands(sort_hands($combi_array[$i]));
            }

            return max_hands($hand_combi)[0];
        }

        $this->playerHand = determine_hand($cardPlayer_target);
        $this->oppHand = determine_hand($cardOpp_target);

        //勝敗判定
        if($this->playerHand[1] < $this->oppHand[1]){
            $judge = 'win';
        }elseif($this->playerHand[1] > $this->oppHand[1]){
            $judge = 'lose';
        }else{
            if($this->playerHand[2] > $this->oppHand[2]){
            $judge = 'win';
            }elseif($this->playerHand[2] < $this->oppHand[2]){
            $judge = 'lose';
            }else{
            $judge = 'chop';
            }
        }   

    return $judge;
    }
}

$game = new Game;
$cardPlayer = $game->cardPlayer;
$cardOpp =$game->cardOpp;
$ComCard = $game->ComCard;

$judge = $game->showDown();

$playerHand = $game->playerHand;
$oppHand = $game->oppHand;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>シンプルホールデム</title>
    <style>
    h1{
        text-align:center;
    }
    .main{
        width: 400px; margin: 0px auto; text-align:center;
    }
    </style>
</head>
<body>
    <h1>ヘッズアップ・テキサスホールデム</h1>
    <div class="main">
        <div>
            <?php 
            echo <<<EOM
            相手（<span>BB</span>）<br>
            総チップ：<span id='oppStack'>9800</span><br>
            ベット額：<span id='oppBet'>200</span><br>
            手札：<span id='oppback'>？？</span>
            <span id='oppHand'>
                {$cardOpp[0]['face']}<span class='suit'>{$cardOpp[0]['suit']}</span>
                {$cardOpp[1]['face']}<span class='suit'>{$cardOpp[1]['suit']}</span>
            </span><br><br>
            <div class='showDown'>「{$oppHand[0]}」</div>
            <div id='oppAct'></div><br>
            <span id='step'></span><br>
            <span id='judge'>{$judge}</span><br>
            ポット：<span id='pot'>0</span><br>
            場のカード：
            <span id='back1'>？？？</span>
            <span id='flop'>
                {$ComCard[0]['face']}<span class='suit'>{$ComCard[0]['suit']}</span>
                {$ComCard[1]['face']}<span class='suit'>{$ComCard[1]['suit']}</span>
                {$ComCard[2]['face']}<span class='suit'>{$ComCard[2]['suit']}</span>
            </span>
            <span id='back2'>？</span>
            <span id='turn'>{$ComCard[3]['face']}<span class='suit'>{$ComCard[3]['suit']}</span></span>
            <span id='back3'>？</span>
            <span id='river'>{$ComCard[4]['face']}<span >{$ComCard[4]['suit']}</span></span>
            <br><br><br>
            自分（<span>SB</span>）<br>
            総チップ：<span id='playerStack'>9900</span><br>
            ベット額：<span id='playerBet'class='suit'>100</span><br>
            手札：{$cardPlayer[0]['face']}<span class='suit'>{$cardPlayer[0]['suit']}</span>
            {$cardPlayer[1]['face']}<span class='suit'>{$cardPlayer[1]['suit']}</span><br><br>
            <div class='showDown'>「{$playerHand[0]}」</div>
            <div id='playerAct'></div>
            EOM;
            ?>
        </div>
        
        <input type="button" value="フォールド" onclick="action('fold')">
        <input id='call' type="button" value="コール" onclick="action('call')">
        <input id='check' type="button" value="チェック" onclick="action('check')">
        <input id='raise' type="button" value="レイズ" onclick="action('raise')">
        <input id='bet' type="button" value="ベット" onclick="action('bet')">
        <input id='amount' type='number' step='200' min='0' max='20000' value='200'>
        <br>
        <input type="button" value="もう一度遊ぶ" onclick="koshin()">
    
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
    <script src='./app.js'></script>
</body>
</html>
