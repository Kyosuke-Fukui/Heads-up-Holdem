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
$cardPlayer = $game->cardPlayer; //自分の手札
$cardOpp =$game->cardOpp; //相手の手札
$ComCard = $game->ComCard; //コミュニティカード
$judge = $game->showDown(); //勝敗
$playerHand = $game->playerHand; //自分の手役
$oppHand = $game->oppHand; //相手の手役

$list = array("cardPlayer" => $cardPlayer, "cardOpp" => $cardOpp, "ComCard" => $ComCard, "playerHand" => $playerHand, "oppHand" => $oppHand, "judge" => $judge);
header("Content-type: application/json; charset=UTF-8");
echo json_encode($list);

exit;

?>