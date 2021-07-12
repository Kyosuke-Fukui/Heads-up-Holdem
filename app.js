// const Game = class {
//     constructor() {

//     }
// }

$(".suit:contains('♥')").css("color", "red");
$(".suit:contains('♦')").css("color", "red");
$('#judge').hide();
$('.showDown').hide();
$('#oppHand').hide();
$('#flop').hide();
$('#turn').hide();
$('#river').hide();
$('#step').html('プリフロップ')
let pot = 0;
$('#pot').html(pot);
$('#playerBet').html(100);
$('#oppBet').html(200);
const t = 2000

let position = 'SB'

if (position == 'SB') {
    $('#check').hide();
    $('#bet').hide();
}

let step
let rnd

function koshin() {
    location.reload();
}

const action = (a) => {
    switch (a) {
        case 'fold':
            $('#playerAct').html('フォールド');
            window.setTimeout("showDown()", t);
            break;

        case 'call':
            //チップ調整
            let callAmount = parseInt($('#oppBet').text()) - parseInt($('#playerBet').text())
            if (callAmount < parseInt($('#playerStack').text())) {
                $('#playerBet').html(parseInt($('#oppBet').text()))
                $('#playerStack').html(parseInt($('#playerStack').text()) - callAmount)
                $('#playerAct').html('コール');
            } else {
                $('#playerBet').html(parseInt($('#playerBet').text()) + parseInt($('#playerStack').text()))
                $('#playerStack').html(0)
                $('#playerAct').html('オールイン');
                window.setTimeout("showDown()", t);
                break;
            }

            //ベット額が等しくなり、次の段階に進む
            step = $('#step').text();
            switch (step) {
                case 'プリフロップ':
                    window.setTimeout("proceed('flop')", t);
                    break;
                case 'フロップ':
                    window.setTimeout("proceed('turn')", t);
                    break;
                case 'ターン':
                    window.setTimeout("proceed('river')", t);
                    break;
                case 'リバー':
                    window.setTimeout("showDown()", t);
                    break;
            }
            break;

        case 'check':
            $('#playerAct').html('チェック');
            //ベット額が等しくなり、次の段階に進む
            step = $('#step').text();
            switch (step) {
                case 'フロップ':
                    window.setTimeout("proceed('turn')", t);
                    break;
                case 'ターン':
                    window.setTimeout("proceed('river')", t);
                    break;
                case 'リバー':
                    window.setTimeout("showDown()", t);
                    break;
            }
            break;

        case 'raise':
            //チップ調整
            if ((parseInt($('#amount').val()) + parseInt($('#playerBet').text())) > parseInt($('#oppBet').text()) && (parseInt($('#amount').val()) <= parseInt($('#playerStack').text()))) {
                if (parseInt($('#amount').val()) == parseInt($('#playerStack').text())) {
                    $('#playerBet').html(parseInt($('#amount').val()) + parseInt($('#playerBet').text()))
                    $('#playerStack').html(0)
                    $('#playerAct').html('オールイン');
                } else {
                    $('#playerBet').html(parseInt($('#amount').val()) + parseInt($('#playerBet').text()))
                    $('#playerStack').html(parseInt($('#playerStack').text()) - parseInt($('#amount').val()))
                    $('#playerAct').html('レイズ');
                }

            } else {
                alert('レイズ額が不正です');
                break;
            }

            //自分がレイズ→相手はフォールドorコールorレイズ
            rnd = Math.random();
            if (rnd > 2 / 3) {
                window.setTimeout("oppMove('fold')", t);
            } else if (rnd > 1 / 3) {
                window.setTimeout("oppMove('call')", t);
            } else {
                window.setTimeout("oppMove('raise')", t);
            }
            break;

        case 'bet':
            //チップ調整
            if ((parseInt($('#amount').val()) + parseInt($('#playerBet').text())) > parseInt($('#oppBet').text()) && (parseInt($('#amount').val()) <= parseInt($('#playerStack').text()))) {
                if (parseInt($('#amount').val()) == parseInt($('#playerStack').text())) {
                    $('#playerAct').html('オールイン');
                } else {
                    $('#playerAct').html('ベット');
                }
                $('#playerBet').html(parseInt($('#amount').val()) + parseInt($('#playerBet').text()))
                $('#playerStack').html(parseInt($('#playerStack').text()) - parseInt($('#amount').val()))
            } else {
                alert('ベット額が不正です');
            }

            //自分がベット→相手はフォールドorコールorレイズ
            rnd = Math.random();
            if (rnd > 2 / 3) {
                window.setTimeout("oppMove('fold')", t);
            } else if (rnd > 1 / 3) {
                window.setTimeout("oppMove('call')", t);
            } else {
                window.setTimeout("oppMove('raise')", t);
            }
            break;
    }
}

const oppMove = (a) => {
    switch (a) {
        case 'fold':
            $('#oppAct').html('フォールド');
            window.setTimeout("showDown()", t);
            break;
        case 'call':
            $('#oppAct').html('コール');
            //チップ調整
            let oppCallAmount = parseInt($('#playerBet').text()) - parseInt($('#oppBet').text())
            if (oppCallAmount < parseInt($('#oppStack').text())) {
                $('#oppBet').html(parseInt($('#playerBet').text()))
                $('#oppStack').html(parseInt($('#oppStack').text()) - oppCallAmount)
                $('#oppAct').html('コール');
            } else {
                $('#oppBet').html(parseInt($('#oppBet').text()) + parseInt($('#oppStack').text()))
                $('#oppStack').html(0)
                $('#oppAct').html('オールイン');
                window.setTimeout("showDown()", t);
                break;
            }
            //ベット額が等しくなり、次の段階に進む
            step = $('#step').text();
            switch (step) {
                case 'プリフロップ':
                    window.setTimeout("proceed('flop')", t);
                    break;
                case 'フロップ':
                    window.setTimeout("proceed('turn')", t);
                    break;
                case 'ターン':
                    window.setTimeout("proceed('river')", t);
                    break;
                case 'リバー':
                    window.setTimeout("showDown()", t);
                    break;
            }
            break;
        case 'check':
            $('#oppAct').html('チェック');
            break;
        case 'raise':
            $('#oppAct').html('レイズ');
            //チップ調整
            //レイズ額は自分のベット額の3倍に固定
            let oppRaiseAmount = parseInt($('#playerBet').text()) * 3
            if (oppRaiseAmount < parseInt($('#oppStack').text())) {
                $('#oppBet').html(parseInt($('#oppBet').text()) + oppRaiseAmount)
                $('#oppStack').html(parseInt($('#oppStack').text()) - oppRaiseAmount)
                $('#oppAct').html('レイズ');
            } else {
                $('#oppBet').html(parseInt($('#oppBet').text()) + parseInt($('#oppStack').text()))
                $('#oppStack').html(0)
                $('#oppAct').html('オールイン');
            }
            //レイズされたらチェックはできない
            $('#call').show();
            $('#check').hide();
            break;
        case 'bet':
            //チップ調整
            //相手は1/2ポットベットのみと仮定
            let oppBetAmount = parseInt($('#pot').text()) / 2
            if (oppBetAmount < parseInt($('#oppStack').text())) {
                $('#oppBet').html(oppBetAmount);
                $('#oppStack').html(parseInt($('#oppStack').text()) - oppBetAmount)
                $('#oppAct').html('ベット');
            } else {
                $('#oppBet').html(parseInt($('#oppBet').text()) + parseInt($('#oppStack').text()))
                $('#oppStack').html(0)
                $('#oppAct').html('オールイン');
            }
            //ベットされたらチェックはできない
            $('#call').show();
            $('#check').hide();
            break;
    }
}

const proceed = (a) => {
    switch (a) {
        case 'flop':
            $('#step').html('フロップ')

            $('#call').hide();
            $('#check').show();

            //カードを裏返す
            $('#back1').hide();
            $('#flop').show();
            //各々のベット額をポットに集める
            pot = parseInt($('#pot').text()) + parseInt($('#oppBet').text()) + parseInt($('#playerBet').text())
            $('#pot').html(pot);
            //各々のベット額をリセットする
            $('#playerBet').html(0);
            $('#oppBet').html(0);

            //フロップ以降はBBがアウトオブポジション（先手）
            rnd = Math.random();
            //相手はチェックorベット
            if (rnd > 0.5) {
                window.setTimeout("oppMove('check')", t);
            } else {
                window.setTimeout("oppMove('bet')", t);
            }
            break;
        case 'turn':
            $('#step').html('ターン')

            $('#call').hide();
            $('#check').show();

            //カードを裏返す
            $('#back2').hide();
            $('#turn').show();
            //各々のベット額をポットに集める
            pot = parseInt($('#pot').text()) + parseInt($('#oppBet').text()) + parseInt($('#playerBet').text())
            $('#pot').html(pot);
            //各々のベット額をリセットする
            $('#playerBet').html(0);
            $('#oppBet').html(0);

            //フロップ以降はBBがアウトオブポジション（先手）
            rnd = Math.random();
            //相手はチェックorベット
            if (rnd > 0.5) {
                window.setTimeout("oppMove('check')", t);
            } else {
                window.setTimeout("oppMove('bet')", t);
            }
            break;
        case 'river':
            $('#step').html('リバー')

            $('#call').hide();
            $('#check').show();

            //カードを裏返す
            $('#back3').hide();
            $('#river').show();
            //各々のベット額をポットに集める
            pot = parseInt($('#pot').text()) + parseInt($('#oppBet').text()) + parseInt($('#playerBet').text())
            $('#pot').html(pot);
            //各々のベット額をリセットする
            $('#playerBet').html(0);
            $('#oppBet').html(0);

            //フロップ以降はBBがアウトオブポジション（先手）
            rnd = Math.random();
            //相手はチェックorベット
            if (rnd > 0.5) {
                window.setTimeout("oppMove('check')", t);
            } else {
                window.setTimeout("oppMove('bet')", t);
            }
            break;
    }
}

const showDown = () => {
    //アクションの非表示
    $('#playerAct').hide();
    $('#oppAct').hide();

    //相手の手札をオープンする
    $('#step').hide();
    $('#oppback').hide();
    $('#oppHand').show();

    //カードを裏返す
    $('#back1').hide();
    $('#flop').show();
    $('#back2').hide();
    $('#turn').show();
    $('#back3').hide();
    $('#river').show();

    //各々のベット額をポットに集める
    pot = parseInt($('#pot').text()) + parseInt($('#oppBet').text()) + parseInt($('#playerBet').text())
    $('#pot').html(pot);

    //各々のベット額をリセットする
    $('#playerBet').html(0);
    $('#oppBet').html(0);

    //手役の表示
    $('.showDown').show()

    //ポットの分配
    switch ($('#judge').text()) {
        case 'win':
            //勝ちならポットの全額を自分のチップに加える
            $('#playerStack').html(parseInt($('#playerStack').text()) + parseInt($('#pot').text()))
            break;
        case 'lose':
            //負けならポットの全額を相手のチップに加える
            $('#oppStack').html(parseInt($('#oppStack').text()) + parseInt($('#pot').text()))
            break;
        case 'chop':
            //引き分けならポットの半額ずつをそれぞれのチップに加える（チョップ）
            $('#playerStack').html(parseInt($('#playerStack').text()) + parseInt($('#pot').text()) / 2)
            $('#oppStack').html(parseInt($('#oppStack').text()) + parseInt($('#pot').text()) / 2)
            break;
    }
    $('#pot').html(0);
    if (parseInt($('#oppStack').text()) == 0) alert('相手のチップを全て奪いました');
    if (parseInt($('#playerStack').text()) == 0) alert('チップがなくなりました');
}
