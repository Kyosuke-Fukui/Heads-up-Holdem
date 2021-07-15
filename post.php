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
            //同役判定（ホールデム仕様なので実際には不要な条件文あり）
            switch ($this->playerHand[0]) {
                case 'ストレートフラッシュ':
                case 'ストレート':
                    if($this->playerHand[2][0] > $this->oppHand[2][0]){
                        //自分がA,2,3,4,5のストレートの場合
                        if(($this->playerHand[2][0] == 12 && $this->playerHand[2][1] == 3)){
                            $judge = 'lose';
                        }else{
                            $judge = 'win';
                        }
                    }elseif($this->playerHand[2][0] < $this->oppHand[2][0]){
                        //相手がA,2,3,4,5のストレートの場合
                        if(($this->oppHand[2][0] == 12 && $this->oppHand[2][1] == 3)){
                            $judge = 'win';
                        }else{
                            $judge = 'lose';
                        }
                    }else{
                        //自分がA,2,3,4,5のストレートの場合
                        if(($this->playerHand[2][0] == 12 && $this->playerHand[2][1] == 3)){
                            //相手がA,2,3,4,5のストレートの場合
                            if(($this->oppHand[2][0] == 12 && $this->oppHand[2][1] == 3)){
                                $judge = 'chop';
                            }else{
                                $judge = 'lose';
                            }
                        }else{
                            //相手がA,2,3,4,5のストレートの場合
                            if(($this->oppHand[2][0] == 12 && $this->oppHand[2][1] == 3)){
                                $judge = 'win';
                            }else{
                                $judge = 'chop';
                            }
                        }
                    }
                    break;
        
                default:
                    for ($i=0; $i < count($this->playerHand[2]); $i++) { 
                        if($this->playerHand[2][$i] > $this->oppHand[2][$i]){
                            $judge = 'win';
                            break;
                        }elseif($this->playerHand[2][$i] < $this->oppHand[2][$i]){
                            $judge = 'lose';
                            break;
                        }else{
                            $judge = 'chop';
                        }
                    }
                    break;
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