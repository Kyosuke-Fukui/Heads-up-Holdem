<?php 
 
 //手札を数字の強い順に並べる関数
function sort_hands($array){
    $sort_keys = [];
    foreach($array as $key => $value){
        $sort_keys[$key] = $value['key'];
    }
    array_multisort($sort_keys,SORT_DESC,$array);
    return $array;
}   

//配列で全ての組合せを返す関数
function combination(array $arr, int $r): ?array
{
    // 重複した値を削除して，数値添字配列にする
    // $arr = array_values(array_unique($arr));

    $n = count($arr);
    $result = []; // 最終的に二次元配列にして返す

    // nCr の条件に一致していなければ null を返す
    if($r < 0 || $n < $r){ return null; }

    if($r === 1){
        foreach($arr as $item){
            $result[] = [$item];
        }
    }

    if($r > 1){
        // n - r + 1 回ループ
        for($i = 0; $i < $n-$r+1; $i++){
            // $sliced は $i + 1 番目から末尾までの要素
            $sliced = array_slice($arr, $i + 1);
            // 再帰処理 二次元配列が返ってくる
            $recursion = combination($sliced, $r - 1);
            foreach($recursion as $one_set){
                array_unshift($one_set, $arr[$i]);
                $result[] = $one_set;
            }
        }
    }

    return $result;
}

//役の判定
function show_hands($array){
    //スーツが同じ手札の枚数を数える
    $suit_array = [];
    foreach($array as $key => $value){
        $suit_array[$key] = $value['suit'];
    }
    $same_suits = array_count_values($suit_array);
    $count_same_suits = max($same_suits);

    //数字が同じ手札の枚数を数える
    $face_array = [];
    foreach($array as $key => $value){
        $face_array[$key] = $value['key'];
    }
    $same_faces = array_count_values($face_array);
    $count_same_faces = max($same_faces);

    //数字に重複がある場合の重複枚数が最も多いペアの数字
    $pair;
    // echo $face_array[0];
    arsort($same_faces);
    // var_dump($same_faces);
    $pair =  array_keys($same_faces);

    $hand = [[],[],[]]; //役の名前、役の強さ、数字５つ（同役ジャッジに使用）
    //ストレート系判定
    if($count_same_faces==1 && (($face_array[0]-1 == $face_array[1] && $face_array[1]-1 == $face_array[2] && $face_array[2]-1 == $face_array[3] && $face_array[3]-1 == $face_array[4]) ||
    ($face_array[0] == 12 && $face_array[1] == 3 && $face_array[2] == 2 && $face_array[3] == 1 && $face_array[4] == 0))){
        if($count_same_suits == 5){
            if($face_array[0] == 12 && $face_array[1] == 11){
                $hand = ["ロイヤルストレートフラッシュ",1, $face_array];
            }else {
                $hand = ["ストレートフラッシュ",2, $face_array];
            }
        }else {
            $hand = ["ストレート",6, $face_array];
        }
    }else {
        if($count_same_suits == 5) {
            $hand = ["フラッシュ",5, $face_array];
        }else{
            if($count_same_faces == 4) $hand = ["フォーカード",3, $pair];
            if ($count_same_faces == 3){
                if(count($same_faces) == 2) {
                $hand = ["フルハウス",4, $pair];
                }else {
                $hand = ["スリーカード",7, $pair];
                }
            } 
            if($count_same_faces == 2) {
                if(count($same_faces) == 3){
                $hand = ["ツーペア",8, $pair];
                }else{
                $hand = ["ワンペア",9, $pair];
                }
            }
            if($count_same_faces == 1) $hand = ["ハイカード",10,$face_array];
        }
    }

    return $hand;
}

//21通り（7C5）の組合せを、役の強さ順に並べる関数
function max_hands($array){
    //役のランク
    $hand_rank = [];
    foreach($array as $key => $value){
        $hand_rank[$key] = $value[1];
    }
    //役が同じ時の数字のランク
    $num_rank = [];
    foreach($array as $key => $value){
        $num_rank[$key] = $value[2];
    }
    array_multisort($hand_rank,SORT_ASC,$num_rank,SORT_DESC,$array);
    return $array;
}