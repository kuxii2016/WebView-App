<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title></title>
<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
<script type="text/javascript">
 var Zeit = 250;
 var TimerID;
 var punkte = 9;
 var called = 0;
 
var Wuerfel = new Array(); 
    Wuerfel[0] = new Image(); 
    Wuerfel[0].src = "eins.gif";
    Wuerfel[0].val = 1;
    Wuerfel[1] = new Image(); 
    Wuerfel[1].src = "zwei.gif";
    Wuerfel[1].val = 2;
    Wuerfel[2] = new Image(); 
    Wuerfel[2].src = "drei.gif";
    Wuerfel[2].val = 3;
    Wuerfel[3] = new Image(); 
    Wuerfel[3].src = "vier.gif";
    Wuerfel[3].val = 4;
    Wuerfel[4] = new Image(); 
    Wuerfel[4].src = "fünf.gif";
    Wuerfel[4].val = 5;
    Wuerfel[5] = new Image(); 
    Wuerfel[5].src = "sechs.gif";
    Wuerfel[5].val = 6;

    function randomDice(n) {
        zahl = Math.floor(Math.random() * (n + 1));
        return zahl;
    }

    function wuerfeln(){ 
        var target = $('#thedice');
        var geworfen = randomDice(Wuerfel.length - 1);
        target.attr('src', Wuerfel[geworfen].src);
        called = Wuerfel[geworfen].val;
    }
    function throwDice() {
        TimerID = window.setInterval("wuerfeln()", Zeit);
    }

    function calc(){
        var target = $('#bet');
        var given = target.attr('value');
        if(called == given){
            punkte += 3;
            alert('YEAHHH!');
        }else{
            punkte -= 1;
            alert('leider daneben...\n:o(');
        }
         $('#punktestand').html('Du hast jetzt ' + punkte + ' Punkte');
         $('#bet').val(0);
    }
    $(document).ready(function(){
        $('#stop').click(function(){
            var target = $('#bet');
            var given = target.attr('value');
            var clear = Math.round(Math.random() * 2500);
            if(given != ''){
                window.setTimeout("window.clearInterval(TimerID)", clear);
                window.setTimeout(calc, clear);    
            }else{
                alert('Gib bitte dienen TIP ab!');
            }
        });
        $('#start').click(function(){
            throwDice();
        });
    });
</script>
<style type="text/css">
body{
    text-align:center; 
    background: #000;
    color: #ccc;
    font-size:18px;
}
img{
    border:1px solid #a8a8a8;
    margin:20px;
}
#punktestand{
    margin:30px;
    width:300px;
    height:50px;
}
</style>
</head>

<body style="">
<div id="punktestand">
Du hast jetzt 9 Punkte
</div>
<input type="button" value="Start" id="start">
<input type="button" value="Stopp" id="stop"> 
<br /><br />
<img id="thedice" src="black.gif" /><br />
<input id="bet" type="text" size="1" value="" /><br />
</body>

</html>